<div>
    <h1>CSMS (Contractor Safety Management System)</h1>
    <div class="alert alert-warning">
        <strong>
            Berikut merupakan form pembuatan CSMS (Contractor Safety Management System)
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

    <div class="card mt-2">
        <div class="card-body">
            <form action="" method="get" class="d-flex">
                <div class="input-group mb-3">
                    <input type="text" class="form-control" wire:model="search" name="search" placeholder="Search"
                           value="" aria-label="Recipient's username" aria-describedby="button-addon2">
                </div>
            </form>
            <div class="d-flex justify-content-end">
                <a href="{{ route('csms.create') }}">
                    <button type="button" class="btn btn-success">Create +</button>
                </a>
            </div>
            <div class="overflow-x-max" wire:ignore>
                <table class="table table-bordered fs-6 mt-4">
                    <thead class="thead-light text-center">
                    <th>No</th>
                    <th>Document Name</th>
                    <th>Action</th>
                    </thead>
                    <tbody>
                    @foreach ($dataCSMS as $data)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $data->document_name }}</td>
                            <td class="d-flex">
                                <a href="{{ Storage::url($data->file_upload) }}" target="__blank"
                                   class="btn btn-info">Print</a>
                                <button data-bs-toggle="modal" data-bs-target="#deleteModal" class="btn btn-danger"
                                        wire:click="setDelete({{ $data->id }})">Delete
                                </button>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                 aria-hidden="true"
                 wire:ignore.self>
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <h5>Apakah Anda yakin ingin menghapus dokumen ?</h5>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tidak</button>
                            <button type="submit" class="btn btn-primary" wire:click="handleDelete({{ $deleteId }})"
                                    wire:loading.attr="disabled">Ya
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
