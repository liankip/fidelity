<div>
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <div class="d-flex">
                    <a href="{{ route('csms.index') }}" class="btn btn-sm btn-secondary my-auto">
                        <i class="fa-solid fa-angle-left"></i>
                    </a>
                    <h2 class="my-auto">CSMS</h2>
                </div>
                <hr>
            </div>
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-2" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @elseif (session('fail'))
                <div class="alert alert-danger alert-dismissible fade show mb-2" role="alert">
                    {{ session('fail') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
        </div>
        <div class="card mt-2">
            <div class="card-body">
                <form method="POST">
                    @csrf
                    <div class="row">
                        <div class="col">
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <strong>Nama Dokumen</strong>
                                    <div class="d-flex gap-2">
                                        <input type="text" class="form-control" wire:model="document_name"
                                               placeholder="Nama Dokumen">
                                    </div>
                                    @error('document_name') <span
                                        class="error text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <strong>Upload file (PDF)</strong>
                                    <div class="d-flex gap-2">
                                        <input type="file" class="form-control" wire:model="file_upload"
                                               accept="application/pdf">
                                    </div>
                                    @error('file_upload') <span
                                        class="error text-danger">{{ $message }}</span>@enderror
                                    <div wire:loading wire:target="fileUpload">Uploading...</div>
                                </div>
                            </div>

                            <div class="col-xs-12 col-sm-12 col-md-12">
                                @foreach ($forms as $index => $form)
                                    <div class="form-group border" style="padding: 10px">
                                        <form>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <h3>Document {{ $index + 1 }}</h3>
                                                </div>
                                                <div>
                                                    <button type="button" wire:click="removeForm({{ $index }})"
                                                            class="btn btn-danger btn-sm">
                                                        <i class="fa-solid fa-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <label for="selectPertama_{{ $index }}">Pilih
                                                Document:</label>
                                            <div class="d-flex gap-2">
                                                <select class="form-control"
                                                        wire:model="forms.{{ $index }}.pilihanPertama"
                                                        id="selectPertama_{{ $index }}">
                                                    <option value="">Pilih...</option>
                                                    <option value="jsa">JSA</option>
                                                    <option value="hiradc">HIRADC</option>
                                                    <option value="internalTraining">Internal Training</option>
                                                    <option value="workInstruction">Work Instruction</option>
                                                    <option value="msds">MSDS</option>
                                                    <option value="workPermit">Work Permit</option>
                                                    <option value="sopDocuments">SOP Documents</option>
                                                    <option value="safetyInduction">Safety Induction</option>
                                                    <option value="hsePolicy">HSE Policy</option>
                                                    <option value="otp">OTP</option>
                                                    <option value="medicalCheckUp">Medical Check Up</option>
                                                    <option value="apdRequest">APD Request</option>
                                                    <option value="apdHandover">APD Handover</option>
                                                    <option value="apdInspection">APD Inspection</option>
                                                    <option value="safetyTalk">Safety Talk</option>
                                                </select>
                                            </div>

                                            @if ($form['pilihanPertama'] === 'jsa')
                                                <div class="form-group mt-3">
                                                    <strong>JSA</strong>
                                                    <div class="d-flex gap-2">
                                                        <select wire:model="forms.{{ $index }}.selectedFile"
                                                                class="js-example-basic-single form-control">
                                                            <option value="" selected>Pilih JSA</option>
                                                            @foreach($jsaFiles as $jsa)
                                                                <option
                                                                    value="{{ $jsa->file_upload }}">{{ $jsa->job_name }} {{ $jsa->no_jsa }}
                                                                    .pdf
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            @elseif ($form['pilihanPertama'] === 'hiradc')
                                                <div class="form-group mt-3">
                                                    <strong>HIRADC</strong>
                                                    <div class="d-flex gap-2">
                                                        <select wire:model="forms.{{ $index }}.selectedFile"
                                                                class="js-example-basic-single form-control">
                                                            <option value="" selected>Pilih HIRADC</option>
                                                            @foreach($hiradcFiles as $hiradc)
                                                                <option
                                                                    value="{{ $hiradc->file_upload }}">{{ $hiradc->work_unit }}
                                                                    - {{ $hiradc->area }}
                                                                    - {{ $hiradc->document_number }}
                                                                    .pdf
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            @elseif($form['pilihanPertama'] === 'internalTraining')
                                                <div class="form-group mt-3">
                                                    <strong>Internal Training</strong>
                                                    <div class="d-flex gap-2">
                                                        <select wire:model="forms.{{ $index }}.selectedFile"
                                                                class="js-example-basic-single form-control">
                                                            <option value="" selected>Pilih Internal Training
                                                            </option>
                                                            @foreach($internalTrainingFiles as $internalTraining)
                                                                <option
                                                                    value="{{ $internalTraining->file_upload }}">{{ $internalTraining->no_doc }}
                                                                    .pdf
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            @elseif($form['pilihanPertama'] === 'workInstruction')
                                                <div class="form-group mt-3">
                                                    <strong>Work Instruction</strong>
                                                    <div class="d-flex gap-2">
                                                        <select wire:model="forms.{{ $index }}.selectedFile"
                                                                class="js-example-basic-single form-control">
                                                            <option value="" selected>Pilih Work Instruction
                                                            </option>
                                                            @foreach($workInstructionFiles as $workInstruction)
                                                                <option
                                                                    value="{{ $workInstruction->file_upload }}">{{$workInstruction->name}}
                                                                    - {{ $workInstruction->document_number }}
                                                                    .pdf
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            @elseif($form['pilihanPertama'] === 'msds')
                                                <div class="form-group mt-3">
                                                    <strong>MSDS</strong>
                                                    <div class="d-flex gap-2">
                                                        <select wire:model="forms.{{ $index }}.selectedFile"
                                                                class="js-example-basic-single form-control">
                                                            <option value="" selected>Pilih MSDS</option>
                                                            @foreach($msdsFiles as $msds)
                                                                <option
                                                                    value="{{ $msds->file_upload }}">{{ $msds->document_name }}
                                                                    .pdf
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            @elseif($form['pilihanPertama'] === 'workPermit')
                                                <div class="form-group mt-3">
                                                    <strong>MSDS</strong>
                                                    <div class="d-flex gap-2">
                                                        <select wire:model="forms.{{ $index }}.selectedFile"
                                                                class="js-example-basic-single form-control">
                                                            <option value="" selected>Pilih Work Permit</option>
                                                            @foreach($workPermitFiles as $workPermit)
                                                                <option
                                                                    value="{{ $workPermit->file_upload }}">{{ $workPermit->document_name }}
                                                                    - {{ $workPermit->document_name }}
                                                                    .pdf
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            @elseif($form['pilihanPertama'] === 'sopDocuments')
                                                <div class="form-grou mt-3">
                                                    <strong>SOP Documents</strong>
                                                    <div class="d-flex gap-2">
                                                        <select wire:model="forms.{{ $index }}.selectedFile"
                                                                class="js-example-basic-single form-control">
                                                            <option value="" selected>Pilih SOP Documents</option>
                                                            @foreach($sopDocumentsFiles as $sopDocuments)
                                                                <option
                                                                    value="{{ $sopDocuments->file_upload }}">{{ $sopDocuments->name }}
                                                                    - {{ $sopDocuments->document_number }}
                                                                    .pdf
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            @elseif($form['pilihanPertama'] === 'safetyInduction')
                                                <div class="form-group mt-3">
                                                    <strong>Safety Induction</strong>
                                                    <div class="d-flex gap-2">
                                                        <select wire:model="forms.{{ $index }}.selectedFile"
                                                                class="js-example-basic-single form-control">
                                                            <option value="" selected>Pilih Safety Induction
                                                            </option>
                                                            @foreach($safetyInductionFiles as $safetyInduction)
                                                                <option
                                                                    value="{{ $safetyInduction->file_upload }}">{{ $safetyInduction->created_at }}
                                                                    - {{ $safetyInduction->name }}
                                                                    - {{ $safetyInduction->name }}
                                                                    .pdf
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            @elseif($form['pilihanPertama'] === 'hsePolicy')
                                                <div class="form-group mt-3">
                                                    <strong>HSE Policy</strong>
                                                    <div class="d-flex gap-2">
                                                        <select wire:model="forms.{{ $index }}.selectedFile"
                                                                class="js-example-basic-single form-control">
                                                            <option value="" selected>Pilih HSE Policy</option>
                                                            @foreach($hsePolicyFiles as $hsePolicy)
                                                                <option
                                                                    value="{{ $hsePolicy->file_upload }}">{{ $hsePolicy->name }}
                                                                    .pdf
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            @elseif($form['pilihanPertama'] === 'otp')
                                                <div class="form-group mt-3">
                                                    <strong>OTP</strong>
                                                    <div class="d-flex gap-2">
                                                        <select wire:model="forms.{{ $index }}.selectedFile"
                                                                class="js-example-basic-single form-control">
                                                            <option value="" selected>Pilih OTP</option>
                                                            @foreach($otpFiles as $otp)
                                                                <option
                                                                    value="{{ $otp->file_upload }}">{{ $otp->name }}
                                                                    .pdf
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            @elseif($form['pilihanPertama'] === 'medicalCheckUp')
                                                <div class="form-group mt-3">
                                                    <strong>Medical Check Up</strong>
                                                    <div class="d-flex gap-2">
                                                        <select wire:model="forms.{{ $index }}.selectedFile"
                                                                class="js-example-basic-single form-control">
                                                            <option value="" selected>Pilih Medical Check Up
                                                            </option>
                                                            @foreach($medicalCheckUpFiles as $medicalCheckUp)
                                                                <option
                                                                    value="mcu/{{ $medicalCheckUp->attachment }}">{{ $medicalCheckUp->user->name }}
                                                                    - {{ $medicalCheckUp->user->position }}
                                                                    .pdf
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            @elseif($form['pilihanPertama'] === 'apdRequest')
                                                <div class="form-group mt-3">
                                                    <strong>APD Request</strong>
                                                    <div class="d-flex gap-2">
                                                        <select wire:model="forms.{{ $index }}.selectedFile"
                                                                class="js-example-basic-single form-control">
                                                            <option value="" selected>Pilih APD
                                                            </option>
                                                            @foreach($apdRequestFiles as $apdRequest)
                                                                <option
                                                                    value="apd/request/{{ $apdRequest->attachment }}">{{ $apdRequest->user->name }}
                                                                    - {{ $apdRequest->user->position }} {{ date('d F Y', strtotime($apdRequest->date)) }}
                                                                    .pdf
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            @elseif($form['pilihanPertama'] === 'apdHandover')
                                                <div class="form-group mt-3">
                                                    <strong>APD Handover</strong>
                                                    <div class="d-flex gap-2">
                                                        <select wire:model="forms.{{ $index }}.selectedFile"
                                                                class="js-example-basic-single form-control">
                                                            <option value="" selected>Pilih APD
                                                            </option>
                                                            @foreach($apdHandoverFiles as $apdHandover)
                                                                <option
                                                                    value="apd/handover/{{ $apdHandover->attachment }}">{{ $apdHandover->apdRequest->user->name }}
                                                                    - {{ $apdHandover->apdRequest->user->position }} {{ date('d F Y', strtotime($apdHandover->date)) }}
                                                                    .pdf
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            @elseif($form['pilihanPertama'] === 'apdInspection')
                                                <div class="form-group mt-3">
                                                    <strong>APD Inspection</strong>
                                                    <div class="d-flex gap-2">
                                                        <select wire:model="forms.{{ $index }}.selectedFile"
                                                                class="js-example-basic-single form-control">
                                                            <option value="" selected>Pilih APD Inspection
                                                            </option>
                                                            @foreach($apdInspectionFiles as $apdInspection)
                                                                <option
                                                                    value="inspection/apd/{{ $apdInspection->attachment }}">{{ $apdInspection->unit }}
                                                                    - {{ $apdInspection->work }}
                                                                    .pdf
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            @elseif($form['pilihanPertama'] === 'safetyTalk')
                                                <div class="form-group mt-3">
                                                    <strong>Safety Talk</strong>
                                                    <div class="d-flex gap-2">
                                                        <select wire:model="forms.{{ $index }}.selectedFile"
                                                                class="js-example-basic-single form-control">
                                                            <option value="" selected>Pilih Safety Talk
                                                            </option>
                                                            @foreach($safetyTalkFiles as $safetyTalk)
                                                                <option
                                                                    value="{{ $safetyTalk->file_upload }}">{{ $safetyTalk->location }} {{ $safetyTalk->job_status }} {{ $safetyTalk->activity_date }}
                                                                    .pdf
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            @endif
                                        </form>
                                    </div>
                                @endforeach
                                <button class="btn btn-primary mb-2" type="button"
                                        wire:click="addForm">Add Document
                                </button>
                            </div>
                        </div>
                    </div>
                    <button @if(empty($forms)) disabled @endif wire:click.prevent="create" wire:loading.remove
                            type="submit" class="btn btn-success">Create
                        +
                    </button>
                    <button wire:loading wire:target='create' type="submit" class="btn btn-secondary"
                            disabled>Saving...
                    </button>
                    <a class="btn btn-secondary" href="{{ route('k3.hiradc') }}">
                        Cancel
                    </a>
                </form>
            </div>
        </div>
    </div>
</div>
