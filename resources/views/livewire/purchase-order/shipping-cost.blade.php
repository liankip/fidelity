<div>
    <div class="modal-header">
        <h5>Di antar</h5>
        <button type="button" class="btn-close" wire:click="$emitUp('closeModal')" aria-label="Close"></button>
    </div>
    <div class="modal-body">
        @if ($purchase_order->deliver_status == 1)
            <div class="form-group">
                <strong>Jasa Pengiriman:</strong>
                <select required name="ds_id" wire:model='delivery_service'
                        class="js-example-basic-single form-control">
                    <option value="">Pilih Jasa Pengiriman</option>
                    @foreach ($ds as $val_ds)
                        <option value="{{ $val_ds['id'] }}">
                            {{ $val_ds['name'] }}
                        </option>
                    @endforeach
                </select>
                @error('$delivery_service')
                <div class="text-danger mt-2"> {{ $message }}</div>
                @enderror

            </div>
        @elseif ($purchase_order->deliver_status == 2)
            <div class="form-group">
                <strong>Ongkos kirim:</strong>
                <input required type="number" wire:model='shipping_cost' name="tarif_ds" class="form-control"
                       placeholder="Total Biaya">
                {{-- <input required type="text" wire:model='ongkir' wire:blur="updateongkir({{$po->id}})" name="tarif_ds" class="form-control"
                    placeholder="Total Biaya"> --}}
                @error('ongkir')
                <div class="text-danger mt-2">{{ $message }}
                </div>
                @enderror
            </div>
        @endif

    </div>
</div>
