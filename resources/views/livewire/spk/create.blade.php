<div class="mt-2">
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>{{ config('app.company', 'SNE') }} - ERP | SPK Create</h2>
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
    <div class="card">
        @if (count($purchaserequestdetail) == 0)
            @csrf
            <a class="btn btn-success" href="">Tambah Barang</a>
        @endif

        <div class="card-header">
            {{ $purchaserequest->pr_no }} |
            <a class="btn btn-primary" href="{{ url('purchase_requests') }}">
                Back
            </a>
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <tr>
                    <th>No</th>
                    <th>Item Name</th>

                    <th>Quantity</th>
                    <th>Unit</th>
                    <th>Notes</th>
                    <th style="width: 15%">Supplier</th>
                    <th>Harga</th>
                    <th>Tax</th>
                    {{-- <th>Exclude Tax</th> --}}

                    <th>Jumlah</th>
                    <th style="width: 12%">Payment Method</th>
                    <th>Di Antar</th>
                    <th>Action</th>
                </tr>
                @foreach ($purchaserequestdetail as $key => $val)
                    {{-- @dd($key) --}}
                    <tr>

                        <td>
                            <input hidden type="text" name="item_id[]" class="form-control" placeholder="item_id"
                                value="{{ $val->item_id }}">
                            {{ $key + 1 }}
                        </td>
                        <td>

                            @if ($val->edit_name)
                                <div class="form-group">
                                    <input type="text" class="form-control" placeholder="type"
                                        wire:model.defer="purchaserequestdetail.{{ $key }}.item_name">
                                    @error('item_name')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <button type="button" wire:click="saveEditName({{ $key }})"
                                    class="btn btn-success btn-sm button-edit">
                                    <i class="fa-solid fa-pencil"></i> Simpan
                                </button>
                                <button type="button" wire:click="setEditName({{ $key }},false)"
                                    class="btn btn-danger btn-sm button-edit">
                                    <i class="fa-solid fa-xmark"></i> Batal
                                </button>
                            @else
                                {{ $val->item_name }}
                                <button type="button" wire:click="setEditName({{ $key }},true)"
                                    class="btn btn-success btn-sm button-edit">
                                    <i class="fa-solid fa-pencil"></i> Edit
                                </button>
                            @endif

                        </td>
                        <input hidden type="text" name="type[]" class="form-control" placeholder="type"
                            value="{{ $val->type }}">

                        {{-- <td>
                                <input hidden type="text" name="type[]" class="form-control" placeholder="type"
                                    value="{{ $val->type }}">
                                {{ $val->type }}
                            </td> --}}
                        <td class="text-center">
                            <input hidden type="text" name="qty[]" class="form-control" placeholder="qty"
                                value="{{ $val->qty }}">
                            {{ str_replace(',00', '', number_format($val->qty, 2, ',', '.')) }}
                        </td>
                        <td>
                            <input hidden type="text" name="unit[]" class="form-control" placeholder="unit"
                                value="{{ $val->unit }}">
                            {{ $val->unit }}
                        </td>

                        <td>
                            <input hidden type="text" name="notes[]" class="form-control" placeholder="notes"
                                value="{{ $val->notes }}">
                            {{ $val->notes }}
                        </td>
                        <td>
                            @if (count($brand_partner->where('item_id', $val->item_id)) == 0)
                                {{-- <a class="btn btn-success" target="_blank"
                                        href="{{ route('prices.create', ['item' => $val->item_id]) }}"> +</a> --}}
                                <select name="" wire:model="purchaserequestdetail.{{ $key }}.supplier"
                                    class="form-control" disabled>
                                    <option value="">Tidak ada Pilihan</option>
                            @endif

                            @if (count($brand_partner->where('item_id', $val->item_id)) != 0)
                                <select name="" wire:model="purchaserequestdetail.{{ $key }}.supplier"
                                    class="js-example-basic-single form-control">
                                    <option value="">Pilih Supplier</option>
                            @endif
                            @php
                                $no = 0;
                            @endphp
                            @foreach ($brand_partner->where('item_id', $val->item_id) as $key1 => $test)
                                <option value="{{ $test->id }}">
                                    {{ $test->supplier->name }} | {{ $test->supplier->term_of_payment }} |
                                    @if ($test->tax_status == 1)
                                        Incl. PPN |
                                    @endif
                                    @if ($test->tax_status == 0)
                                        Excl. PPN |
                                    @endif
                                    @if ($test->tax_status == 2)
                                        No PPN |
                                    @endif

                                    @if ($no == 0)
                                        (termurah)
                                    @endif
                                </option>
                                @php
                                    $no += 1;
                                @endphp
                            @endforeach
                            </select>

                            <!-- Button trigger modal -->
                            <button type="button" wire:click="showmodalsp({{ $val->item_id }})"
                                class="btn btn-success btn-sm">
                                Tambah price baru
                            </button>

                            <!-- Modal -->


                            @error('purchaserequestdetail.{{ $key }}.supplier')
                                <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                            @enderror
                        </td>
                        <td>
                            <div class="form-group">
                                {{-- <strong>Event Type Code:</strong> --}}
                                <input style="display: none" type="text"
                                    wire:model="purchaserequestdetail.{{ $key }}.price" readonly
                                    name="price[]" class="form-control" placeholder="price">
                                {{-- @dd($purchaserequestdetail[$key]['price']) --}}
                                @if ($purchaserequestdetail[$key]['price'])
                                    <input type="text"
                                        value="{{ number_format($purchaserequestdetail[$key]['price'], 0, ',', '.') }}"
                                        readonly name="price[]" class="form-control" placeholder="price">
                                @else
                                    <input type="text" value="" readonly name="price[]" class="form-control"
                                        placeholder="price">
                                @endif
                                @error('purchaserequestdetail.*.price')
                                    <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </td>
                        <td>
                            <div class="form-group">
                                {{-- <strong>Event Type Code:</strong> --}}
                                <input style="display: none" type="text"
                                    wire:model="purchaserequestdetail.{{ $key }}.tax" readonly name="tax[]"
                                    class="form-control" placeholder="tax">
                                @if ($purchaserequestdetail[$key]['tax_status'] == 0)
                                    <input type="text" readonly name="tax[]" class="form-control" value="Excl. tax"
                                        placeholder="tax">
                                @elseif($purchaserequestdetail[$key]['tax_status'] == 1)
                                    <input type="text" readonly name="tax[]" class="form-control"
                                        value="Incl. tax" placeholder="tax">
                                @elseif($purchaserequestdetail[$key]['tax_status'] == 2)
                                    <input type="text" readonly name="tax[]" class="form-control"
                                        value="Non PPN" placeholder="tax">
                                @else
                                    <input type="text" readonly name="tax[]" class="form-control"
                                        value="" placeholder="">
                                @endif

                                @error('purchaserequestdetail.{{ $key }}.tax')
                                    <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </td>

                        <td>
                            <div class="form-group">
                                {{-- <strong>Event Type Code:</strong> --}}
                                <input style="display: none" type="text"
                                    wire:model="purchaserequestdetail.{{ $key }}.jumlah" readonly
                                    name="amount[]" class="form-control" placeholder="amount">
                                @if ($purchaserequestdetail[$key]['jumlah'])
                                    <input type="text"
                                        value="{{ number_format($purchaserequestdetail[$key]['jumlah'], 0, ',', '.') }}"
                                        readonly name="amount[]" class="form-control" placeholder="amount">
                                @else
                                    <input type="text" value="" readonly name="amount[]"
                                        class="form-control" placeholder="amount">
                                @endif
                                @error('purchaserequestdetail.{{ $key }}.jumlah')
                                    <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </td>
                        {{-- @endforeach --}}
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
                                <option selected value="0">dijemput</option>
                                <option value="1">Diantar expedisi</option>
                                <option value="2">Diantar dari toko</option>
                            </select>
                        </td>
                        <td>
                            <button wire:click="reject({{ $key }},{{ $val->item_id }})"
                                class="btn btn-danger">Pending</button>
                        </td>
                    </tr>
                @endforeach

            </table>

            <div>
                <div class="mb-3">
                    <label for="exampleFormControlInput1" class="form-label">Warehouse</label>
                    <select class="form-select" wire:model="warehousemodel" aria-label="Default select example">
                        <option selected>Select Warehouse</option>
                        @foreach ($warehouse as $ware)
                            <option value="{{ $ware->id }}">{{ $ware->name }}</option>
                        @endforeach
                        <option value="0">Project</option>
                    </select>
                    @if ($errors->has('warehousemodel'))
                        <span class="text-danger">Warehouse field is required</span>
                    @endif
                </div>
            </div>
        </div>
        <div class="card-footer">
            @if ($disablesave || ($warehousemodel == null || $warehousemodel == 'Select Warehouse'))
                <button class="btn btn-secondary" disabled>Save</button>
            @else
                <button onclick="action()" id="btnsavepo" class="btn btn-success">Save</button>
            @endif
            <script>
                function action() {
                    let btn = document.getElementById("btnsavepo")
                    btn.disabled = true;

                    Livewire.emit("savedataemit")
                }
            </script>
        </div>

    </div>
    @if ($showaddprice)
        @include('components.modaladdprice')
    @endif
    @if ($showaddsp)
        @include('components.modaladdsupplier')
    @endif
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css"
        integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <style>
        .button-edit {
            font-size: 9px;
        }
    </style>
</div>
