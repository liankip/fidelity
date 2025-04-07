<?php

namespace App\Http\Livewire\Common;

use App\Models\ProjectDocument;
use Livewire\Component;
use Livewire\WithFileUploads;

class FileUploadForm extends Component
{
    use WithFileUploads;
    public $file;

    public function uploadDocument()
    {
        $this->validate([
            'file' => 'required|mimes:xls,xlsx'
        ]);

        $path = $this->file->store('public/documents');
       ProjectDocument::create([
            'path' => $path,
            'file_name' => $this->file->getClientOriginalName(),
            'uploaded_by' => auth()->user()->id,
        ]);

        return redirect()->route('projects.document')
            ->with('success', 'Document has been uploaded successfully.');

    }
    public function render()
    {
        return view('livewire.project.document.file-upload-form');
    }
}
