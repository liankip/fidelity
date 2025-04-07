<div>
    <h1>Work Permit Document</h1>
    <div class="alert alert-warning">
        <strong>
            Berikut merupakan form pembuatan Work Permit Document (Dokumen Izin Kerja)
        </strong>
    </div>
    <hr>
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
    <div class="mb-3">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
            Upload file
        </button>
    </div>

    <div class="bg-white p-4" wire:ignore>
        <table class="table table-bordered" id="permitTable">
            <thead>
                <th>No</th>
                <th>Document Name</th>
                <th>Action</th>
            </thead>
            <tbody>
                @foreach ($dataPermit as $data)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $data->document_name }}</td>
                        <td class="d-flex">
                            <button class="btn btn-warning" wire:click='setParam({{ $data->id }})'
                                data-bs-toggle="modal" data-bs-target="#updateModal" type="button">Details</button>
                            <a href="{{ Storage::url($data->file_upload) }}" target="__blank"
                                class="btn btn-info">Print</a>
                            <button data-bs-toggle="modal" data-bs-target="#deleteModal" class="btn btn-danger"
                                wire:click="setDelete({{ $data->id }})">Delete</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form class="modal-content" method="POST" action="{{ route('permit.create') }}"
                enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <strong>Document Name</strong>
                    <span class="text-danger">*</span>
                    <input type="text" class="form-control mb-2" name="document_name" placeholder="Document Name"
                        required>
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>Upload file (PDF)</strong>
                            <span class="text-danger">*</span>
                            <div class="d-flex gap-2">
                                <input type="file" class="form-control" name="file_upload" accept="application/pdf"
                                    required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Update Modal -->
    <div class="modal fade" id="updateModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true"
        wire:ignore.self>
        <div class="modal-dialog">
            <form class="modal-content" wire:submit.prevent="handleUpdate" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <strong>Document Name</strong>
                    <span class="text-danger">*</span>
                    <input type="text" class="form-control mb-2" wire:model='editName' placeholder="Document Name"
                        required>
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>Upload file (PDF)</strong>
                            <span class="text-danger">*</span>
                            <div class="d-flex gap-2">
                                <input type="file" class="form-control" wire:model='editFile'
                                    accept="application/pdf">
                            </div>
                            @if ($specificPermit !== null)
                                <a href="{{ Storage::url($specificPermit->file_upload) }}" target="__blank"
                                    class="btn btn-info mt-2">Download existing document</a>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true"
        wire:ignore.self>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h5>Apakah Anda yakin ingin menghapus data ?</h5>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tidak</button>
                    <button type="submit" class="btn btn-primary" wire:click="handleDelete({{ $deleteId }})"
                        wire:loading.attr="disabled">Ya</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            const dTable = new DataTable('#permitTable', {
                ordering: false,
            });
        });
    </script>
</div>
