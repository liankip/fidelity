@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <a href="{{ route('projects.index') }}" class="third-color-sne"> <i
                    class="fa-solid fa-chevron-left fa-xs"></i> Back</a>
                <h2>{{ $project->name }}</h2>
            </div>
        </div>
    </div>

    <div class="card mt-5 primary-box-shadow">
        <div class="card-body">
            <div class="w-100">
                <strong>Project Budget</strong>
                <p>{{ rupiah_format($project->value) }}</p>
            </div>
            <div class="w-100">
                <strong>Total Purchased</strong>
                <p>{{ rupiah_format($grandTotal, 0, ',', '.') }}</p>
            </div>
            @if ($project->po_number)
                <div class="w-100 mt-3">
                    <strong>PO Number</strong>
                    <p>{{ $project->po_number }}</p>
                </div>
            @endif
            <div class="w-100 mt-3">
                <strong>Project Code</strong>
                <p>{{ $project->project_code }}</p>
            </div>
            <div class="w-100 mt-3">
                <div class="d-flex justify-content-between">
                    <strong>Project Document</strong>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#uploadModal">
                        Upload Document
                    </button>
                </div>
                @if (!$project->project_documents->isEmpty())
                    <ul>
                        @foreach ($project->project_documents as $document)
                            <li>
                                <a href="{{ asset('storage/' . $document->path) }}"
                                    target="_blank">{{ $document->file_name }}</a>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p>-</p>
                @endif

            </div>
        </div>
    </div>
    <div class="card mt-5 primary-box-shadow">
        <div class="card-body">
            <div class="w-100">
                <strong>Company / Client Name</strong>
                <p>{{ $project->company_name }}</p>
            </div>
            <div class="card border">
                <div class="card-body">
                    <strong>Struktur Organisasi</strong>
                    <div class="w-100 mt-3">
                        <strong>PM In Charge</strong>
                        @php
                            $pic = json_decode($project->pic);
                        @endphp

                        @if (json_last_error() === JSON_ERROR_NONE)
                            <ul>
                                @foreach ($pic as $person)
                                    <li>{{ $person->value }}</li>
                                @endforeach
                            </ul>
                        @else
                            <p>{{ $project->pic }}</p>
                        @endif
                    </div>
                    @if ($project->sm)
                        <div class="w-100 mt-3">
                            <strong>SM In Charge</strong>
                            @php
                                $sm = json_decode($project->sm);
                            @endphp

                            <ul>
                                @foreach ($sm as $person)
                                    <li>{{ $person->value }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if ($project->logistic)
                        <div class="w-100 mt-3">
                            <strong>Logistic In Charge</strong>
                            @php
                                $logistic = json_decode($project->logistic);
                            @endphp

                            <ul>
                                @foreach ($logistic as $person)
                                    <li>{{ $person->value }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    @if ($project->ehs)
                        <div class="w-100 mt-3">
                            <strong>EHS In Charge</strong>
                            @php
                                $ehs = json_decode($project->ehs);
                            @endphp

                            <ul>
                                @foreach ($ehs as $person)
                                    <li>{{ $person->value }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    @if ($project->director)
                        <div class="w-100 mt-3">
                            <strong>Director In Charge</strong>
                            @php
                                $director = json_decode($project->director);
                            @endphp

                            <ul>
                                @foreach ($director as $person)
                                    <li>{{ $person->value }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
            </div>
            <div class="w-100">
                <strong>Project Address</strong>
                <p>{{ $project->address }}</p>
            </div>
            <div class="w-100">
                <strong>Province</strong>
                <p>{{ $project->province }}</p>
            </div>
            <div class="w-100">
                <strong>City</strong>
                <p>{{ $project->city }}</p>
            </div>
        </div>
    </div>
    <div class="modal fade" id="uploadModal" tabindex="-1" aria-labelledby="uploadModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="uploadModalLabel">Upload Document</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('projects.uploadFile', $project->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <strong>Document Upload<span class="text-danger">*</span></strong>
                            <div id="document-upload-container">
                                <div class="d-flex gap-2 mb-2 document-upload-row">
                                    <input type="file" class="form-control" name="documents[]" required/>
                                    <button type="button" class="btn btn-danger delete-document">Delete</button>
                                </div>
                            </div>
                            <button type="button" class="btn btn-success mt-1" id="add-document">Add more document</button>
                            @error('documents')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary ml-3">Save</button>
                    </form>

                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const documentUploadContainer = document.getElementById('document-upload-container');
            const addDocumentButton = document.getElementById('add-document');

            addDocumentButton.addEventListener('click', function() {
                const newDocumentRow = document.createElement('div');
                newDocumentRow.classList.add('d-flex', 'gap-2', 'mb-2', 'document-upload-row');
                newDocumentRow.innerHTML = `
                    <input type="file" class="form-control" name="documents[]" />
                    <button type="button" class="btn btn-danger delete-document">Delete</button>
                `;
                documentUploadContainer.appendChild(newDocumentRow);

                newDocumentRow.querySelector('.delete-document').addEventListener('click', function() {
                    documentUploadContainer.removeChild(newDocumentRow);
                });
            });

            document.querySelectorAll('.delete-document').forEach(button => {
                button.addEventListener('click', function() {
                    const documentRow = this.parentElement;
                    documentUploadContainer.removeChild(documentRow);
                });
            });
        });
    </script>

@endsection
