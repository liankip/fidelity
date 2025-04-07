@php use Carbon\Carbon; @endphp
<div>
    <div class="row">
        <div class="col-lg-12 margin-tb">
            {{-- <div class="pull-right mb-2">
                    <a class="btn btn-success" href="{{ route('payments.create') }}"> Create payment</a>
                </div> --}}
        </div>
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
    <h2>Barang Keluar</h2>
    <hr>
    <a class="btn btn-info" href="{{ route('inventory.out') }}">All Items</a>
    {{-- @if($draftStatus)
    <livewire:invontory.draft-item/>
    @else --}}
    <form action="">
        <div class="card mt-5">
            <div class="card-body">
                <div class="form-group">
                        <div class="input-group mb-3 mt-3">
                            <input type="text" class="form-control" name="search" placeholder="Search Item Name"
                                   wire:model.debounce.500ms="search" spellcheck="false" data-ms-editor="true">
                        </div>
                        <div wire:loading wire:target="projectmodel, search">
                            <p>Loading...</p>
                        </div>
                        <table class="table table-bordered" style="border-color: #c8c8c8;">
                            <tbody style="border-color: #c8c8c8;">
                            <tr style="background-color: #e7e2fd">
                                {{-- <th class="text-center" style="border-color: #c8c8c8; width: 5%">No</th> --}}
                                <th class="text-center" style="border-color: #c8c8c8;">Nama Item</th>
                                <th class="text-center" style="border-color: #c8c8c8;">Catatan</th>
                                <th class="text-center" style="border-color: #c8c8c8;">Bagian</th>
                                <th class="text-center" style="border-color: #c8c8c8;">Stok Tersedia</th>
                                <th class="text-center" style="border-color: #c8c8c8;">Stok keluar</th>
                                <th class="text-center" style="border-color: #c8c8c8;">Stok Sisa</th>
                                <th class="text-center" style="border-color: #c8c8c8;">Aksi</th>
                            </tr>
                            @php
                                $lastItemName = '';
                                $lastPartof = '';
                                // $iteration = 1;
                            @endphp
                            @foreach ($items as $key => $item)
                                {{-- {{}} --}}
                                <tr>
                                    @if ($item->inventory->item->name !== $lastItemName)
                                        {{-- <td rowspan="{{ $itemRowspan[$item->inventory->item->name] }}">{{ $iteration }}</td> --}}
                                        <td rowspan="{{ $itemRowspan[$item->inventory->item->name] }}">
                                            {{ $item->inventory->item->name }}
                                            @php
                                                $lastItemName = $item->inventory->item->name;
                                                $lastPartof = '';  // Reset partof for a new item name
                                                // $iteration++;
                                            @endphp
                                        </td>
                                        <td rowspan="{{ $itemRowspan[$item->inventory->item->name] }}">
                                            @if (isset($notes[$item->id]))
                                                <textarea wire:model.defer="notes.{{ $item->id }}" required rows="4"
                                                          class="form-control"></textarea>
                                                <div class="flex gap-5 mt-2">
                                                    <button class="btn btn-sm btn-success" type="button"
                                                            wire:click="saveNote({{ $item->id }})" title="Save">
                                                        <i class="fa-solid fa-check"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-danger" type="button"
                                                            wire:click="cancelNote({{ $item->id }})" title="Cancel">
                                                        <i class="fa-solid fa-xmark"></i>
                                                    </button>
                                                </div>
                                            @elseif (!empty($item->note))
                                                <div>{!! nl2br(e($item->note)) !!}</div>
                                                <button class="btn btn-sm btn-primary" type="button"
                                                        wire:click="editNote({{ $item->id }})" title="Edit">
                                                    <i class="fa-regular fa-pen-to-square"></i>
                                                </button>
                                            @else
                                                <button class="btn btn-sm btn-success" type="button"
                                                        wire:click="addNoteField({{ $item->id }})">Tambah
                                                    Catatan
                                                </button>
                                            @endif
                                            {{-- @if ($itemNote === $item->id)
                                                <textarea wire:model='note' required rows="4" class="form-control"></textarea>
                                                <div class="flex gap-5 mt-2">
                                                    <button class="btn btn-sm btn-success" type="button"
                                                        wire:click='itemNoteSave({{ $item->id }})' title="Save"><i
                                                            class="fa-solid fa-check"></i></button>
                                                    <button class="btn btn-sm btn-danger" type="button"
                                                        wire:click='itemNoteCancel({{ $item->id }})' title="Cancel"><i
                                                            class="fa-solid fa-xmark"></i></button>
                                                </div>
                                            @else
                                                <button class="btn btn-sm btn-success" type="button"
                                                    wire:click='itemNote({{ $item->id }})'>Tambah catatan</button>
                                            @endif --}}

                                        </td>
                                    @endif
                                    @if ($item->partof !== $lastPartof)
                                        <td rowspan="{{ $partofRowspan[$item->inventory->item->name][$item->partof] }}">
                                            {{ $item->partof }}
                                            @php
                                                $lastPartof = $item->partof;
                                            @endphp
                                        </td>

                                        <td rowspan="{{ $partofRowspan[$item->inventory->item->name][$item->partof] }}">
                                            {{ number_format($item->earlystock) }}
                                        </td>

                                        @php
                                            $filteredInventoryOuts = $item->inventory_outs->where('partof', $item->partof);
                                        @endphp
                                        <td rowspan="{{ $partofRowspan[$item->inventory->item->name][$item->partof] }}">
                                            @if ($item->inventory_outs)
                                                {{ $filteredInventoryOuts->sum('out') }}
                                            @else
                                                -
                                            @endif
                                        </td>

                                        <td rowspan="{{ $partofRowspan[$item->inventory->item->name][$item->partof] }}">{{ ($item->earlystock) - ($filteredInventoryOuts->sum('out')) }}</td>

                                        <td rowspan="{{ $partofRowspan[$item->inventory->item->name][$item->partof] }}">
                                            <div class="d-flex gap-1">
                                                <button class="btn btn-sm btn-success" type="button"
                                                        wire:click="inventoryOutModal({{ $item->id }}, '{{ $item->partof }}')">
                                                    Barang
                                                    Keluar
                                                </button>
                                                <div class="d-flex gap-1 align-items-center">
                                                    @php
                                                        $getData = $item->inventory_outs->where('inventory_detail_id', $item->id)->where('project_id', $item->project->id)->where('partof', $item->partof)->first();
                                                        $logUser = auth()->user()->id;

                                                        if (!$getData) {
                                                            $isReserved = 'false';
                                                            $ownerId = null;
                                                        } else {
                                                            $isReserved = $getData->reserved;
                                                            $ownerId = $getData->owner_id;
                                                            if ($ownerId !== null) {
                                                                $ownerUsername = App\Models\User::where('id', $ownerId)->first()->name;
                                                            }
                                                        }

                                                    @endphp
                                                    @if($ownerId !== null && $ownerId != $logUser)
                                                        <button class="btn btn-secondary" disabled>Listed
                                                            by {{ $ownerUsername }}</button>
                                                    @else
                                                        <input type="checkbox" name="reserve"
                                                               wire:click="toggleReserve({{ $item->id }}, '{{ $item->project->id }}', '{{ $item->partof }}')"
                                                               @if($isReserved === 'true')
                                                                   checked
                                                            @endif>
                                                        <label
                                                            for="reserve">{{ $isReserved === 'true' ? 'Listed' : 'List' }}</label>
                                                    @endif
                                                </div>
                                            </div>
                                            <button class="btn btn-sm btn-warning" type="button"
                                                    wire:click="inventoryHistory({{ $item->id }}, '{{ $item->partof }}')">
                                                History
                                                Barang Keluar
                                            </button>
                                        </td>
                                    @endif
                                </tr>
                                @if ($selectedItem === $item->id && $selectedItemPart === $item->partof)
                                    <div
                                        class="bg-black opacity-25"
                                        style="height: 100%;width: 100%;left: 0;top: 0;overflow: hidden;position: fixed;z-index:999">
                                    </div>

                                    <div class="modal d-block" tabindex="-1" role="dialog"
                                         aria-labelledby="myModalLabel" aria-hidden="true" id="myModal" wire:key="{{ $key }}">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">
                                                        {{ $detailItem->inventory->item->name }} - {{ $item->partof }}
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            wire:click="closeModal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <form method="POST">
                                                        {{-- <div class="form-group mb-3">
                                                            <strong>Upload Foto Barang Terpakai</strong>
                                                            <div class="d-flex gap-2">
                                                                <input type="file" class="form-control"
                                                                    wire:model="item_pic"
                                                                    accept="image/png, image/gif, image/jpeg">
                                                            </div>
                                                            <div wire:loading wire:target="item_pic">
                                                                Uploading...</div>
                                                            @if ($item_pic)
                                                                <img src="{{ $item_pic->temporaryUrl() }}"
                                                                    class="img-fluid w-75 mt-1">
                                                            @endif
                                                            @error('item_pic')
                                                                <div class="text-danger">{{ $message }}</div>
                                                            @enderror
                                                        </div> --}}

                                                        <div class="form-group mb-3">
                                                            <strong>Jumlah barang<span
                                                                    class="text-danger">*</span></strong>
                                                                    <input type="number" wire:model="out" class="form-control" placeholder="Jumlah barang" step="0.01" />
                                                            @error('out')
                                                            <div class="text-danger">{{ $message }}</div>
                                                            @enderror
                                                            <div class="d-flex align-items-center gap-1">
                                                                <input type="checkbox" id="halfStatus"
                                                                       wire:model="halfStatus">
                                                                <label for="halfStatus" style="font-size: 10pt;">Terpakai
                                                                    sebagian</label>
                                                            </div>
                                                        </div>

                                                        <div class="form-group">
                                                            <strong>Pengguna<span
                                                                    class="text-danger">*</span></strong>
                                                            <select wire:model.debounce.150ms='user_model'
                                                                    name="user_id" id="user_id"
                                                                    class="js-example-basic-single form-select @error('user_model') is-invalid @enderror @if (!empty($user_model) && !$errors->has('user_model')) is-valid @endif">

                                                                <option value="" hidden readonly>Pilih User
                                                                </option>

                                                                @foreach ($users as $user)
                                                                    <option value="{{ $user->id }}">
                                                                        {{ $user->name }}
                                                                    </option>
                                                                @endforeach

                                                            </select>

                                                            @error('user_model')
                                                            <div class="text-danger">{{ $message }}</div>
                                                            @enderror
                                                        </div>

                                                        <div class="form-group mb-3">
                                                            <strong>Peruntukan<span
                                                                    class="text-danger">*</span></strong>
                                                            <textarea wire:model='desc' required rows="4"
                                                                      class="form-control"></textarea>
                                                            @error('desc')
                                                            <div class="text-danger">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </form>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                            wire:click="closeModal"
                                                            data-bs-dismiss="modal">Close
                                                    </button>
                                                    <button type="button" class="btn btn-primary"
                                                            wire:click="save({{ $item->id }}, '{{ $item->partof }}')">
                                                        Submit
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                @if ($selectedItemHistory === $item->id && $selectedItemHistoryPart === $item->partof)
                                    <div
                                        class="bg-black opacity-25"
                                        style="height: 100%;width: 100%;left: 0;top: 0;overflow: hidden;position: fixed;z-index:999">
                                    </div>

                                    <div class="modal d-block" tabindex="-1" role="dialog"
                                         aria-labelledby="myModalLabel" aria-hidden="true" id="myModal" wire:key="{{ $key }}">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="btn-close"
                                                            data-bs-dismiss="modal" wire:click="closeModal"
                                                            aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    @forelse ($detailItem as $key => $detail)
                                                        <div class="card" style="border: 1px solid blue;">
                                                            <div class="card-body">
                                                                @if ($selectedInventoryOut === $detail->id)
                                                                    <form method="POST">
                                                                        {{-- <div class="form-group mb-3">
                                                                            <strong>Upload Foto Barang
                                                                                Terpakai</strong>
                                                                            <div class="">
                                                                                <input type="file"
                                                                                    class="form-control"
                                                                                    wire:model="new_item_pic"
                                                                                    accept="image/png, image/gif, image/jpeg">
                                                                            </div>
                                                                            <div wire:loading
                                                                                wire:target="new_item_pic">
                                                                                Uploading...</div>
                                                                            @if ($new_item_pic)
                                                                                <img src="{{ $new_item_pic->temporaryUrl() }}"
                                                                                    class="img-fluid w-75 mt-1">
                                                                            @else
                                                                                <img src="{{ asset('storage/' . $item_pic) }}"
                                                                                    class="img-fluid  w-75"
                                                                                    alt="">
                                                                            @endif
                                                                            @error('item_pic')
                                                                                <div class="text-danger">
                                                                                    {{ $message }}</div>
                                                                            @enderror
                                                                        </div> --}}

                                                                        <div class="form-group mb-3">
                                                                            <strong>Jumlah barang<span
                                                                                    class="text-danger">*</span></strong>
                                                                                    <input type="number" wire:model="out" class="form-control" placeholder="Jumlah barang" step="0.01" />
                                                                            @error('out')
                                                                            <div class="text-danger">
                                                                                {{ $message }}</div>
                                                                            @enderror
                                                                        </div>

                                                                        <div class="form-group">
                                                                            <strong>Pengguna<span
                                                                                    class="text-danger">*</span></strong>
                                                                            <select
                                                                                wire:model.debounce.150ms='user_model'
                                                                                name="user_id" id="user_id"
                                                                                class="js-example-basic-single form-select @error('user_model') is-invalid @enderror @if (!empty($user_model) && !$errors->has('user_model')) is-valid @endif">

                                                                                <option value="" hidden
                                                                                        readonly>Pilih User
                                                                                </option>

                                                                                @foreach ($users as $user)
                                                                                    <option
                                                                                        value="{{ $user->id }}">
                                                                                        {{ $user->name }}
                                                                                    </option>
                                                                                @endforeach

                                                                            </select>

                                                                            @error('user_model')
                                                                            <div class="text-danger">
                                                                                {{ $message }}</div>
                                                                            @enderror
                                                                        </div>

                                                                        <div class="form-group mb-3">
                                                                            <strong>Tujuan<span
                                                                                    class="text-danger">*</span></strong>
                                                                            <textarea wire:model='desc' required
                                                                                      rows="4"
                                                                                      class="form-control"></textarea>
                                                                            @error('desc')
                                                                            <div class="text-danger">
                                                                                {{ $message }}</div>
                                                                            @enderror
                                                                        </div>
                                                                        {{-- <div class="form-group mb-3">
                                                                            <strong>Tanggal Keluar<span
                                                                                    class="text-danger">*</span></strong>
                                                                            <input type="date" wire:model="dateOut"
                                                                                   class="form-control"/>
                                                                            @error('dateOut')
                                                                            <div
                                                                                class="text-danger">{{ $message }}</div>
                                                                            @enderror
                                                                        </div> --}}
                                                                    </form>
                                                                    <div class="flex gap-5 mt-2">
                                                                        <button class="btn btn-sm btn-success"
                                                                                type="button"
                                                                                wire:click="saveEditInventory({{ $detail->id }})"
                                                                                title="Save">
                                                                            <i class="fa-solid fa-check"></i>
                                                                        </button>
                                                                        <button class="btn btn-sm btn-danger"
                                                                                type="button"
                                                                                wire:click="cancelEditInventory({{ $detail->id }})"
                                                                                title="Cancel">
                                                                            <i class="fa-solid fa-xmark"></i>
                                                                        </button>
                                                                    </div>
                                                                @else
                                                                    <div class="d-flex justify-content-between">
                                                                        <h5 class="card-title d-flex gap-2">
                                                                            {{ Carbon::parse($detail->created_at)->format('d F Y') }}
                                                                        </h5>
                                                                        
                                                                            <button class="btn btn-sm btn-primary"
                                                                            type="button"
                                                                            wire:click="editInventory({{ $detail->id }})"
                                                                            title="Edit">Edit</i>
                                                                            </button>
                                                                        
                                                                    </div>

                                                                    <a href="{{ asset('storage/' . $detail->item_pic) }}"
                                                                       target="_blank">
                                                                        <img
                                                                            src="{{ asset('storage/' . $detail->item_pic) }}"
                                                                            class="img-fluid  w-75"
                                                                            alt="">
                                                                    </a>

                                                                    <div class="my-3">
                                                                        <h6 class="card-subtitle mb-2 text-muted">
                                                                            Stok Keluar
                                                                        </h6>
                                                                        <p class="fw-bold" style="color: black">
                                                                            {{ $detail->out ? $detail->out : '-'  }}
                                                                            @if ($detail->is_partial === 'true')
                                                                                <span
                                                                                    class="bg-info rounded p-1 fs-6 text-white">Partial</span>
                                                                            @endif
                                                                        </p>
                                                                    </div>
                                                                    @if ($detail->user)
                                                                        <div class="my-3">
                                                                            <h6
                                                                                class="card-subtitle mb-2 text-muted">
                                                                                Pengguna
                                                                            </h6>
                                                                            <p class="fw-bold"
                                                                               style="color: black">
                                                                                {{ $detail->user->name }}
                                                                            </p>
                                                                        </div>
                                                                    @endif
                                                                    <div class="my-3">
                                                                        <h6 class="card-subtitle mb-2 text-muted">
                                                                            Tujuan
                                                                        </h6>
                                                                        <p style="color: black">
                                                                            {{ $detail->desc }}
                                                                        </p>
                                                                    </div>
                                                                    @if($detail->hasEditHistory())
                                                                        <div class="my-3">
                                                                            <h6 class="card-subtitle mb-2 text-muted">History Edit</h6>
                                                                            @foreach ($detail->editHistory as $history)
                                                                                <li style="color: black">
                                                                                    {{ $history->created_at->format('d M Y H:i') }} - Stock sebelumnya: {{ $history->prev_out_qty }}
                                                                                </li>
                                                                            @endforeach
                                                                        </div>
                                                                    @endif
                                                                @endif
                                                            </div>
                                                        </div>
                                                    @empty
                                                        Tidak ada history data
                                                    @endforelse
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                            <tr>
                            </tr>
                            </tbody>
                        </table>
                        {{ $items->links() }}
                    
                </div>
            </div>
        </div>
    </form>
    {{-- @endif --}}
    @push('javascript')
        <script>
            $(document).ready(function () {
                $('#user_id').select2({
                    theme: 'bootstrap-5',
                });

                $(document).on('change', '#user_id', function (e) {
                @this.set('user_model', e.target.value)
                    ;
                });
            });

            document.addEventListener("livewire:load", () => {
                Livewire.hook('message.processed', (message, component) => {
                    $('#user_id').select2({
                        theme: 'bootstrap-5',
                    });

                    $(document).on('change', '#user_id', function (e) {
                    @this.set('user_model', e.target.value)
                        ;
                    });
                });
            });
        </script>
    @endpush
</div>
