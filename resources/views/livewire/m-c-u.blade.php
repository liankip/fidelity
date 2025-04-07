<div>
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h1>MCU (Medical Check Up)</h1>
                <div class="alert alert-warning">
                    <strong>
                        Berikut merupakan form pembuatan MCU (Medical Check Up)
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
                <a href="{{ route('k3.mcu.create') }}">
                    <button type="button" class="btn btn-success">Create +</button>
                </a>
            </div>
            <div class="overflow-x-max">
                <table class="table table-bordered fs-6 mt-4">
                    <thead class="thead-light text-center">
                        <tr class="text-center table-secondary">
                            <th style="width: 5%" class="align-middle">No</th>
                            <th style="width: 20%" class="align-middle">Nama</th>
                            <th style="width: 25%" class="align-middle">Jabatan</th>
                            <th style="width: 15%" class="align-middle">Tanggal</th>
                            <th style="width: 15%" class="align-middle">Jadwal Selanjutnya</th>
                            <th style="width: 10%" class="align-middle">Dokument</th>
                            <th style="width: 10%" class="align-middle">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($data_mcu as $mcu)
                            <tr class="text-center">
                                <td class="align-middle">{{ $loop->iteration }}</td>
                                <td class="text-start align-middle">{{ $mcu->user->name }}</td>
                                <td class="text-start align-middle">
                                    {{ $mcu->user->position ? $mcu->user->position : '-' }}
                                </td>
                                <td class="align-middle">{{ date('d F Y', strtotime($mcu->date)) }}</td>
                                <td class="align-middle">
                                    {{ $date = date('d F Y', strtotime($mcu->date . ' +1 year')) }}</td>
                                <td class="align-middle">
                                    <a href="{{ asset('storage/mcu/' . $mcu->attachment) }}" target="_blank">
                                        <button type="button" class="btn btn-primary">Download</button>
                                    </a>
                                </td>
                                <td>
                                    <button data-bs-toggle="modal" data-bs-target="#exampleModal" class="btn btn-danger"
                                        wire:click="setDelete({{ $mcu->id }})">Delete</button>
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

    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true"
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
