<div>
    <div class="d-flex justify-content-between">
        <div class="d-flex">
            <a href="{{ route('payment-submission.voucher.index', $submission->id) }}"
               class="btn btn-sm btn-danger my-auto">
                <i class="fa-solid fa-angle-left"></i>
            </a>
            <h2 class="my-auto">Voucher NO: {{ $voucher->voucher_no }}</h2>
        </div>
    </div>
    <hr>
    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-dismissible fade show mb-2" role="alert">
            {{ $message }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    <div class="d-flex justify-content-end align-items-center gap-3 mb-3">
        <div class="d-flex">
            <button class="btn btn-primary" wire:click.prevent="update({{ $voucher->id }})"
                    wire:loading.attr="disabled">
                <span wire:loading.remove>Simpan</span>
                <span wire:loading>...</span>
            </button>
        </div>
    </div>
    <div class="mt-3 card">
        <div class="card-body">
            <h5>Additional Payment</h5>
            <table class="table table-bordered table-responsive">
                <thead class="border table-secondary">
                <tr class="">
                    <th class="align-middle">#</th>
                    <th class="align-middle" style="width: 5%;">Sudah Diketahui Direksi</th>
                    <th class="align-middle" style="width: 30%;">Faktur Pajak</th>
                    <th class="align-middle">Keterangan</th>
                    <th class="align-middle">Tanggal PO Diterbitkan</th>
                    <th class="align-middle" style="width: 15%;">No Rekening dan Nama Penerima</th>
                    <th class="align-middle">Project</th>
                    <th class="align-middle">Nama Item</th>
                    <th class="align-middle" style="width: 10%;">Pemohon & Penerima</th>
                    <th class="align-middle">Total Harga</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($additionalField as $key => $data)
                    <tr class="align-middle text-center">
                        <td>
                            <button class="btn btn-sm btn-outline-danger"
                                    wire:click.prevent="removeField({{ $key }})">
                                <i class="fa fa-trash"></i>
                            </button>
                        </td>
                        <td>
                            <input type="checkbox"
                                   wire:model="additionalField.{{ $key }}.is_confirm" {{ $data['is_confirm'] ? 'checked' : '' }}>
                        </td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="flexRadioDefault{{ $key }}"
                                       id="flexRadioDefault{{ $key }}" value="1"
                                       wire:model="additionalField.{{ $key }}.faktur_pajak">
                                <label class="form-check-label" for="flexRadioDefault{{ $key }}">
                                    Ada
                                </label>
                            </div>

                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="flexRadioDefault{{ $key }}"
                                       id="flexRadioDefault{{ $key }}" value="2"
                                       wire:model="additionalField.{{ $key }}.faktur_pajak">
                                <label class="form-check-label" for="flexRadioDefault{{ $key }}">
                                    Tidak Ada
                                </label>
                            </div>

                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="flexRadioDefault{{ $key }}"
                                       id="flexRadioDefault{{ $key }}" value="3"
                                       wire:model="additionalField.{{ $key }}.faktur_pajak">
                                <label class="form-check-label" for="flexRadioDefault{{ $key }}">
                                    Belum Ada
                                </label>
                            </div>
                        </td>
                        <td>
                            <input type="text" wire:model="additionalField.{{ $key }}.keterangan"
                                   value="{{ $data['keterangan'] }}">
                        </td>
                        <td>
                            {{ date('d F Y', strtotime($voucher->created_at)) }}
                        </td>
                        <td>
                            <span>Contoh Format: BCA xxxxxxx</span>
                            <input type="text" wire:model="additionalField.{{ $key }}.no_rekening"/>
                            <input type="text" class="mt-2" wire:model="additionalField.{{ $key }}.bank_penerima"
                                   value="{{ $data['bank_penerima'] }}">
                        </td>
                        <td>
                            <input type="text" wire:model="additionalField.{{ $key }}.project"
                                   value="{{ $data['project'] }}">
                        </td>
                        <td>
                            <input type="text" wire:model="additionalField.{{ $key }}.nama_item"
                                   value="{{ $data['nama_item'] }}">
                        </td>
                        <td>
                            <input type="text"
                                   wire:model="additionalField.{{ $key }}.peminta_penerima"
                                   value="{{ $data['peminta_penerima'] }}">
                        </td>
                        <td>
                            <input type="text" wire:model="additionalField.{{ $key }}.total"
                                   value="{{ $data['total'] }}">
                        </td>
                    </tr>
                @endforeach
                @foreach ($moreAdditionalField as $index => $data)
                    <tr>
                        <td>
                            <button class="btn btn-sm btn-outline-danger"
                                    wire:click.prevent="moreRemoveField({{ $index }})">
                                <i class="fa fa-trash"></i>
                            </button>
                        </td>
                        <td class="align-middle text-center">
                            <input type="checkbox" wire:model="moreAdditionalField.{{ $index }}.is_confirm">
                            @error('moreAdditionalField.' . $index . '.is_confirm') <span
                                class="text-danger">{{ $message }}</span> @enderror
                        </td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" value="1"
                                       wire:model="moreAdditionalField.{{ $index }}.faktur_pajak">
                                <label class="form-check-label" for="moreFlexRadioDefault{{ $index }}">
                                    Ada
                                </label>
                            </div>

                            <div class="form-check">
                                <input class="form-check-input" type="radio" value="2"
                                       wire:model="moreAdditionalField.{{ $index }}.faktur_pajak">
                                <label class="form-check-label" for="moreFlexRadioDefault{{ $index }}">
                                    Tidak Ada
                                </label>
                            </div>

                            <div class="form-check">
                                <input class="form-check-input" type="radio" value="3"
                                       wire:model="moreAdditionalField.{{ $index }}.faktur_pajak">
                                <label class="form-check-label" for="moreFlexRadioDefault{{ $index }}">
                                    Belum Ada
                                </label>
                            </div>
                            @error('moreAdditionalField.' . $index . '.faktur_pajak') <span
                                class="text-danger">{{ $message }}</span> @enderror
                        </td>
                        <td>
                            <input type="text" wire:model="moreAdditionalField.{{ $index }}.keterangan">
                            @error('moreAdditionalField.' . $index . '.keterangan') <span
                                class="text-danger">{{ $message }}</span> @enderror
                        </td>
                        <td>
                            {{ date('d F Y', strtotime($voucher->created_at)) }}
                        </td>
                        <td>
                            <span>Contoh Format: BCA xxxxxxx</span>
                            <input type="text" wire:model="moreAdditionalField.{{ $index }}.no_rekening"
                                   placeholder="Masukkan No Rekening"/>
                            @error('moreAdditionalField.' . $index . '.no_rekening') <span
                                class="text-danger">{{ $message }}</span> @enderror
                            <input type="text" class="mt-2" wire:model="moreAdditionalField.{{ $index }}.bank_penerima">
                            @error('moreAdditionalField.' . $index . '.bank_penerima') <span
                                class="text-danger">{{ $message }}</span> @enderror
                        </td>
                        <td>
                            <input type="text" wire:model="moreAdditionalField.{{ $index }}.project">
                            @error('moreAdditionalField.' . $index . '.project') <span
                                class="text-danger">{{ $message }}</span> @enderror
                        </td>
                        <td>
                            <input type="text" wire:model="moreAdditionalField.{{ $index }}.nama_item">
                            @error('moreAdditionalField.' . $index . '.nama_item') <span
                                class="text-danger">{{ $message }}</span> @enderror
                        </td>
                        <td>
                            <input type="text" wire:model="moreAdditionalField.{{ $index }}.peminta_penerima">
                            @error('moreAdditionalField.' . $index . '.peminta_penerima') <span
                                class="text-danger">{{ $message }}</span> @enderror
                        </td>
                        <td>
                            <input type="text" wire:model="moreAdditionalField.{{ $index }}.total_amount">
                            @error('moreAdditionalField.' . $index . '.total_amount') <span
                                class="text-danger">{{ $message }}</span> @enderror
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <button class="btn btn-success" wire:click.prevent="moreAddField({{ $i }})">
                Tambah Pembayaran Lainnya
            </button>
        </div>
    </div>
</div>
