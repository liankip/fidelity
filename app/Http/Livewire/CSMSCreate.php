<?php

namespace App\Http\Livewire;

use App\Models\ApdChecklistInspection;
use App\Models\ApdHandover;
use App\Models\ApdRequest;
use App\Models\CSMSModel;
use App\Models\Hiradc;
use App\Models\HsePolicy;
use App\Models\JSAModel;
use App\Models\MSDSModel;
use App\Models\Otp;
use App\Models\SafetyTalkModel;
use App\Models\Sop;
use App\Models\WorkInduction;
use App\Models\WorkInstruction;
use App\Models\WorkPermitModel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;
use Webklex\PDFMerger\Facades\PDFMergerFacade as PDFMerger;

class CSMSCreate extends Component
{
    use WithFileUploads;

    public $forms = [];

    public $jsaFiles = [];
    public $hiradcFiles = [];
    public $ibprFiles = [];
    public $internalTrainingFiles = [];
    public $workInstructionFiles = [];
    public $msdsFiles = [];
    public $workPermitFiles = [];
    public $sopDocumentsFiles = [];
    public $safetyInductionFiles = [];
    public $hsePolicyFiles = [];
    public $otpFiles = [];
    public $medicalCheckUpFiles = [];
    public $apdRequestFiles = [];
    public $apdHandoverFiles = [];
    public $apdInspectionFiles = [];
    public $safetyTalkFiles = [];

    public $document_name;
    public $file_upload;

    protected $rules = [
        'document_name' => 'required',
        'file_upload' => 'required|mimes:pdf|max:10240',
    ];

    protected $messages = [
        'document_name.required' => 'Nama Dokumen tidak boleh kosong',
        'file_upload.required' => 'Upload File tidak boleh kosong',
        'file_upload.file' => 'Upload File harus berupa file',
        'file_upload.mimes' => 'Upload File harus berupa file PDF',
    ];

    public function mount()
    {
        $this->jsaFiles = JSAModel::all();
        $this->hiradcFiles = Hiradc::all();
        $this->internalTrainingFiles = \App\Models\InternalTraining::all();
        $this->workInstructionFiles = WorkInstruction::all();
        $this->msdsFiles = MSDSModel::all();
        $this->workPermitFiles = WorkPermitModel::all();
        $this->sopDocumentsFiles = Sop::all();
        $this->safetyInductionFiles = WorkInduction::all();
        $this->hsePolicyFiles = HsePolicy::all();
        $this->otpFiles = Otp::all();
        $this->medicalCheckUpFiles = \App\Models\Mcu::all();
        $this->apdRequestFiles = ApdRequest::all();
        $this->apdHandoverFiles = ApdHandover::all();
        $this->apdInspectionFiles = ApdChecklistInspection::all();
        $this->safetyTalkFiles = SafetyTalkModel::all();
    }

    public function addForm()
    {
        $this->forms[] = [
            'pilihanPertama' => '',
            'pilihanKedua' => '',
        ];
    }

    public function removeForm($index)
    {
        unset($this->forms[$index]);
        $this->forms = array_values($this->forms);
    }

    public function updatedForms($value, $key)
    {
        [$index, $field] = explode('.', $key);

        if ($field === 'pilihanPertama') {
            $pilihanPertama = $this->forms[$index]['pilihanPertama'];
            $this->forms[$index]['pilihanKedua'] = match ($pilihanPertama) {
                'jsa' => $this->jsaFiles,
                'hiradc' => $this->hiradcFiles,
                'ibpr' => $this->ibprFiles,
                'internalTraining' => $this->internalTrainingFiles,
                'workInstruction' => $this->workInstructionFiles,
                'msds' => $this->msdsFiles,
                'workPermit' => $this->workPermitFiles,
                'sopDocuments' => $this->sopDocumentsFiles,
                'safetyInduction' => $this->safetyInductionFiles,
                'hsePolicy' => $this->hsePolicyFiles,
                'otp' => $this->otpFiles,
                'medicalCheckUp' => $this->medicalCheckUpFiles,
                'apdRequest' => $this->apdRequestFiles,
                'apdHandover' => $this->apdHandoverFiles,
                'apdInspection' => $this->apdInspectionFiles,
                'safetyTalk' => $this->safetyTalkFiles,
                default => [],
            };
        }
    }

    public function create()
    {
        $this->validate();

        try {
            $pdf = PDFMerger::init();
            $uploadedFilePath = $this->file_upload->store('csms', 'public');
            $pdf->addPDF(storage_path('app/public/' . $uploadedFilePath), 'all');

            foreach ($this->forms as $form) {
                if (!empty($form['selectedFile'])) {
                    $pdf->addPDF(storage_path('app/public/' . $form['selectedFile']), 'all');
                }
            }

            $mergedFileName = 'merged_' . Str::uuid() . '.pdf';
            $pdf->merge();
            Storage::disk('local')->put('public/csms/' . $mergedFileName, $pdf->output());

            CSMSModel::create([
                'document_name' => $this->document_name,
                'file_upload' => 'csms/' . $mergedFileName,
                'updated_by' => auth()->user()->id,
            ]);

            session()->flash('message', 'Files merged successfully');
            return redirect()->route('csms.index');
        } catch (\Exception $e) {
            session()->flash('fail', 'Mohon konversikan versi pdf ke 1.4 atau lebih rendah dengan pdf2go.com atau ilovepdf.com');
        }
    }

    public function render()
    {
        return view('livewire.c-s-m-s-create');
    }
}
