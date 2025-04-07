<div>
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h1>APD (Alat Pelindung Diri)</h1>
                <div class="alert alert-warning">
                    <strong>
                        Berikut merupakan form pembuatan APD (Alat Pelindung Diri)
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
                <a href="{{ route('k3.apd.create') }}">
                    <button type="button" class="btn btn-success">Create +</button>
                </a>
            </div>
            <div class="overflow-x-max">
                <table class="table table-bordered fs-6 mt-4">
                    <thead class="thead-light text-center">
                        <tr class="text-center table-secondary">
                            <th style="width: 5%" class="align-middle">No</th>
                            <th style="width: 25%" class="align-middle">Nama</th>
                            <th style="width: 15%" class="align-middle">Jabatan</th>
                            <th style="width: 15%" class="align-middle">Tanggal</th>
                            <th style="width: 20%" class="align-middle">Status</th>
                            <th style="width: 10%" class="align-middle">Dokument</th>
                            <th style="width: 10%" class="align-middle">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($data_apd as $apd)
                            <tr class="text-center">
                                <td class="align-middle">{{ $loop->iteration }}</td>
                                <td class="text-start align-middle">{{ $apd->user->name }}</td>
                                <td class="text-start align-middle">
                                    {{ $apd->user->position ? $apd->user->position : '-' }}
                                </td>
                                <td class="text-start align-middle">
                                    {{ date('d F Y', strtotime($apd->date)) }}
                                </td>
                                <td class="align-middle text-start">
                                    @if ($apd->apdHandoverCount() > 0)
                                        <div class="text-success">
                                            <strong>Sudah Diserahkan</strong>
                                        </div>
                                        <div>
                                            <div>Tanggal: {{ date('d F Y', strtotime($apd->apdHandover->date)) }}</div>
                                        </div>
                                        <div class="mt-2">
                                            <button class="btn btn-success btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#photo" wire:click="setPhoto({{ $apd->id }})">Lihat
                                                Photo</button>
                                        </div>
                                    @else
                                        <span class="text-danger">
                                            <strong>
                                                Belum Diserahkan
                                            </strong>
                                        </span>
                                    @endif
                                </td>
                                <td class="align-middle">
                                    <div class="dropdown">
                                        <button class="btn btn-secondary dropdown-toggle" type="button"
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                            Document
                                        </button>
                                        <ul class="dropdown-menu shadow">
                                            <li>
                                                <a href="{{ asset('storage/apd/request/' . $apd->attachment) }}"
                                                    class="dropdown-item">APD Request</a>
                                            </li>
                                            @if ($apd->apdHandoverCount() > 0)
                                                <li>
                                                    <a href="{{ asset('storage/apd/handover/' . $apd->apdHandover->attachment) }}"
                                                        target="_blank" class="dropdown-item">APD Handover</a>
                                                </li>
                                            @endif
                                        </ul>
                                    </div>
                                </td>
                                <td class="align-middle">
                                    <div class="dropdown">
                                        <button class="btn btn-secondary dropdown-toggle" type="button"
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                            Action
                                        </button>
                                        <ul class="dropdown-menu shadow">
                                            @if ($apd->apdHandoverCount() == 0)
                                                <li>
                                                    <button data-bs-toggle="modal" data-bs-target="#handover"
                                                        class="dropdown-item"
                                                        wire:click="setHandover({{ $apd->id }})">Handover</button>
                                                </li>
                                            @endif
                                            <li>
                                                <button data-bs-toggle="modal" data-bs-target="#delete"
                                                    class="dropdown-item"
                                                    wire:click="setDelete({{ $apd->id }})">Delete</button>
                                            </li>
                                        </ul>
                                    </div>
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

    <div class="modal fade" id="handover" tabindex="-1" aria-labelledby="handoverLabel" aria-hidden="true"
        wire:ignore.self>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5>Serah Terima APD</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">
                            <strong>
                                Penerima<span class="text-danger">*</span>
                            </strong>
                        </label>
                        <input class="form-control" value="{{ $receiver_name }}" disabled>
                        @error('receiver_name')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="name" class="form-label">
                            <strong>
                                Diserahkan Oleh<span class="text-danger">*</span>
                            </strong>
                        </label>
                        <input class="form-control" value="{{ auth()->user()->name }}" disabled>
                    </div>

                    <div class="mb-3">
                        <label for="date" class="form-label">
                            <strong>
                                Tanggal<span class="text-danger">*</span>
                            </strong>
                        </label>
                        <input type="date" class="form-control" id="date" name="date"
                            wire:model.defer="date">
                        @error('date')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">
                            <strong>
                                Description
                            </strong>
                        </label>
                        <textarea class="form-control" id="description" name="description" wire:model.defer="description">
                        </textarea>
                        @error('description')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col">
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <strong>Upload file (PDF)<span class="text-danger">*</span></strong>
                                    <div class="d-flex gap-2">
                                        <input type="file" class="form-control" wire:model.defer="attachment"
                                            accept="application/pdf">
                                    </div>
                                    <div wire:loading wire:target="attachment">Uploading...</div>
                                    @error('attachment')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col">
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <strong>Upload Photo<span class="text-danger">*</span></strong>
                                    <div class="d-flex gap-2">
                                        <input type="file" class="form-control" wire:model.defer="photo"
                                            accept="image/*,application/pdf" multiple>
                                    </div>
                                    <div>
                                        Bisa pilih lebih dari satu file
                                    </div>
                                    <div wire:loading wire:target="photo">Uploading...</div>
                                    @error('photo')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" wire:click="handleHandover({{ $handover_id }})"
                        wire:loading.attr="disabled" wire:target="attachment, photo">Simpan</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="photo" tabindex="-1" aria-labelledby="photoLabel" aria-hidden="true"
        wire:ignore.self>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5>Photo Serah Terima APD</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @foreach ($data_photo as $photo)
                        <div class="mb-3">
                            <img class="border rounded w-100"
                                src="{{ asset('storage/apd/handover/photo/' . $photo->photo) }}" class="img-fluid"
                                alt="photo">
                        </div>
                    @endforeach
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>
