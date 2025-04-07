<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;

class ProjectDocumentsController extends Controller
{
    public function index()
    {
        $documents = \App\Models\ProjectDocument::all();
        return view('masterdata.projects.documents.index', compact('documents'));
    }

    public function download(Request $request) {
        $file = \App\Models\ProjectDocument::find($request->id);
        return response()->download(storage_path('app/'.$file->path), $file->file_name);
    }

    public function destroy(Request $request) {
        $file = \App\Models\ProjectDocument::find($request->id);
        \Storage::delete($file->path);
        $file->delete();
        return redirect()->route('projects.document')
            ->with('success', 'Document has been deleted successfully.');
    }
}
