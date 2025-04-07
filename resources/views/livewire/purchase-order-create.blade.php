<div class="mt-2">
    @push('javascript')
        <script>
            function emitsavesipplier() {
                let btn = document.getElementById("btnsavesupplier");
                btn.disabled = true;
                Livewire.emit("savesupplieremit");
            }
        </script>
    @endpush
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2 class="primary-color-sne">Create Purchase Order</h2>
            </div>
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


    @csrf
    <div class="card card primary-box-shadow mt-5">
        <div class="card-body">
            @if (count($purchaserequestdetail) == 0)
                @csrf
                <a class="btn btn-success" href="{{ route('itempr.index') }}">Tambah Barang</a>
            @endif
            <div>
                <table class="table primary-box-shadow table-responsive">
                    <thead class="text-center thead-light">
                        <th class="border-top-left">No</th>
                        <th>Item Name</th>
                        <th>Product Description</th>
                        <th>Quantity</th>
                        <th>Notes</th>
                        <th>Supplier</th>
                        <th>Unit</th>
                        <th>Harga</th>
                        <th>Tax</th>
                        <th>Jumlah</th>
                        <th>Payment Method</th>
                        <th class="border-top-right">Pengiriman</th>
                    </thead>

                    @foreach ($purchaserequestdetail as $key => $val)
                        <tr wire:key="{{ $key }}">
                            <td>
                                <input hidden type="text" name="item_id[]" class="form-control" placeholder="item_id"
                                    value="{{ $val->item_id }}">
                                {{ $key + 1 }}
                            </td>
                            <td @if ($val->is_relocated) wire:ignore @endif>
                                    {{ $val->item->name }} 
                                    @if($val->is_bulk == 1)
                                        <span class="badge badge-primary">
                                            Bulked
                                        </span>
                                    @endif
                            </td>

                            {{-- Supplier Description --}}
                            <td class="text-center">
                                @if ($val->edit_name)
                                    <div class="form-group">
                                        <input type="text" class="form-control" placeholder="Product description"
                                            wire:model.defer="purchaserequestdetail.{{ $key }}.new_item_name">
                                        @error('item_name')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="d-flex gap-2 mt-1">
                                        <button type="button" wire:click="saveEditName({{ $key }})"
                                            class="btn btn-outline-success btn-sm">
                                            <i class="fa-solid fa-floppy-disk"></i>&nbsp;&nbsp;<span>Simpan</span>
                                        </button>
                                        <button type="button" wire:click="setEditName({{ $key }}, false)"
                                            class="btn btn-outline-danger btn-sm">
                                            <i class="fa-solid fa-xmark"></i>&nbsp;&nbsp;<span>Batal</span>
                                        </button>
                                    </div>
                                @else
                                <span>{{ $val->new_item_name }} </span>

                                <div>
                                    @if(!$val->is_bulk && !$val->is_relocated)
                                        <button type="button" wire:click="setEditName({{ $key }}, true)"
                                        class="btn btn-outline-success btn-sm">
                                        <i class="fa-solid fa-pen-to-square"></i>&nbsp;&nbsp;<span>Edit</span>
                                        </button>
                                    @endif
                                </div>
                                @endif

                            </td>

                            <input hidden type="text" name="type[]" class="form-control" placeholder="type"
                                value="{{ $val->type }}">

                            <td class="text-center">
                                @if ($val->isRelocated == true)
                                    @php
                                        $actualQty = \App\Models\Inventory::where('prdetail_id', $val->id)->first()
                                            ->actual_qty;
                                    @endphp
                                    <span class="badge bg-info mb-1">Item relokasi</span><br>
                                    <span>Stock : {{ $relocatedQty[$val->id] ?? 0 }}</span>
                                    @if (!empty($isQtyChecked[$val->id]))
                                        <input type="number" min="1" class="form-control" placeholder="qty"
                                            wire:model.defer="relocatedQty.{{ $val->id }}" required>
                                    @endif
                                    <div class="form-check">
                                        <input type="checkbox" wire:model="isQtyChecked.{{ $val->id }}"
                                            wire:change="toggleCheck({{ $val->id }})" class="form-check-input">
                                        <label class="form-check-label" for="exampleCheck1">Tambahan</label>
                                    </div>
                                @else
                                    @if ($purchaserequestdetail[$key]->reduce_qty != null)
                                        <input type="number" class="form-control" max="{{ $quantity[$key] }}"
                                            wire:click="unit_po_changed({{ $key }})" placeholder="qty"
                                            wire:model="purchaserequestdetail.{{ $key }}.reduce_qty" @if($val->is_bulk == 1)
                                                readonly
                                            @endif>
                                    @else
                                        <input type="number" class="form-control" max="{{ $quantity[$key] }}"
                                            wire:click="unit_po_changed({{ $key }})" placeholder="qty"
                                            wire:model="purchaserequestdetail.{{ $key }}.qty" @if($val->is_bulk == 1)
                                            readonly
                                        @endif>
                                    @endif
                                @endif
                            </td>

                            <td>
                                <input hidden type="text" name="notes[]" class="form-control" placeholder="notes"
                                    value="{{ $val->notes }}">
                                {{ $val->notes }}
                            </td>

                            @if(!$val->is_bulk)
                                
                            <td>
                                @if(!$val->is_bulk)
                                    
                                
                                @if ($val->isRelocated == true)
                                    @if (empty($isQtyChecked[$val->id]))
                                        @continue
                                    @endif
                                @endif
                                @if (count($brand_partner->where('item_id', $val->item_id)) == 0)
                                    <select name=""
                                        wire:model="purchaserequestdetail.{{ $key }}.supplier"
                                        class="form-select" disabled>
                                        <option value="">Tidak ada Pilihan</option>
                                @endif

                                @if (count($brand_partner->where('item_id', $val->item_id)) != 0)
                                    <select name=""
                                        wire:model="purchaserequestdetail.{{ $key }}.supplier"
                                        class="js-example-basic-single form-select"
                                        wire:change="purchaserequestdetail_supplier({{ $key }})">
                                        <option value="" disabled>Pilih Supplier</option>
                                @endif

                                @php
                                    $no = 0;
                                @endphp

                                @foreach ($brand_partner->where('item_id', $val->item_id)->unique('supplier_id') as $key1 => $test)
                                    <option value="{{ $test->id }}">
                                        @if ($test->supplier)
                                            {{ $test->supplier->name }}
                                            | {{ $test->supplier->term_of_payment }} |
                                            @if ($test->tax_status == 1)
                                                Include Tax |
                                            @endif
                                            @if ($test->tax_status == 0)
                                                Exclude Tax |
                                            @endif
                                            @if ($test->tax_status == 2)
                                                No PPN |
                                            @endif

                                            @if ($no == 0)
                                                (Termurah)
                                            @endif
                                        @endif

                                    </option>
                                    @php
                                        $no += 1;
                                    @endphp
                                @endforeach
                                </select>

                                <div x-data="{ open: false }" x-cloak>
                                    <button @click="open = true" type="button"
                                        wire:click="showmodalsp({{ $val->item_id }}, {{ $key }})"
                                        class="btn btn-success btn-sm mt-1">
                                        Tambah Price Baru
                                    </button>

                                    <div x-show="open">
                                        @include('components.modaladdprice')
                                    </div>
                                </div>

                                @error('purchaserequestdetail.{{ $key }}.supplier')
                                    <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                                @enderror

                                @isset($quotationsCounts[$val->item_id])
                                    @if ($quotationsCounts[$val->item_id] > 0)
                                        <div class="mt-1">
                                            <a href="{{ route('vendors.quotation', $val->item_id) }}" target="_blank">
                                                Lihat Quotation
                                            </a>
                                        </div>
                                    @endif
                                @endisset
                                @endif
                            </td>

                            <td>

                                <select class="form-select" wire:model="unit_po_selected.{{ $key }}"
                                    wire:change="unit_po_changed({{ $key }})">
                                    @if (isset($unit_po_selected[$key]) && !is_array($unit_po_selected[$key]))
                                        <option value="{{ $unit_po_selected[$key] }}">
                                            {{ DB::table('units')->where('id', $unit_po_selected[$key])->pluck('name')->first() }}
                                        </option>
                                    @elseif(isset($unit_po_selected[$key]) && is_array($unit_po_selected[$key]))
                                        @foreach ($unit_po_selected[$key] as $data)
                                            <option value="{{ $data['unit_id'] }}">
                                                {{ DB::table('units')->where('id', $data['unit_id'])->pluck('name')->first() }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                            </td>

                            <td>
                                <div class="form-group">
                                    <input style="display: none" type="text"
                                        wire:model="purchaserequestdetail.{{ $key }}.price" readonly
                                        class="form-control" placeholder="Price">

                                    @if ($purchaserequestdetail[$key]['price'])
                                        <input type="text"
                                            value="{{ number_format($purchaserequestdetail[$key]['price'], 0, ',', '.') }}"
                                            readonly class="form-control" placeholder="Price">
                                    @else
                                        <input type="text" value="" readonly class="form-control"
                                            placeholder="Price">
                                    @endif
                                    @error('purchaserequestdetail.*.price')
                                        <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </td>

                            <td>
                                <div class="form-group">
                                    <input style="display: none" type="text"
                                        wire:model="purchaserequestdetail.{{ $key }}.tax" readonly
                                        class="form-control" placeholder="Tax">
                                    @if ($purchaserequestdetail[$key]['tax_status'] === 0)
                                        <input type="text" readonly name="tax[]" class="form-control"
                                            value="Exclude Tax" placeholder="Tax">
                                    @elseif($purchaserequestdetail[$key]['tax_status'] == 1)
                                        <input type="text" readonly name="tax[]" class="form-control"
                                            value="Include Tax" placeholder="Tax">
                                    @elseif($purchaserequestdetail[$key]['tax_status'] == 2)
                                        <input type="text" readonly name="tax[]" class="form-control"
                                            value="Non PPN" placeholder="Tax">
                                    @else
                                        <input type="text" readonly name="tax[]" class="form-control"
                                            value="" placeholder="Tax">
                                    @endif

                                    @error('purchaserequestdetail.{{ $key }}.tax')
                                        <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </td>

                            <td>
                                <div class="form-group">
                                    <input style="display: none" type="text"
                                        wire:model="purchaserequestdetail.{{ $key }}.jumlah" readonly
                                        name="amount[]" class="form-control" placeholder="Amount">
                                    @if ($purchaserequestdetail[$key]['jumlah'])
                                        <input type="text"
                                            value="{{ number_format($purchaserequestdetail[$key]['jumlah'], 0, ',', '.') }}"
                                            readonly name="amount[]" class="form-control" placeholder="Amount">
                                    @else
                                        <input type="text" value="" readonly name="amount[]"
                                            class="form-control" placeholder="Amount">
                                    @endif
                                    @error('purchaserequestdetail.{{ $key }}.jumlah')
                                        <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </td>

                            <td>
                                <input type="text" wire:model="purchaserequestdetail.{{ $key }}.payment"
                                    readonly name="amount[]" class="form-control" placeholder="Payment">
                                @error('amount')
                                    <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                                @enderror
                            </td>

                            <td>
                                <select class="form-select"
                                    wire:model="purchaserequestdetail.{{ $key }}.diantar"
                                    aria-label="Default select example">
                                    <option selected value="0">Dijemput</option>
                                    <option value="1">Diantar Expedisi</option>
                                    <option value="2">Diantar Dari Toko</option>
                                </select>
                            </td>
                            @endif
                            {{-- <td>
                            <button wire:click="reject({{ $key }},{{ $val->item_id }})"
                                class="btn btn-danger">Pending</button>
                        </td> --}}
                        </tr>
                    @endforeach
                </table>



                <div class="p-2">
                    <div class="mb-3">
                        <label for="exampleFormControlInput1" class="form-label">Warehouse</label>
                        <select class="form-select" wire:model="warehousemodel" aria-label="Default select example">
                            <option selected>Select Warehouse</option>
                            @foreach ($warehouse as $ware)
                                <option value="{{ $ware->id }}">{{ $ware->name }}</option>
                            @endforeach
                            @if(count($warehouse) > 2)
                                <option value="0">Project</option>
                            @endif
                        </select>
                        @if ($errors->has('warehousemodel'))
                            <span class="text-danger">Warehouse field is required</span>
                        @endif
                    </div>

                    <div class="mb-3">
                        <label>PO Type</label>
                        <select class="form-select" wire:model="potypemodel" aria-label="Default select example">
                            <option selected>Select PO Type</option>
                            <option value="Supply">Persediaan</option>
                            <option value="Non supply">Non Persediaan</option>
                        </select>
                        @if ($errors->has('potypemodel'))
                            <span class="text-danger">PO Type field is required</span>
                        @endif
                    </div>
                </div>
            </div>
            <div>
                @if (
                    $disablesave ||
                        ($warehousemodel == null ||
                            $warehousemodel == 'Select Warehouse' ||
                            $potypemodel == null ||
                            $potypemodel == 'Select PO Type'))
                    <button class="btn btn-secondary mb-4" disabled>Save</button>
                @else
                    <button onclick="action()" id="btnsavepo" class="btn btn-success mb-4">Save</button>
                @endif
                <script>
                    function action() {
                        let btn = document.getElementById("btnsavepo")
                        btn.disabled = true;
                        Livewire.emit("savedataemit")
                    }
                </script>
                <a class="btn btn-danger mb-4" wire:click="cancel_po">
                    Cancel
                </a>
            </div>
        </div>
    </div>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css"
        integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <style>
        .button-edit {
            font-size: 9px;
        }
    </style>
</div>
