<div>
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h1>Legal Document Management</h1>
                <div class="alert alert-warning">
                    <strong>
                        Berikut merupakan form pembuatan Legal Document Management
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
                    <input type="text" class="form-control" wire:model="search" name="search" placeholder="Search"
                           aria-label="Recipient's username" aria-describedby="button-addon2">
                </div>
            </form>
            <div class="d-flex justify-content-end">
                <a href="{{ route('legal-document-management.create') }}">
                    <button type="button" class="btn btn-success">Create +</button>
                </a>
            </div>
            <div class="overflow-x-max">
                <table class="table table-bordered fs-6 mt-4">
                    <thead class="thead-light text-center">
                    <tr class="text-center table-secondary">
                        <th style="width: 3%" class="align-middle">No</th>
                        <th style="width: 13%" class="align-middle">Nama dokumen</th>
                        <th style="width: 10%" class="align-middle">Nomor dokumen</th>
                        <th style="width: 10%" class="align-middle">Asal Intansi</th>
                        <th style="width: 10%" class="align-middle">Expired</th>
                        <th style="width: 7%" class="align-middle">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($data as $key => $d)
                        <tr wire:key="{{ $key }}" style="font-size: 13px; background-color: white">
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $d->nama_dokumen }}</td>
                            <td>{{ $d->nomor_dokumen }}</td>
                            <td>{{ $d->asal_instansi }}</td>
                            <td>{{ $d->expired }}</td>
                            <td class="d-flex">
                                <a href="{{ route('legal-document-management.edit', $d->id) }}"
                                   class="btn btn-warning">Edit</a>
                                @if ($d->file_upload !== null)
                                    <a href="{{ route('legal-document-management.print', $d->id) }}" target="_blank"
                                       class="btn btn-info">Print</a>
                                @endif
                                <a href="{{ route('legal-document-management.delete', $d->id) }}"
                                   class="btn btn-danger">
                                    Delete
                                </a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
