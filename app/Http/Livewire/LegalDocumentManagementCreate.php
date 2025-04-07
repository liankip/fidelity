<?php

namespace App\Http\Livewire;

use App\Models\LegalDocumentManagement;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;

class LegalDocumentManagementCreate extends Component
{
    use WithFileUploads;

    public $nama_dokumen;
    public $nomor_dokumen;
    public $asal_instansi;
    public $expired;
    public $file_upload;

    protected $rules = [
        'nama_dokumen' => 'required|max:255',
        'nomor_dokumen' => 'required|max:255',
        'asal_instansi' => 'nullable|max:255',
        'expired' => 'nullable',
        'file_upload' => 'required|file|mimes:pdf'
    ];

    protected $messages = [
        'nama_dokumen.required' => 'Nama dokumen tidak boleh kosong',
        'nama_dokumen.max' => 'Nama dokumen tidak boleh lebih dari 255 karakter',
        'nomor_dokumen.required' => 'Nomor dokumen tidak boleh kosong',
        'nomor_dokumen.max' => 'Nomor dokumen tidak boleh lebih dari 255 karakter',
        'asal_instansi.max' => 'Asal instansi tidak boleh lebih dari 255 karakter',
        'file_upload.required' => 'File upload tidak boleh kosong',
        'file_upload.file' => 'File upload harus berupa file',
        'file_upload.mimes' => 'File upload harus berupa file pdf'
    ];

    public function create()
    {
        $this->validate();

        if ($this->file_upload) {
            $fileUrl = $this->file_upload->store('legal_document_management', 'public');
        } else {
            $fileUrl = null;
        }

        LegalDocumentManagement::create([
            'nama_dokumen' => $this->nama_dokumen,
            'nomor_dokumen' => $this->nomor_dokumen,
            'asal_instansi' => $this->asal_instansi,
            'file_upload' => $fileUrl,
            'expired' => $this->expired,
            'created_by' => Auth::user()->id,
        ]);

        return redirect()->route('legal-document-management.index')->with('success', 'Data Legal Document berhasil disimpan');
    }

    public function render()
    {
        return view('livewire.legal-document-management-create');
    }
}
