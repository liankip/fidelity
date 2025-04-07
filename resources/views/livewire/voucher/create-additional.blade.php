<div class="row justify-content-center">
    <style>
        select.form-select option:disabled {
            background-color: #9ca7af !important;
            color: #6c757d !important;
        }
    </style>
    @if ($message = Session::get('danger'))
        <div class="alert alert-danger">
            <p>{{ $message }}</p>
        </div>
    @endif
    <div>
        <a href="{{ route('payment-submission.voucher.index', $submission) }}" class="third-color-sne"> <i
                class="fa-solid fa-chevron-left fa-xs"></i>
            Back</a>
        <h2>
            Voucher {{ $nomorVoucher }}</h2>
        <hr>
    </div>

    <div class="d-flex justify-content-end align-items-center gap-3">
        <button class="btn btn-primary" wire:click="save" wire:loading.attr="disabled"
            {{ count($additionalField) === 0 ? 'disabled' : '' }}>
            <span wire:loading.remove>Simpan</span>
            <span wire:loading>...</span>
        </button>
    </div>
    <div class="mt-3 card">
        <div class="card-body">
            <table class="table table-bordered table-responsive">
                <thead class="table-secondary">
                    <tr class="text-center">
                        <th class="align-middle" style="width: 5%;">Action</th>
                        <th class="align-middle" style="width: 5%;">Sudah Diketahui Direksi</th>
                        <th class="align-middle" style="width: 30%;">Faktur Pajak</th>
                        <th class="align-middle" style="width: 20%;">Keterangan</th>
                        <th class="align-middle" style="width: 15%;">No Rekening dan Nama Penerima</th>
                        <th class="align-middle" style="width: 15%;">Project</th>
                        <th class="align-middle" style="width: 20%;">Nama Item</th>
                        <th class="align-middle" style="width: 10%;">Pemohon & Penerima</th>
                        <th class="align-middle" style="width: 15%;">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($showSelect)
                        <tr>
                            <td></td>
                            <td colspan="8">
                                <select class="form-select" wire:model="item" wire:change="selectOfficeExpenseItem">
                                    <option value="default">-- Select an Item --</option>
                                    @foreach ($officeExpenseItem as $d)
                                        <option value="{{ $d->id }}"
                                            @if (in_array($d->id, array_column($additionalField, 'id'))) disabled @endif>
                                            {{ $d->officeExpensePurchase->officeExpense->office }}
                                            {{ $d->officeExpensePurchase->purchase_name }} - {{ $d->notes }}
                                            ({{ rupiah_format($d->total_expense) }})
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                        </tr>
                    @endif

                    @foreach ($additionalField as $key => $value)
                        <tr class="align-middle text-center">
                            <td>
                                <button class="btn btn-sm btn-outline-danger"
                                    wire:click.prevent="removeField({{ $key }})">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </td>
                            <td>
                                <input type="checkbox" wire:model="additionalField.{{ $key }}.is_confirm">
                                @error('additionalField.' . $key . '.is_confirm')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </td>
                            <td>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio"
                                        name="flexRadioDefault{{ $key }}"
                                        id="flexRadioDefault{{ $key }}" value="1"
                                        wire:model="additionalField.{{ $key }}.faktur_pajak">
                                    <label class="form-check-label" for="flexRadioDefault{{ $key }}">
                                        Ada
                                    </label>
                                </div>

                                <div class="form-check">
                                    <input class="form-check-input" type="radio"
                                        name="flexRadioDefault{{ $key }}"
                                        id="flexRadioDefault{{ $key }}" value="2"
                                        wire:model="additionalField.{{ $key }}.faktur_pajak">
                                    <label class="form-check-label" for="flexRadioDefault{{ $key }}">
                                        Tidak Ada
                                    </label>
                                </div>

                                <div class="form-check">
                                    <input class="form-check-input" type="radio"
                                        name="flexRadioDefault{{ $key }}"
                                        id="flexRadioDefault{{ $key }}" value="3"
                                        wire:model="additionalField.{{ $key }}.faktur_pajak">
                                    <label class="form-check-label" for="flexRadioDefault{{ $key }}">
                                        Belum Ada
                                    </label>
                                </div>
                                @error('additionalField.' . $key . '.faktur_pajak')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </td>
                            <td>
                                <textarea type="text" wire:model="additionalField.{{ $key }}.keterangan" placeholder="Masukkan Keterangan"></textarea>
                                @error('additionalField.' . $key . '.keterangan')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </td>
                            <td>
                                <input type="text" wire:model="additionalField.{{ $key }}.no_rekening"
                                    placeholder="Format: BCA 864xxxxx" />
                                @error('additionalField.' . $key . '.no_rekening')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                                <input type="text" class=" mt-2"
                                    wire:model="additionalField.{{ $key }}.bank_penerima"
                                    placeholder="Masukkan Nama Penerima" />
                                @error('additionalField.' . $key . '.bank_penerima')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </td>
                            <td>
                                <input type="text" wire:model="additionalField.{{ $key }}.project"
                                    placeholder="Masukkan Project" />
                                @error('additionalField.' . $key . '.project')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </td>
                            <td>
                                <input type="text" wire:model="additionalField.{{ $key }}.nama_item"
                                    placeholder="Masukkan Nama Item" />
                                @error('additionalField.' . $key . '.nama_item')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </td>
                            <td>
                                <input type="text" wire:model="additionalField.{{ $key }}.peminta_penerima"
                                    placeholder="Masukkan Pemohon dan Penerima" />
                                @error('additionalField.' . $key . '.peminta_penerima')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </td>
                            <td>
                                <input type="text" wire:model="additionalField.{{ $key }}.total_amount"
                                    placeholder="Masukkan Total" />
                                @error('additionalField.' . $key . '.total_amount')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <button class="btn btn-success" wire:click="addField({{ $i }})">
                Tambah Pembayaran Lainnya
            </button>
        </div>
    </div>
</div>
