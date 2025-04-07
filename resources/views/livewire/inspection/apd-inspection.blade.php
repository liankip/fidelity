<div>
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h1>APD (Alat Pelindung Diri) Checklist Inspection</h1>
                <div class="alert alert-warning">
                    <strong>
                        Berikut merupakan form pembuatan APD (Alat Pelindung Diri) Checklist Inspection
                    </strong>
                </div>
                @if ($message = Session::get('success'))
                    <div class="alert alert-success alert-dismissible fade show mb-2" role="alert">
                        {{ $message }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                <hr>
            </div>
        </div>
    </div>
    <div class="card mt-2">
        <div class="card-body">
            <form action="" method="get" class="d-flex">
                <div class="input-group mb-3">
                    <input type="text" class="form-control" wire:model.debounce.500ms="search" name="search"
                        placeholder="Search" value="" aria-label="Recipient's username"
                        aria-describedby="button-addon2">
                </div>
            </form>
            <div class="d-flex justify-content-end">
                <a href="{{ route('k3.apd-inspection.create') }}">
                    <button type="button" class="btn btn-success">Create +</button>
                </a>
            </div>
            <div class="overflow-x-max">
                <table class="table table-bordered fs-6 mt-4">
                    <thead class="thead-light text-center">
                        <tr class="text-center table-secondary">
                            <th style="width: 5%" class="align-middle">No</th>
                            <th style="width: 18%" class="align-middle">Unit</th>
                            <th style="width: 17%" class="align-middle">Pekerjaan</th>
                            <th style="width: 15%" class="align-middle">Tanggal</th>
                            <th style="width: 19%" class="align-middle">Petugas Inspeksi</th>
                            <th style="width: 13%" class="align-middle">Dokument</th>
                            <th style="width: 13%" class="align-middle">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($data_inspection as $inspection)
                            <tr>
                                <td class="text-center align-middle">
                                    {{ $loop->iteration }}
                                </td>
                                <td class="align-middle">
                                    {{ $inspection->unit }}
                                </td>
                                <td class="align-middle">
                                    {{ $inspection->work }}
                                </td>
                                <td class="align-middle">
                                    {{ date('d F Y', strtotime($inspection->date)) }}
                                </td>
                                <td class="align-middle">
                                    {{ $inspection->user->name }}
                                </td>
                                <td class="text-center align-middle">
                                    <a href="{{ asset('storage/inspection/apd/' . $inspection->attachment) }}"
                                        target="_blank" class="btn btn-primary">Download</a>
                                </td>
                                <td class="text-center align-middle">
                                    <button data-bs-toggle="modal" data-bs-target="#delete"
                                        wire:click="setDelete({{ $inspection->id }})"
                                        class="btn btn-danger">Delete</button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">Data tidak ditemukan</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="delete" tabindex="-1" aria-labelledby="deleteLabel" aria-hidden="true"
        wire:ignore.self>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h5>Apakah Anda yakin ingin menghapus data?</h5>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tidak</button>
                    <button type="submit" class="btn btn-primary" wire:click="handleDelete({{ $delete_id }})"
                        wire:loading.attr="disabled">Ya</button>
                </div>
            </div>
        </div>
    </div>
</div>
