<?php

namespace App\Http\Controllers;

use App\Roles\Role;
use App\Models\User;
use App\Models\Invoice;
use App\Models\EmailSend;
use App\Helpers\GetEmails;
use Illuminate\Http\Request;
use App\Mail\InvoiceUploaded;
use App\Mail\ReceiptUploaded;
use App\Models\PurchaseOrder;
use Illuminate\Support\Carbon;
use App\Models\NotificationTop;
use Illuminate\Support\Facades\Mail;
use App\Models\NotificationEmailType;
use App\Notifications\UploadedInvoice;
use App\Constants\EmailNotificationTypes;
use Illuminate\Support\Facades\Notification;
use App\Helpers\TermOfPayment\GenerateEstimate;
use App\Traits\FileUploader;
use Illuminate\Support\Facades\Storage;

class UploadInvoiceController extends Controller
{
    use FileUploader;

    public function index()
    {
        $po = PurchaseOrder::orderBy("po_no", "DESC")->get();
        return view('InvoiceUpload', compact('po'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'po_id' => 'required',
            'image' => 'required|mimes:jpeg,png,jpg,gif,pdf|max:5000',
            'tax_invoice_photo' => 'mimes:jpeg,png,jpg,gif,pdf|max:5000',
        ]);

        $imageName = time() . '.' . $request->image->extension();

        if (config('app.env') === 'production') {
            $tempPathImage = $request->image->storeAs('images/invoices', $imageName, 'local');
            $pathFileImage = 'images/invoices/' . $imageName;

            Storage::disk('gcs')->put($pathFileImage, fopen($request->image->getRealPath(), 'r+'));
            Storage::disk('local')->delete($tempPathImage);

            $imagePath = Storage::disk('gcs')->url($pathFileImage);

            $taxInvoice = $this->uploadFile($request->file('tax_invoice_photo'), 'invoices/images', 'tax_invoice_photo');
        } else {
            $request->image->move(public_path('images/invoices'), $imageName);

            $imagePath = 'images/invoices/' . $imageName;

            $taxInvoice = $this->uploadFile($request->file('tax_invoice_photo'), 'invoices/images', 'tax_invoice_photo');
        }

        $po = PurchaseOrder::where("id", $request->po_id)->first();

        $save = new Invoice;
        $save->po_id = $request->po_id;
        $save->foto_invoice = $imagePath;
        $save->tax_invoice_photo = $taxInvoice;
        $save->created_by = auth()->user()->id;
        $save->date_received = $request->date_received;
        $save->save();

        $receiver = User::role([Role::IT, Role::ADMIN_LAPANGAN])->get();
        $podata = [
            'po_no' => $po->po_no,
            "created_by" => auth()->user()->name
        ];

        Notification::send($receiver, new UploadedInvoice($podata));

        $emailReceiver = NotificationEmailType::getEmails(EmailNotificationTypes::INVOICE_UPLOADED);

        foreach ($emailReceiver as $receiver) {
            Mail::to($receiver->email)->send(new InvoiceUploaded($po, $save));
        }

        return redirect()->route('purchase-orders')
            ->with('success', 'Invoice Berhasil di Tambahkan untuk PO No ' . $po->po_no)
            ->with('image', $imageName);
    }
}
