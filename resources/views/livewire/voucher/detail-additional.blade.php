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
    <div class="card">
        <div class="card-body" style="overflow-x: scroll;">
            <table class="table table-bordered text-sm">
                <thead class="border table-secondary">
                <tr class="">
                    <th class="align-middle">No</th>
                    <th class="align-middle">Sudah Diketahui Direksi</th>
                    <th class="align-middle">Faktur Pajak</th>
                    <th class="align-middle">Keterangan</th>
                    <th class="align-middle">Tanggal PO Diterbitkan</th>
                    <th class="align-middle">No Rekening dan Nama Penerima</th>
                    <th class="align-middle">Project</th>
                    <th class="align-middle">Nama Item</th>
                    <th class="align-middle">Pemohon & Penerima</th>
                    <th class="align-middle">Total Harga</th>
                </tr>
                </thead>
                <tbody>
                @if (isset($voucher->additional_informations))
                    @foreach (json_decode($voucher->additional_informations, true) as $index => $data)
                        <tr class="align-middle text-center">
                            <td>
                                {{ $index + 1 }}
                            </td>
                            <td>
                                <i @if ($data['is_confirm']) class="fas fa-check-square text-success fs-5" @endif></i>
                            </td>
                            <td>
                                @if ($data['faktur_pajak'] == 1)
                                    <span class="badge bg-success">Ada</span>
                                @elseif($data['faktur_pajak'] == 2)
                                    <span class="badge bg-danger">Tidak Ada</span>
                                @elseif($data['faktur_pajak'] == 3)
                                    <span class="badge bg-secondary">Belum Ada</span>
                                @endif
                            </td>
                            <td>
                                {{ $data['keterangan'] }}
                            </td>
                            <td>
                                {{ date('d F Y', strtotime($voucher->created_at)) }}
                            </td>
                            <td>
                                No Rekening: {{ $data['no_rekening'] }} <br>
                                Nama Penerima: {{ $data['bank_penerima'] }}
                            </td>
                            <td>
                                {{ $data['project'] }}
                            </td>
                            <td>
                                {{ $data['nama_item'] }}
                            </td>
                            <td>
                                {{ $data['peminta_penerima'] }}
                            </td>
                            <td>
                                {{ rupiah_format($data['total']) }}
                            </td>
                        </tr>
                    @endforeach
                @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
