<div class="container mt-2">
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <a href="{{ url('purchase-orders') }}" class="third-color-sne"> <i
                        class="fa-solid fa-chevron-left fa-xs"></i> Back</a>
                <h2 class="primary-color-sne mt-3">{{ config('app.company', 'SNE') }} - ERP | Edit Purchase Order</h2>
            </div>
        </div>
    </div>

    <livewire:common.alert />

    @csrf
    <div class="card primary-box-shadow mt-5">
        <div class="card-header">
            {{ $po->pr_no }} | {{ $po->supplier->name }}
        </div>
        <div class="card-body">
            <table class="table primary-box-shadow">
                <thead class="thead-light">
                    <th class="border-top-left">No</th>
                    <th>Item Name</th>
                    <th>Quantity</th>
                    <th>Unit</th>
                    <th>Notes</th>
                    <th style="min-width: 12%">Harga</th>
                    <th>Tax</th>
                    <th>Jumlah</th>
                    <th>Payment Method</th>
                    <th>Di Antar</th>
                    <th class="border-top-right"></th>
                </thead>
                @foreach ($po->podetail as $key => $val)
                    <tr>
                        <td>
                            {{ $key + 1 }}
                        </td>
                        <td>
                            {{ $val->prdetail ? $val->prdetail->item_name : $val->item->name }}
                        </td>
                        <input hidden type="text" name="type[]" class="form-control" placeholder="type"
                            value="{{ $val->type }}">

                        <td>
                            {{ $val->qty }}
                        </td>
                        <td>
                            {{ $val->prdetail ? $val->prdetail->unit : $val->unit }}
                        </td>

                        <td>
                            {{ $val->notes }}
                        </td>

                        <td>
                            <input class="form-control" type="number" min="1"
                                wire:model="arraypodetail.{{ $key }}.price" value="{{ $val->price }}"
                                aria-label=".form-control-lg example">
                        </td>
                        <td>
                            @if ($val->tax_status == 0)
                                Excl. PPN
                            @elseif ($val->tax_status == 1)
                                Incl. PPN
                            @else
                                Non PPN
                            @endif
                        </td>
                        <td>
                            {{ number_format($arraypodetail[$key]['amount'], 0, ',', '.') }}
                        </td>
                        <td>
                            {{ $po->term_of_payment }}
                        </td>
                        <td>
                            <select class="form-select" wire:model="arraypodetail.{{ $key }}.deliver_status"
                                aria-label="Default select example">
                                <option selected value="0">Dijemput</option>
                                <option value="1">Diantar expedisi</option>
                                <option value="2">Diantar dari toko</option>
                            </select>
                        </td>
                        <td>
                            <button class="btn btn-danger" data-bs-toggle="modal"
                                data-bs-target="#deleteItemPurchaseOrder-{{ $val->id }}">
                                <i class="fa-solid fa-trash"></i>
                            </button>

                            <x-common.modal id="deleteItemPurchaseOrder-{{ $val->id }}"
                                title="Delete Item {{ $val->prdetail ? $val->prdetail->item_name : $val->item->name }}">
                                <x-slot:modal-body>
                                    <p>Are you sure you want to delete this item?</p>
                                    <x-common.modal.button-cancel />
                                    <button type="submit" wire:loading.attr="disabled"
                                        wire:click="delete('{{ $val->id }},{{ $val->prdetail->pr_id }}')"
                                        class="btn btn-danger">Delete</button>
                                </x-slot:modal-body>
                                <x-slot:modal-footer>
                                </x-slot:modal-footer>
                            </x-common.modal>
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
            <button wire:click="savedata" class="btn btn-success">Save</button>
        </div>
    </div>
</div>
