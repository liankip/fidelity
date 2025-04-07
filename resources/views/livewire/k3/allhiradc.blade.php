<div>
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h1>HIRADC (Hazard Identification Risk Assessment and Determining Control)</h1>
                <div class="alert alert-warning">
                    <strong>
                        Berikut merupakan form pembuatan HIRADC (Hazard Identification Risk
                        Assessment and Determining
                        Control)
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
                        value="" aria-label="Recipient's username" aria-describedby="button-addon2">
                </div>
            </form>
            <div class="d-flex justify-content-end">
                <a href="{{ route('k3.createHiradc') }}">
                    <button type="button" class="btn btn-success">Create +</button>
                </a>
            </div>
            <div class="overflow-x-max">
                <table class="table table-bordered fs-6 mt-4">
                    <thead class="thead-light text-center">
                        <tr class="text-center table-secondary">
                            <th style="width: 3%" class="align-middle">No</th>
                            <th style="width: 13%" class="align-middle">Nama dokumen</th>
                            <th style="width: 6%" class="align-middle">Dept</th>
                            <th style="width: 10%" class="align-middle">Unit kerja</th>
                            <th style="width: 5%" class="align-middle">Area</th>
                            <th style="width: 10%" class="align-middle">No dokumen</th>
                            <th style="width: 8%" class="align-middle">Tanggal efektif</th>
                            <th style="width: 7%" class="align-middle">No revisi</th>
                            <th style="width: 7%" class="align-middle">Reviewed date</th>
                            <th style="width: 7%" class="align-middle">Next reviewed</th>
                            {{-- <th style="width: 7%" class="align-middle">Terkait</th> --}}
                            <th style="width: 7%" class="align-middle">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($hiradcs as $key => $hiradc)
                            <tr wire:key="hiradc-{{ $hiradc->id }}" style="font-size: 13px; background-color: white">
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $hiradc->name }}</td>
                                <td>{{ $hiradc->dept }}</td>
                                <td>{{ $hiradc->work_unit }}</td>
                                <td>{{ $hiradc->area }}</td>
                                <td>{{ $hiradc->document_number }}</td>
                                <td>{{ $hiradc->effective_date ? $hiradc->effective_date : '-' }}</td>
                                <td>{{ $hiradc->revision_number ? $hiradc->revision_number : '-' }}</td>
                                <td>{{ $hiradc->reviewed_date ? $hiradc->reviewed_date : '-' }}</td>
                                <td>{{ $hiradc->next_reviewed ? $hiradc->next_reviewed : '-' }}</td>
                                {{-- <td>
                                    <p>Dibuat oleh : {{ $hiradc->user_created->name }}</p>
                                    <p>Dicheck oleh : {{ $hiradc->checked_by ? $hiradc->user_checked->name : '-' }}</p>
                                    <p>Disetujui oleh : {{ $hiradc->approved_by ? $hiradc->user_approved->name : '-' }}</p>
                                </td> --}}
                                <td>
                                    <a href="{{ route('k3.editHiradc', $hiradc->id) }}"
                                        class="btn btn-sm btn-success mt-1">Edit</a>
                                    <a href="{{ route('k3.hiradc.allList', $hiradc->id) }}"
                                        class="btn btn-sm btn-success mt-1">List Item</a>
                                    @if ($hiradc->file_upload !== null)
                                        <a href="{{ asset('storage/' . $hiradc->file_upload) }}" target="_blank"
                                            class="btn btn-sm btn-warning mt-1">Download Dokumen</a>
                                    @else
                                        <form action="{{ route('k3.hiradc.document-print', $hiradc->id) }}"
                                            method="post" target="__blank">
                                            @csrf
                                            <button class="btn btn-sm btn-success mt-1" type="submit">Print
                                                Dokumen</button>
                                        </form>
                                    @endif
                                    <button type="button" wire:click='deleteModal({{ $hiradc->id }})'
                                        class="btn btn-sm mt-1 btn-danger">Hapus</button>
                                </td>
                            </tr>
                            @if ($selectDelete === $hiradc->id)
                                <div
                                    class="bg-black opacity-25"style="height: 100%;width: 100%;left: 0;top: 0;overflow: hidden;position: fixed;z-index:999">
                                </div>

                                <div class="modal d-block" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
                                    aria-hidden="true" id="myModal">
                                    <div class="modal-dialog">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Apakah Anda Yakin Menyetujui Menghapus Data
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        wire:click="closeModal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        wire:click="closeModal" data-bs-dismiss="modal">Close</button>
                                                    <button type="button" class="btn btn-primary"
                                                        wire:click="destroy({{ $hiradc->id }})">Submit</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @empty
                            <tr>
                                <td colspan="12" class="text-center">
                                    <h5 class="my-2">Data Not Found</h5>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
