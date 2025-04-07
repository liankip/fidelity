<div wire:click="closeshowai" class="bg-dark opacity-25"
    style="height: 100%;width: 100%;left: 0;top: 0;overflow: hidden;position: fixed;"></div>

<div class="modal d-block" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Di antar</h3>
                <button type="button" class="btn-close" wire:click="closemodalpengirman" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                @if ($po->deliver_status == 1)

                    <div class="form-group">
                        <strong>Jasa Pengiriman:</strong>
                        <select required name="ds_id" wire:model='jasa_pengiriman'
                            class="js-example-basic-single form-control">
                            <option value="">Pilih Jasa Pengiriman</option>
                            @foreach ($ds as $val_ds)
                                <option value="{{ $val_ds->id }}">
                                    {{ $val_ds->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('jasa_pengiriman')
                            <div class="text-danger mt-2"> {{ $message }}
                            </div>
                        @enderror

                    </div>
                @elseif ($po->deliver_status == 2)
                    <div class="form-group">
                        <strong>Ongkos kirim:</strong>
                        <input required type="number" wire:model='ongkir' name="tarif_ds" class="form-control"
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
    </div>
</div>
