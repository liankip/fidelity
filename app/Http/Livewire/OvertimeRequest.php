<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Overtime;
use Illuminate\Http\Request;
use DB;


class OvertimeRequest extends Component
{
    public $overtimeId;
    public $realization;
    public $filter = 'all';
    public $search = '';

    public function mount($filter = 'all')
    {
        $this->filter = $filter;
    }

    public function render()
    {
        $overtimeData = $this->getFilteredData();

        return view('livewire.overtime-request', [
            'overtimeData' => $overtimeData,
        ]);
    }

    public function setFilter($filter)
    {
        // Redirect with the new filter parameter
        return redirect()->route('overtime.filterData', ['filter' => $filter]);
    }

    public function getFilteredData()
    {
        $query = Overtime::query();

        if ($this->filter === 'new') {
            $query->where('status', 'New');
        } elseif ($this->filter === 'approved') {
            $query->where('status', 'Approved');
        } elseif ($this->filter === 'rejected') {
            $query->where('status', 'Rejected');
        }

        // Order by status in ascending order, with 'New' prioritized
        $query->orderByRaw("FIELD(status, 'New') DESC, status ASC");

        return $query->get()->groupBy('overtime_id');
    }



    public function updateData(Request $request){
        $data = $request->all();
        
        try {
            DB::beginTransaction();

            $overtimeId = $data['overtime_id'];
            $overtimeData = Overtime::where('overtime_id',$overtimeId)->get();
            $realization = intval(str_replace(',', '', $data['realization']));

            foreach ($overtimeData as $record) {
                $record->update([
                    'realization' => $realization,
                    'updated_by' => auth()->user()->id 
                ]);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            dd($e);
        }

        return redirect()->back();
    }

    public function approvalData(Request $request){
        $data = $request->all();
        try {
            DB::beginTransaction();

            $overtimeId = $data['overtime_id'];
            $overtimeData = Overtime::where('overtime_id',$overtimeId)->get();
            $status = $data['status'];

            foreach ($overtimeData as $record) {
                $record->update([
                    'status' => $status,
                    'updated_by' => auth()->user()->id 
                ]);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            dd($e);
        }

        $message = ($status == 'Approved') ? 'Data has been approved.' : 'Data has been rejected.';

        return redirect()->back()->with('success', $message);
    }
}
