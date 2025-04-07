<div>
    <div class="container mt-2" x-data="{isEdit : @entangle('isEdit')}">
        <div class="row">
            <div class="col-lg-12 margin-tb">
                <x-common.notification-alert/>
                <livewire:vendors.create-item-modal/>
                <div class="card overflow-x-max mt-3" wire:ignore>
                    <div class="card-body">
                        <table class="table table-auto" id="table">
                            <thead class="thead-light">
                            <tr>
                                <th class="align-middle" style="width: 5%">#</th>
                                <th class="align-middle">Nama Barang</th>
                                <th class="align-middle">Merk</th>
                                <th class="align-middle">Kategori Barang</th>
                                <th class="align-middle">Jenis Barang</th>
                                <th class="align-middle">Satuan</th>
                                <th class="align-middle">Harga</th>
                                <th class="align-middle">Aksi</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($items as $item)
                                <tr>
                                    <td class="align-middle">{{ $loop->iteration }}</td>
                                    <td class="align-middle">{{ $item->item_name }}</td>
                                    <td class="align-middle">{{ $item->brand }}</td>
                                    <td class="align-middle">{{ $item->category->name }}</td>
                                    <td class="align-middle">{{ $item->type }}</td>
                                    <td class="align-middle">{{ $item->unit->name }}</td>
                                    <td class="align-middle">{{ rupiah_format($item->price) }}</td>
                                    <td class="align-middle">
                                        <button class="btn btn-sm btn-outline-warning"
                                                wire:click="$emitTo('vendors.create-item-modal', 'editItem', {{$item->id}})">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button wire:loading.disable class="btn btn-sm btn-outline-danger"
                                                data-bs-toggle="tooltip"
                                                data-bs-title="Hapus Item" wire:click="delete({{$item->id}})">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>

                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">
                                        Anda belum menambahkan barang
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
