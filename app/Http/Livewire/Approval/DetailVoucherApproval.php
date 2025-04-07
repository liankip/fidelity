<?php

namespace App\Http\Livewire\Approval;

use App\Mail\ApprovedVoucher;
use Carbon\Carbon;
use App\Models\Voucher;
use Livewire\Component;
use App\Models\PurchaseOrder;
use App\Models\VoucherDetail;
use App\Traits\VoucherApprove as VoucherApprove;
use App\Traits\PurchaseOrderApprove as TraitsPurchaseOrderApprove;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class DetailVoucherApproval extends Component
{
    public $voucher;
    public $checked = [];
    public $additionalChecked = [];
    public $details = [];
    public $errorMessage;

    use TraitsPurchaseOrderApprove, VoucherApprove;

    public function mount(Voucher $voucher)
    {
        $this->voucher = $voucher;
    }

    public function save()
    {
        DB::beginTransaction();
        try {
            //code...

            // Step 1: Collect the IDs of the checked voucher details
            $checkedIds = collect($this->checked)->filter(function ($value, $key) {
                return $value;
            })->keys()->toArray();

            $additionalChecked = array_keys(array_filter($this->additionalChecked));

            // Decode additional_informations from JSON to array
            $additionalInformations = json_decode($this->voucher->additional_informations, true);

            // Initialize matches array to store results
            $matches = [];

            // Iterate through additional_informations to find matches
            foreach ($additionalInformations as $index => $item) {
                foreach ($additionalChecked as $checkedId) {
                    // Perform a case-insensitive comparison to check if checkedId exists in item
                    if (stripos($item, $checkedId) !== false) {
                        // Store the index where the match was found
                        $matches[] = [
                            'index' => $index,
                        ];
                        break; // Break the inner loop once a match is found
                    }
                }
            }

            // Extract items from additional_informations based on matches
            $extractedItems = [];
            foreach ($matches as $match) {
                $startIndex = $match['index'];
                $endIndex = $startIndex + 5; // Extract 5 items after the matched index

                // Ensure endIndex does not exceed the array bounds
                if ($endIndex >= count($additionalInformations)) {
                    $endIndex = count($additionalInformations) - 1;
                }

                // Extract items from startIndex to endIndex
                $extractedChunk = array_slice($additionalInformations, $startIndex, $endIndex - $startIndex + 1);

                // Merge extracted chunk into extractedItems array
                $extractedItems = array_merge($extractedItems, $extractedChunk);
            }

            $jsonExtractedItems = json_encode($extractedItems);

           
            // dd();
            // dd(empty($jsonExtractedItems));
            if (empty($checkedIds) && empty($additionalChecked) ) {
                // Step 2: Set an error message if no items are checked
                // $this->errorMessage = 'You must select at least one voucher detail to proceed.';
                // return;'
                return redirect("/voucher_aprv_waitinglists/".$this->voucher->id."/detail")->with("danger", "You must select at least one voucher detail to proceed.");
            }

            $this->voucher->additional_informations = $jsonExtractedItems;
            $this->voucher->save();

            // Step 2: Retrieve all voucher details IDs associated with the current voucher
            $allVoucherDetailIds = $this->voucher->voucher_details->pluck('id')->toArray();

            // dd($checkedIds);

            // Step 3: Calculate the IDs of the unchecked voucher details
            $uncheckedIds = array_diff($allVoucherDetailIds, $checkedIds);


            // Step 4: Delete the unchecked voucher details from the database
            VoucherDetail::whereIn('id', $uncheckedIds)->delete();
            $this->voucher->update([
                'approved_by' => auth()->user()->id,
                'date_approved' => Carbon::now(),
            ]);


            Mail::to('admin@satrianusa.group')->send(new ApprovedVoucher($this->voucher));
            DB::commit();

            $this->sendApprovedVoucherNotification($this->voucher);
            return redirect()->to('voucher_aprv_waitinglists')
                ->with('success', 'Anda Telah Me-Approve ' . $this->voucher->voucher_no . '');
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e);
        }
    }

    public function render()
    {
        return view('livewire.approval.detail-voucher-approval');
    }
}
