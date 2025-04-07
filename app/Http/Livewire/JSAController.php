<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Http\Request;
use App\Models\JSAModel;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;



class JSAController extends Component
{
    public $jsaData;
    public $paramId;

    public function mount(Request $request)
    {
        $this->paramId = $request->query('id');

        if ($this->paramId) {
            $this->jsaData = JSAModel::where('id', intval($this->paramId))->first();
        } else {
            $this->jsaData = null;
        }
    }

    public function render()
    {
        return view('livewire.j-s-a-controller', ['jsaData' => $this->jsaData]);
    }

    // public function handlePost(Request $request){
    //     DB::beginTransaction();

    //     try {
    //         $stringJSA = 'SMK3/SNE/JSA-';
    //         $job_no = sprintf('%02d', $request->job_no);
    //         $no_jsa = $stringJSA . $job_no;

    //         $formattedDate = Carbon::createFromFormat('d F Y', $request->jsa_date)->format('Y-m-d');

    //         if ($request->hasFile('file_upload')) {
    //             $fileUrl = $request->file_upload->store('files', 'public');
    //         } else {
    //             $fileUrl = null;
    //         }

    //         $jsa = new JSAModel([
    //             'no_jsa' => $no_jsa,
    //             'job_no' => $request->job_no,
    //             'job_name' => $request->job_name,
    //             'position_no' => $request->position_no,
    //             'position_name' => $request->position_name,
    //             'section_department' => $request->section_department,
    //             'superior_position' => $request->superior_position,
    //             'jsa_date' => $formattedDate,
    //             'file_upload' => $fileUrl,
    //             'arranged_by' => auth()->user()->id,
    //             'checked_by' => $request->checked_by,
    //             'approved_by' => $request->approved_by,
    //             'revision_num' => $request->revision_num,
    //             'reviewed' => $request->reviewed,
    //             'suggestion_notes' => $request->suggestion_notes,
    //             'job_location' => $request->job_location,
    //         ]);

    //         $jsa->save();

    //         DB::commit();

    //         return redirect()->route('jsa-view.index')->with('success','New JSA has been successfully added');
    //     } catch (\Exception $e) {
    //         // An error occurred, rollback the transaction
    //         DB::rollBack();

    //         dd($e);
    //     }

    // }

    public function handlePost(Request $request)
    {
        DB::beginTransaction();

        try {
            $stringJSA = 'SMK3/SNE/JSA-';
            $job_no = sprintf('%02d', $request->job_no);
            $no_jsa = $stringJSA . $job_no;

            $formattedDate = Carbon::createFromFormat('d F Y', $request->jsa_date)->format('Y-m-d');

            
            $existingJSA = JSAModel::where('id', intval($request->jsa_id_placeholder))->first();

            if ($request->hasFile('file_upload')) {
                $fileUrl = $request->file_upload->store('files', 'public');
            } else if ($existingJSA && $request->file('file_upload') === null) {
                $fileUrl = $existingJSA->file_upload;
            } else {
                $fileUrl = null;
            } 

            if ($existingJSA) {
                $existingJSA->update([
                    'no_jsa' => $no_jsa,
                    'job_name' => $request->job_name,
                    'position_no' => $request->position_no,
                    'position_name' => $request->position_name,
                    'section_department' => $request->section_department,
                    'superior_position' => $request->superior_position,
                    'jsa_date' => $formattedDate,
                    'file_upload' => $fileUrl,
                    'checked_by' => $request->checked_by,
                    'approved_by' => $request->approved_by,
                    'revision_num' => $request->revision_num,
                    'reviewed' => $request->reviewed,
                    'suggestion_notes' => $request->suggestion_notes,
                    'job_location' => $request->job_location,
                ]);
                $message = 'JSA has been updated successfully.';
            } else {
                
                $jsa = new JSAModel([
                    'no_jsa' => $no_jsa,
                    'job_no' => $request->job_no,
                    'job_name' => $request->job_name,
                    'position_no' => $request->position_no,
                    'position_name' => $request->position_name,
                    'section_department' => $request->section_department,
                    'superior_position' => $request->superior_position,
                    'jsa_date' => $formattedDate,
                    'file_upload' => $fileUrl,
                    'arranged_by' => auth()->user()->id,
                    'checked_by' => $request->checked_by,
                    'approved_by' => $request->approved_by,
                    'revision_num' => $request->revision_num,
                    'reviewed' => $request->reviewed,
                    'suggestion_notes' => $request->suggestion_notes,
                    'job_location' => $request->job_location,
                ]);
                $jsa->save();
                $message = 'New JSA has been successfully added.';
            }

            DB::commit();

            return redirect()->route('jsa-view.index')->with('success', $message);
        } catch (\Exception $e) {
            DB::rollBack();

            dd($e);
        }
    }
}
