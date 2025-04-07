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
               @if (count($boqItems) == 0)
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

                         @foreach ($boqItems as $key => $val)
                              <tr wire:key="{{ $key }}">
                                   <td>
                                        <input hidden type="text" name="item_id[]" class="form-control"
                                             placeholder="item_id" value="{{ $val->item_id ?? $val->id }}">
                                        {{ $key + 1 }}
                                   </td>
                                   <td>
                                         {{ $val->item->name ?? $val->name }}
                                   </td>
                                   <td class="text-center">
                                        <input type="text" class="form-control" wire:model="boqItems.{{ $key }}.new_item_name" placeholder="Product Description">
                                    </td>

                                   <input hidden type="text" name="type[]" class="form-control" placeholder="type"
                                        value="{{ $val->type ?? null }}">

                                   <td class="text-center">
                                        @if($val->is_stock || $val->is_raw_materials)
                                        <input type="number" class="form-control" wire:model="quantity.{{ $val->id }}" min="1">
                                        @else
                                        {{ $val->qty }}
                                        @endif
                                   </td>

                                   <td>
                                        <input hidden type="text" name="notes[]" class="form-control"
                                             placeholder="notes" value="{{ $val->notes }}">
                                        {{ $val->notes ?? null }}
                                   </td>
                                   <td>
                                        @if (count($brand_partner->where('item_id', $val->item_id)) == 0)
                                             <select name=""
                                                  wire:model="boqItems.{{ $key }}.supplier"
                                                  class="form-select" disabled>
                                                  <option value="">Tidak ada Pilihan</option>
                                        @endif

                                        @if (count($brand_partner->where('item_id', $val->item_id)) != 0)
                                             <select name=""
                                                  wire:model="boqItems.{{ $key }}.supplier"
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

                                        @error('boqItems.{{ $key }}.supplier')
                                             <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                                        @enderror

                                        @isset($quotationsCounts[$val->item_id])
                                             @if ($quotationsCounts[$val->item_id] > 0)
                                                  <div class="mt-1">
                                                       <a href="{{ route('vendors.quotation', $val->item_id) }}"
                                                            target="_blank">
                                                            Lihat Quotation
                                                       </a>
                                                  </div>
                                             @endif
                                        @endisset
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
                                                  wire:model="boqItems.{{ $key }}.price"
                                                  readonly class="form-control" placeholder="Price">

                                             @if ($boqItems[$key]['price'])
                                                  <input type="text"
                                                       value="{{ number_format($boqItems[$key]['price'], 0, ',', '.') }}"
                                                       readonly class="form-control" placeholder="Price">
                                             @else
                                                  <input type="text" value="" readonly class="form-control"
                                                       placeholder="Price">
                                             @endif
                                             @error('boqItems.*.price')
                                                  <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                                             @enderror
                                        </div>
                                   </td>

                                   <td>
                                        <div class="form-group">
                                             <input style="display: none" type="text"
                                                  wire:model="boqItems.{{ $key }}.tax" readonly
                                                  class="form-control" placeholder="Tax">
                                             @if ($boqItems[$key]['tax_status'] === 0)
                                                  <input type="text" readonly name="tax[]" class="form-control"
                                                       value="Exclude Tax" placeholder="Tax">
                                             @elseif($boqItems[$key]['tax_status'] == 1)
                                                  <input type="text" readonly name="tax[]" class="form-control"
                                                       value="Include Tax" placeholder="Tax">
                                             @elseif($boqItems[$key]['tax_status'] == 2)
                                                  <input type="text" readonly name="tax[]" class="form-control"
                                                       value="Non PPN" placeholder="Tax">
                                             @else
                                                  <input type="text" readonly name="tax[]" class="form-control"
                                                       value="" placeholder="Tax">
                                             @endif

                                             @error('boqItems.{{ $key }}.tax')
                                                  <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                                             @enderror
                                        </div>
                                   </td>

                                   <td>
                                        <div class="form-group">
                                             <input style="display: none" type="text"
                                                  wire:model="boqItems.{{ $key }}.jumlah"
                                                  readonly name="amount[]" class="form-control" placeholder="Amount">
                                             @if ($boqItems[$key]['jumlah'])
                                                  <input type="text"
                                                       value="{{ number_format($boqItems[$key]['jumlah'], 0, ',', '.') }}"
                                                       readonly name="amount[]" class="form-control"
                                                       placeholder="Amount">
                                             @else
                                                  <input type="text" value="" readonly name="amount[]"
                                                       class="form-control" placeholder="Amount">
                                             @endif
                                             @error('boqItems.{{ $key }}.jumlah')
                                                  <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                                             @enderror
                                        </div>
                                   </td>

                                   <td>
                                        <input type="text"
                                             wire:model="boqItems.{{ $key }}.payment" readonly
                                             name="amount[]" class="form-control" placeholder="Payment">
                                        @error('amount')
                                             <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                                        @enderror
                                   </td>

                                   <td>
                                        <select class="form-select"
                                             wire:model="boqItems.{{ $key }}.diantar"
                                             aria-label="Default select example">
                                             <option selected value="0">Dijemput</option>
                                             <option value="1">Diantar Expedisi</option>
                                             <option value="2">Diantar Dari Toko</option>
                                        </select>
                                   </td>

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
                              <select class="form-select" wire:model="warehousemodel"
                                   aria-label="Default select example">
                                   <option selected>Select Warehouse</option>
                                   @foreach ($warehouse as $ware)
                                        <option value="{{ $ware->id }}">{{ $ware->name }}</option>
                                   @endforeach
                                   @if($projectId !== null)
                                        <option value="0">Project</option>
                                   @endif
                              </select>
                              @if ($errors->has('warehousemodel'))
                                   <span class="text-danger">Warehouse field is required</span>
                              @endif
                         </div>

                         <div class="mb-3">
                              <label>PO Type</label>
                              <select class="form-select" wire:model="potypemodel"
                                   aria-label="Default select example">
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
