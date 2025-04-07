<style>
    [x-cloak] {
        display: none;
    }
</style>
<div class="bg-dark opacity-50" style="height: 100%;width: 100%;left: 0;top: 0;overflow: hidden;position: fixed;"></div>
<div class="modal d-block" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Add Price</h3>
                <button type="button" class="btn-close" wire:click="closeshowsp" aria-label="Close" @click="open = false"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <div>
                        <button class="btn btn-success btn-sm" wire:click='shomodalsupplier'>Tambah Supplier Baru</button>
                    </div>

                    <hr>

                    <strong for="exampleDataList" class="form-label">Cari Supplier<span class="text-danger">*</span></strong>
                    <input class="form-control mb-2" wire:model="supplieradd" list="datalistOptions"
                        id="exampleDataList" placeholder="Cari Supplier Disini">
                    <datalist id="datalistOptions">
                        @if ($showaddprice)
                            @foreach ($supplierall as $sup)
                                <option value="{{ $sup->id }}">
                                    {{ $sup->name }}
                                </option>
                            @endforeach
                        @endif
                    </datalist>

                    <select class="form-select font-monospace" wire:model="supplieradd" aria-label="Default select example">
                        <option selected>Select Supplier</option>
                        @php
                            $max_length = DB::table('suppliers')->max(DB::raw('LENGTH(name)'));
                        @endphp
                        @if ($showaddprice)
                            @foreach ($supplierall as $sup)
                                <option value="{{ $sup->id }}">
                                    @php
                                        $a = 0;
                                        $length = $max_length - strlen($sup->name);
                                    @endphp

                                    {{ $sup->name }}@for($a == 0; $a < $length; $a++)&nbsp;@endfor : {{ $sup->term_of_payment }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                    @error('supplieradd')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-3">
                    <strong class="form-label">Item<span class="text-danger">*</span></strong>
                    <select class="form-select" wire:model="itemid" readonly>
                        @if ($showaddprice)
                            @foreach ($itemall as $it)
                                @if ($it->id == $itemadd)
                                    <option value="{{ $it->id }}">
                                        {{ $it->name }}
                                    </option>
                                @endif
                            @endforeach
                        @endif
                    </select>
                    @error('itemid')
                        <span>{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-3">
                    <strong class="form-label">Unit<span class="text-danger">*</span></strong>
                    <select class="form-select" wire:model="unit_selected">
                        @if ($showaddprice)
                            @foreach ($unit as $data_item)
                                    <option value="{{ $data_item->unit_id }}" {{ $data_item->unit_id == $unit_selected ? 'selected' : '' }}>
                                        {{ $data_item->unit->name }}
                                    </option>
                            @endforeach
                        @endif
                    </select>
                    @error('itemid')
                        <span>{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-3">
                    <strong class="form-label">Price<span class="text-danger">*</span></strong>
                    <div class="input-group mb-3">
                        <span class="input-group-text">Rp</span>
                        <input type="text" wire:model="priceold" class="form-control" type-currency="IDR"
                            placeholder="Price in Rp">
                        <span class="input-group-text">,00</span>
                    </div>
                    @error('priceold')
                        <span>{{ $message }}</span>
                    @enderror
                </div>
                <div class="mb-3">
                    <strong class="form-label">Tax<span class="text-danger">*</span></strong>
                    <select class="form-select" wire:model="taxstatusadd" aria-label="Default select example">
                        <option value="1">Include Tax</option>
                        <option value="2">Exclude Tax</option>
                        <option value="3">Non PPN</option>
                    </select>
                    @error('taxstatusadd')
                        <span>{{ $message }}</span>
                    @enderror
                </div>
                <div class="mb-3">
                    <strong class="form-label">Price Result<span class="text-danger">*</span></strong>
                    <div class="input-group mb-3">
                        <span class="input-group-text">Rp</span>
                        <input type="text" wire:model="priceshow" readonly class="form-control"
                            placeholder="Price in Rp">
                        <span class="input-group-text">,00</span>
                    </div>
                    @error('priceshow')
                        <span>{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-3">
                    <div class="p-2 bg-secondary rounded">
                        <strong class="form-label text-white">Kurs Rupiah terhadap dolar saat ini</strong>

                        <div class="input-group mb-3">
                            <span class="input-group-text">Rp</span>
                            <input type="text" class="form-control" placeholder="Ex: 15000">
                        </div>

                        <div>
                            <div class="text-warning">Abaikan form ini jika price tidak terkait dengan kurs rupiah terhadap dolar</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button class="btn" wire:click="closeshowsp" @click="open = false">Cancel</button>
                <button class="btn btn-primary" wire:click="saveprice" wire:loading.attr="disabled">Save</button>
            </div>
            <script>
                document.querySelectorAll('input[type-currency="IDR"]').forEach((element) => {
                    element.addEventListener('keyup', function(e) {
                        let cursorPostion = this.selectionStart;
                        let value = parseInt(this.value.replace(/[^,\d]/g, ''));
                        let originalLenght = this.value.length;
                        if (isNaN(value)) {
                            this.value = "";
                        } else {
                            this.value = value.toLocaleString('id-ID', {
                                currency: 'IDR',
                                minimumFractionDigits: 0
                            });
                            cursorPostion = this.value.length - originalLenght + cursorPostion;
                            this.setSelectionRange(cursorPostion, cursorPostion);
                        }
                    });
                });
            </script>
        </div>
    </div>
</div>
