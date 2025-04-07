<div>
    <h1>Internal Training List</h1>
    <div class="alert alert-warning">
        <strong>
            Berikut merupakan form pembuatan Internal Training List
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
        <a href="{{ route('internal.create') }}" class="btn btn-success">Create List</a>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
            Upload file
        </button>
    </div>

    <div class="bg-white p-4" wire:ignore>
        <table class="table table-bordered" id="internalTable">
            <thead>
                <th>No</th>
                <th>Doc No</th>
                <th>Updated At</th>
                <th>Action</th>
            </thead>
            <tbody>
                @foreach ($dataTraining as $id_no => $group)
                    <tr>
                        <td colspan="1">{{ $loop->iteration }}</td>
                        <td>{{ $group->first()->no_doc ?? '-' }}</td>
                        <td>{{ $group->first()->updated_at->format('j F Y') }}</td>
                        <td class="d-flex">
                            <a href="{{ route('internal.create', ['id' => $id_no]) }}"
                                class="btn btn-warning">Details</a>
                            <button data-bs-toggle="modal" data-bs-target="#deleteModal" class="btn btn-danger"
                                wire:click="setDelete({{ $id_no }})">Delete</button>
                            @if ($group->first()->file_upload !== null)
                                <a href="{{ Storage::url($group->first()->file_upload) }}" target="__blank"
                                    class="btn btn-info">Print</a>
                            @else
                                <a href="{{ route('internal.print', ['id' => $id_no]) }}" class="btn btn-info">Print</a>
                            @endif
                        </td>
                    </tr>
                @endforeach

            </tbody>
        </table>

    </div>

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form class="modal-content" method="POST" action="{{ route('internal.upload') }}"
                enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Modal title</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <strong>Document Number</strong>
                    <input type="text" class="form-control mb-2" name="no_doc" placeholder="Document Number"
                        required>
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>Upload file (PDF)</strong>
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
            const dTable = new DataTable('#internalTable', {
                ordering: false,
            });
        });
    </script>
</div>
