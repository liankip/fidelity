@php use Carbon\Carbon; @endphp
<div>
    <h2 class="primary-color-sne">Payment Submission Waiting Approval</h2>
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <p>{{ session('success') }}</p>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card primary-box-shadow mt-5">
        <div class="card-body" style="overflow-x: scroll;">
            <table class="table primary-box-shadow">
                <thead class="thead-light">
                    <tr class="table-secondary">
                        <th class="border-top-left">No</th>
                        <th>Pengajuan</th>
                        <th>Tipe</th>
                        <th>Total</th>
                        <th class="border-top-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($paymentSubmissionData as $key => $data)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>Pengajuan Pembayaran {{ $data->type }}
                                Tanggal {{ Carbon::parse($data->created_at)->isoFormat(' D MMMM Y') }}</td>
                            <td>{{ $data->type }}</td>
                            <td>{{ rupiah_format($data->totalSum) }}</td>
                            <td>
                                <a class="btn btn-success btn-sm"
                                    href="{{ route('payment-submission-approval.detail', ['paramId' => $data->id]) }}">Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">No Data</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
