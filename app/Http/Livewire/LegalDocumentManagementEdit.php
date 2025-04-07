<?php

namespace App\Http\Livewire;

use App\Models\LegalDocumentManagement;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;

class LegalDocumentManagementEdit extends Component
{
    use WithFileUploads;

    public $edit_id;
    public $nama_dokumen;
    public $nomor_dokumen;
    public $asal_instansi;
    public $expired;
    public $file_upload;
    public $new_file_upload;

    protected $rules = [
        'nama_dokumen' => 'required',
        'nomor_dokumen' => 'required',
        'asal_instansi' => 'nullable',
        'expired' => 'nullable',
        'new_file_upload' => 'nullable|file|mimes:pdf'
    ];

    protected $messages = [
        'nama_dokumen.required' => 'Nama dokumen tidak boleh kosong',
        'nomor_dokumen.required' => 'Nomor dokumen tidak boleh kosong',
        'new_file_upload.file' => 'File upload harus berupa file',
        'new_file_upload.mimes' => 'File upload harus berupa file pdf'
    ];

    public function mount(LegalDocumentManagement $id)
    {
        $data = $id;
        $this->edit_id = $id;
        $this->nama_dokumen = $data->nama_dokumen;
        $this->nomor_dokumen = $data->nomor_dokumen;
        $this->asal_instansi = $data->asal_instansi;
        $this->expired = $data->expired;
        $this->file_upload = $data->file_upload;
    }

    public function edit(LegalDocumentManagement $id)
    {
        $this->validate();

        $data = $id;
        if ($this->new_file_upload) {
            $fileUrl = $this->new_file_upload->store('legal_document_management', 'public');
        } else {
            $fileUrl = $data->file_upload;
        }

        $data->update([
            'nama_dokumen' => $this->nama_dokumen,
            'nomor_dokumen' => $this->nomor_dokumen,
            'asal_instansi' => $this->asal_instansi,
            'file_upload' => $fileUrl,
            'expired' => $this->expired,
            'created_by' => Auth::user()->id,
        ]);

        return redirect()->route('legal-document-management.index')->with('success', 'Data Legal Document Berhasil diedit');
    }


    public function render()
    {
        return view('livewire.legal-document-management-edit');
    }
}
