<div wire:ignore>
    @if ($items->count() > 0)
        @can(\App\Permissions\Permission::APPROVE_ITEM)
            <div class="my-3">
                <div class="d-flex gap-2 justify-content-end">
                    <button class="btn btn-sm btn-success" wire:click="approve">Approve</button>
                    <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal">Reject
                    </button>
                </div>
            </div>
        @endcan
    @endif
    <div class="card primary-box-shadow">

        <div class="card-body">
            <div class="overflow-x-max">
                <table class="table primary-box-shadow mt-3">
                    <thead class="thead-light">
                        <tr>
                            <th class="text-center border-top-left">#</th>
                            <th class="text-center">Kode barang</th>
                            <th class="text-center">Gambar</th>
                            <th class="text-center">Nama Barang</th>
                            <th class="text-center">Merk</th>
                            <th class="text-center">Kategori Barang</th>
                            <th class="text-center">Jenis Barang</th>
                            <th class="text-center">Satuan</th>
                            <th class="text-center">Created At</th>
                            <th class="text-center border-top-right">
                                @if (auth()->user()->can(\App\Permissions\Permission::APPROVE_ITEM))
                                    Action <br>
                                    <input type="checkbox" wire:model="select_all" wire:change="checkAll">
                                @else
                                    Status
                                @endif
                            </th>
                        </tr>
                    </thead>
                    @forelse ($items as $key => $item)
                        <tr>
                            <td>
                                <div class="d-flex">
                                    <a href="{{ route('items.edit', [$item->id, 'from' => 'approval']) }}"
                                        class="btn btn-sm btn-outline-primary">Edit</a>
                                </div>
                            </td>
                            <td class="text-center">{{ $item->item_code }}</td>
                            <td class="text-center">
                                <img src={{ $item->image == 'images/no_image.png' ? url($item->image) : 'storage/' . $item->image }}
                                    alt="" width="100 px">
                            </td>
                            <td>
                                <div>
                                    {{ $item->name }}

                                    @if ($item?->similiar && count($item->similiar) > 0)
                                        <div class="text-danger">
                                            <em>Similar items:</em>
                                        </div>
                                        @foreach ($item->similiar as $name)
                                            <div class="text-danger">
                                                - {{ $name }}
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            </td>
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
                            @if ($item->created_at)
                                <td>
                                    <div>{{ $item->created_at->format('d F Y - H:i') }}</div>
                                    <div><em>{{ $item->created_by ? $item->item_created_by->name : '' }}</em>
                                    </div>
                                </td>
                            @else
                                <td>{{ $item->created_at }}</td>
                            @endif
                            <td class="text-center">
                                @if (auth()->user()->can(\App\Permissions\Permission::APPROVE_ITEM))
                                    @if ((bool)$setting->multiple_item_approval)
                                        @if ($item->approved_by !== null && $item->approved_by !== auth()->user()->id)
                                            {{-- <span class="badge bg-success">
                                                Approved by {{ $item->approvedBy->name }}
                                            </span>
                                            <br> --}}
                                            <label>Approve</label>
                                            <input type="checkbox" wire:model="items_checklist.{{ $item->id }}">
                                        @else
                                            @if ($item->approved_by === null)
                                                <input type="checkbox"
                                                    wire:model="items_checklist.{{ $item->id }}">
                                            @else
                                                <span class="badge bg-success">
                                                    Approved by {{ $item->approvedBy->name }}
                                                </span>
                                                <br>
                                                {{-- Waiting for second approval --}}
                                            @endif
                                        @endif
                                    @else
                                        @if ($item->approved_by !== null)
                                            <span class="badge bg-success">
                                                Approved
                                            </span>
                                        @else
                                            <input type="checkbox" wire:model="items_checklist.{{ $item->id }}">
                                        @endif
                                    @endif
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center">
                                No items need approval
                            </td>
                        </tr>
                    @endforelse
                </table>
            </div>
        </div>
    </div>
    {{-- Confirmation Modals --}}
    <div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="rejectModalLabel">Reject item</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure want to reject this items?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" wire:loading.attr="disabled" wire:click="reject">
                        Reject
                    </button>
                </div>
            </div>
        </div>
    </div>

</div>
