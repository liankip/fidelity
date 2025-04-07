@php use App\Permissions\Permission; @endphp
@extends('layouts.app')

@section('content')
    <div class="container mt-2">
        <div class="row">
            <div class="col-lg-12 margin-tb">
                <div class="pull-left">
                    <h2 class="primary-color-sne">Master Data Item</h2>
                </div>

                @foreach (['danger', 'warning', 'success', 'info'] as $key)
                    @if (Session::has($key))
                        <div class="alert alert-{{ $key }} alert-dismissible fade show mb-1 mt-1" role="alert">
                            {{ Session::get($key) }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                            </button>
                        </div>
                    @endif
                @endforeach

                <div class="pull-right mb-2 mt-5 d-lg-flex gap-2">
                    @can(Permission::CREATE_ITEM)
                        <div class="mr-2">
                            <a class="btn btn-success" href="{{ route('items.create') }}"><i
                                    class="fa-solid fa-plus pe-2"></i>Create Item</a>
                            @hasanyrole('it|top-manager|manager')
                                <a class="btn btn-outline-success" href="{{ route('sync-unit') }}">Sync Unit</a>
                            @endhasanyrole
                        </div>
                    @endcan
                    <a class="btn btn-info mt-3 mt-lg-0" href="{{ route('items.export') }}"><i
                            class="fa-solid fa-download pe-2"></i> Export Items</a>
                </div>
            </div>
        </div>

        <div class="my-3">
            <ul class="nav nav-tabs " id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="all-items-tab" data-bs-toggle="tab" data-bs-target="#all-items"
                        type="button" role="tab" aria-controls="all-items" aria-selected="true">All Items
                    </button>
                </li>
                <li class="nav-item position-relative" role="presentation">
                    <button class="nav-link" id="need-approval-tab" data-bs-toggle="tab" data-bs-target="#need-approval"
                        type="button" role="tab" aria-controls="need-approval" aria-selected="true">Need Approval
                    </button>
                    @if ($itemNeedApproval->count() > 0)
                        <span class="badge bg-danger position-absolute top-0" style="font-size: 12px;right: -3px;margin-top: -6px">
                            {{ $itemNeedApproval->count() }}
                        </span>
                    @endif
                </li>
                @hasanyrole('it|top-manager|manager')
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="archived-items-tab" data-bs-toggle="tab" data-bs-target="#archived-items"
                            type="button" role="tab" aria-controls="all-items" aria-selected="true">Archived Items
                        </button>
                    </li>
                @endhasanyrole
            </ul>
        </div>
        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="all-items" role="tabpanel" aria-labelledby="all-items-tab">
                <div class="card primary-box-shadow">
                    <div class="card-body">

                        <div class="d-flex mt-3">
                            <div class="w-100">
                                <form action="" method="get" class="">
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="search" placeholder="Search"
                                            value="{{ $searchcompact }}">
                                        <button class="btn btn-outline-secondary" type="submit" id="button-addon2">
                                            Search
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <hr>

                        <div class="overflow-x-max">
                            <table class="table mt-3 primary-box-shadow">
                                <thead class="thead-light text-center">
                                    <tr>
                                        <th class="align-middle border-top-left">Kode barang</th>
                                        <th class="align-middle">Gambar</th>
                                        <th class="align-middle">Nama Barang</th>
                                        <th class="align-middle">Merk</th>
                                        <th class="align-middle">Kategori Barang</th>
                                        <th class="align-middle">Jenis Barang</th>
                                        <th class="align-middle">Satuan</th>
                                        <th class="align-middle">Lead Time</th>
                                        <th class="align-middle">Created At</th>
                                        <th class="align-middle border-top-right">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($items as $key => $item)
                                        <tr>
                                            <td class="text-center">{{ $item->item_code }}</td>
                                            <td class="text-center">
                                                <img src={{ $item->image == 'images/no_image.png' ? url($item->image) : 'storage/' . $item->image }}
                                                    alt="" width="100 px">
                                            </td>
                                            <td>{{ $item->name }}</td>
                                            <td class="text-center">{{ $item->brand ? $item->brand : '-' }}</td>
                                            <td>{{ $item->category ? $item->category->name : '-' }}</td>
                                            <td class="text-uppercase">{{ $item->type }}</td>
                                            <td>
                                                @forelse($item->item_unit as $unit)
                                                    <div>
                                                        - {{ $unit->unit->name }}
                                                    </div>
                                                @empty
                                                @endforelse
                                            </td>
                                            <td>{{ $item->lead_time ? $item->lead_time . ' Hari' : '-' }}</td>
                                            @if ($item->created_at)
                                                <td>
                                                    <div>{{ $item->created_at->format('d F Y - H:i') }}</div>
                                                    <div>
                                                        <em>{{ $item->created_by ? $item->item_created_by->name : '' }}</em>
                                                    </div>
                                                </td>
                                            @else
                                                <td>{{ $item->created_at }}</td>
                                            @endif
                                            <td class="text-center">
                                                <div class="d-flex">
                                                    @can(Permission::EDIT_ITEM)
                                                        <a href="{{ route('items.edit', $item->id) }}"
                                                            class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip"
                                                            data-bs-title="Edit">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <a type="button" class="btn btn-sm btn-outline-info"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#exampleModal-{{ $item->id }}"
                                                            data-bs-title="Info">
                                                            <i class="fas fa-info"></i>
                                                        </a>
                                                    @endcan
                                                    <a href="{{ route('history.items', $item->id) }}"
                                                        class="btn btn-sm btn-outline-secondary" target="_blank"
                                                        data-bs-toggle="tooltip" data-bs-title="Riwayat">
                                                        <i class="fas fa-history"></i>
                                                    </a>
                                                    @can(Permission::REMOVE_ITEM)
                                                        <form action="{{ route('items.destroy', $item->id) }}"
                                                            method="Post">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-outline-warning"
                                                                data-bs-toggle="tooltip" data-bs-title="Arsipkan">
                                                                <i class="fas fa-box-archive"></i>
                                                            </button>
                                                        </form>
                                                    @endcan
                                                </div>

                                            </td>
                                        </tr>
                                        <!-- Modal -->
                                        <div class="modal fade" id="exampleModal-{{ $item->id }}" tabindex="-1"
                                            aria-labelledby="exampleModalLabel-{{ $item->id }}" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <form action="{{ route('items.product-update', $item->id) }}"
                                                        method="POST" enctype="multipart/form-data">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="modal-header">
                                                            <h1 class="modal-title fs-5" id="exampleModalLabel">
                                                                {{ $item->name }}</h1>
                                                            <button type="button" class="btn-close"
                                                                data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div>
                                                                <div id="file-upload-container">
                                                                    <div class="row mt-3 file-upload-row">
                                                                        <div class="col-xs-12 col-sm-12 col-md-12">
                                                                            <div class="form-group">
                                                                                <label for="file_upload">Product Catalog
                                                                                    (PDF and Images)</label>
                                                                                @if (!is_null($item->file_upload))
                                                                                    <div class="mt-3">
                                                                                        <ul>
                                                                                            @foreach (json_decode($item->file_upload, true) as $file)
                                                                                                <li class="mt-1"><a
                                                                                                        href="{{ asset('storage/' . $file['file_path']) }}"
                                                                                                        target="_blank">{{ $file['original_name'] ?? $file['file_path'] }}</a>
                                                                                                    <span
                                                                                                        class="btn btn-sm btn-outline-danger rounded-pill"
                                                                                                        onclick="removeFile({{ $item->id }}, '{{ $file['file_path'] }}')"><i
                                                                                                            class="fas fa-minus"></i></span>
                                                                                                </li>
                                                                                            @endforeach
                                                                                        </ul>
                                                                                    </div>
                                                                                @endif
                                                                                <input type="file" name="file_upload[]"
                                                                                    class="form-control"
                                                                                    accept="application/pdf, image/*">
                                                                                @error('file_upload')
                                                                                    <div class="text-danger">
                                                                                        {{ $message }}</div>
                                                                                @enderror
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                {{-- <button type="button" id="add-file-upload" class="btn btn-primary mt-2">Add More Files</button> --}}
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-bs-dismiss="modal">Close</button>
                                                            <button type="submit" class="btn btn-primary">Save
                                                                changes</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4 overflow-x-max">
                            {{ $items->links() }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="need-approval" role="tabpanel" aria-labelledby="need-approval-tab">
                <livewire:items.approval-items :items="$itemNeedApproval" />
            </div>
            <div class="tab-pane fade" id="removed-items" role="tabpanel" aria-labelledby="removed-items-tab">
                <livewire:items.removed-items />
            </div>
            <div class="tab-pane fade" id="archived-items" role="tabpanel" aria-labelledby="archived-items-tab">
                <livewire:items.archived-items />
            </div>
        </div>
        <script>
            const url = new URL(window.location.href);
            const params = new URLSearchParams(url.search);
            const activeTab = params.get('tab');


            if (activeTab === 'need-approval') {
                $('#need-approval-tab').addClass('active');
                $('#all-items-tab').removeClass('active');
                $('#need-approval').addClass('show active');
                $('#all-items').removeClass('show active');
            } else if (activeTab === 'removed') {
                $('#removed-items-tab').addClass('active');
                $('#all-items-tab').removeClass('active');
                $('#removed-items').addClass('show active');
                $('#all-items').removeClass('show active');
            }

            function removeFile(itemId, filePath) {
                if (confirm('Are you sure you want to remove this file?')) {
                    fetch('/remove-file', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                item_id: itemId,
                                file_path: filePath
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                location.reload();
                            } else {
                                alert('Failed to remove file.');
                            }
                        });
                }
            }
        </script>
    </div>
@endsection
