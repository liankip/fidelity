<div>
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <div class="d-flex">
                    <a href="{{ route('k3.hiradc') }}" class="btn btn-sm btn-secondary my-auto">
                        <i class="fa-solid fa-angle-left"></i>
                    </a>
                    <h2 class="my-auto">HIRADC - {{ $hiradc->name }}</h2>
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
        <div class="card mt-2">
            <div class="card-body">
                <div class="d-flex justify-content-end">
                    <a href="{{ route('k3.hiradc.createList', $hiradc->id) }}">
                        <button type="button" class="btn btn-success">Create +</button>
                    </a>
                </div>

                <div class="overflow-x-max">
                    <table class="table table-bordered fs-6 mt-4">
                        <thead class="thead-light text-center">
                            <tr class="text-center table-secondary">
                                <th style="width: 3%" class="align-middle">No</th>
                                <th style="width: 7%" class="align-middle">Sub name</th>
                                <th style="width: 7%" class="align-middle">Aktivitas kerja</th>
                                <th style="width: 3%" class="align-middle">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($hiradclists as $key => $hiradclist)
                                @php
                                    $data = json_decode($hiradclist->data);
                                @endphp
                                <tr wire:key="hiradc-{{ $hiradc->id }}"
                                    style="font-size: 13px; background-color: white">
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $hiradclist->sub_name ? $hiradclist->sub_name : '-' }}</td>
                                    <td>{{ $hiradclist->activity }}</td>
                                    <td>
                                        <a href="{{route('k3.hiradc.editList', [$hiradc->id, $hiradclist->id])}}" class="btn btn-sm btn-success">Edit</a>
                                        <button type="button" wire:click='deleteModal({{ $hiradclist->id }})'
                                            class="btn btn-sm mt-1 btn-danger">Hapus</button>
                                    </td>
                                </tr>
                                @if ($selectDelete === $hiradclist->id)
                                    <div
                                        class="bg-black opacity-25"style="height: 100%;width: 100%;left: 0;top: 0;overflow: hidden;position: fixed;z-index:999">
                                    </div>

                                    <div class="modal d-block" tabindex="-1" role="dialog"
                                        aria-labelledby="myModalLabel" aria-hidden="true" id="myModal">
                                        <div class="modal-dialog">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Apakah Anda Yakin Menyetujui Menghapus
                                                            Data</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            wire:click="closeModal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            wire:click="closeModal"
                                                            data-bs-dismiss="modal">Close</button>
                                                        <button type="button" class="btn btn-primary"
                                                            wire:click="destroy({{ $hiradclist->id }})">Submit</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">
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
