@extends('layouts.app')

@section('content')
    <div>
        <div class="col-lg-12 mb-5">
            <a class="btn btn-danger" href="{{ route('projects.index') }}">
                <i class="fas fa-arrow-left"></i>
                Back
            </a>
        </div>
        <h3>Documents</h3>
        <hr>
        <div class="d-flex justify-content-end mt-5">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#uploadModal">
                Upload Document
            </button>
        </div>
        <x-common.notification-alert/>
      <div class="card mt-3">
          <div class="card-body">
              <table class="table table-bordered">
                  <thead class="table-secondary">
                        <tr>
                            <th>File</th>
                            <th>Uploaded By</th>
                            <th>Uploaded At</th>
                            <th>Action</th>
                        </tr>
                  </thead>
                  <tbody>
                  @forelse($documents as $document)
                      <tr>
                          <td>
                              <i class="fas fa-file-excel"></i>
                              {{ $document->file_name}}
                          </td>
                          <td>{{ $document->user->name }}</td>
                          <td>{{ $document->created_at }}</td>
                          <td>
                              <a href="{{ route('projects.document.download', $document->id) }}" class="btn btn-primary">
                                  <i class="fas fa-download"></i>
                              </a>
                              <a href="{{ route('projects.document.delete', $document->id) }}" class="btn btn-danger">
                                  <i class="fas fa-trash"></i>
                              </a>
                          </td>
                      </tr>
                  @empty
                        <tr>
                            <td colspan="4" class="text-center">No documents found</td>
                        </tr>
                  @endforelse

                  </tbody>
              </table>
          </div>
      </div>
        <!-- Modal -->
        <div class="modal fade" id="uploadModal" tabindex="-1" aria-labelledby="uploadModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="uploadModalLabel">Upload Document</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <a href="https://docs.google.com/spreadsheets/d/1P8x8EIEr4pRN7HRMSwdZBddJy3aImNecI7Uv0JJOx5A/edit?usp=sharing" class="btn btn-success btn-sm mb-2" target="_blank">
                            Download Template
                        </a>
                        <livewire:common.file-upload-form/>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
