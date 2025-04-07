<?php

namespace App\Http\Livewire;

use App\Models\LegalDocumentManagement;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class LegalDocumentManagementList extends Component
{
    public $search;

    public function DeleteLegalDocumentManagement($id)
    {
        DB::beginTransaction();
        try {
            LegalDocumentManagement::where('id', $id)->delete();

            DB::commit();

            return redirect()->route('legal-document-management.index')->with('success', 'Legal Document Management has been deleted');
        } catch (\Exception $e) {
            DB::rollBack();
        }
    }

    public function PrintLegalDocumentManagement($id)
    {
        $dataLDM = LegalDocumentManagement::where('id', $id)->first();

        if ($dataLDM->file_upload !== null) {
            $filePath = Storage::disk('public')->path($dataLDM->file_upload);
            return response()->file($filePath, ['Content-Disposition' => 'inline']);
        }

        return view('prints.print-legal-document-management', compact('dataLDM'));
    }

    public function render()
    {
        $keyword = $this->search;

        $data = LegalDocumentManagement::where(function ($query) use ($keyword) {
            $query->where('nama_dokumen', 'like', '%' . $keyword . '%')
                ->orWhere('nomor_dokumen', 'like', '%' . $keyword . '%');
        })->get();

        return view('livewire.legal-document-management-list', [
            'data' => $data
        ]);
    }
}
