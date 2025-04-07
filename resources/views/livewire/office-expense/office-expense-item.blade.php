<div class="mt-2">
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <a href="{{ route('office-expense.purchase', $office->id) }}" class="third-color-sne"> <i
                        class="fa-solid fa-chevron-left fa-xs"></i> Back</a>
                <h2 class="primary-color-sne">Office Expense > {{ $office->office }} > {{ $purchase->purchase_name }}
                </h2>
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

            <div class="pull-right mb-2 mt-2 d-flex justify-content-between">
                <div class="d-flex gap-3">
                    <div class="mr-2">
                        <a class="btn btn-success"
                            href="{{ route('office-expense.item.insert', ['office' => $office->id, 'purchase' => $purchase->id]) }}">
                            <i class="fas fa-plus"></i>
                            Create Office Expense Item
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card primary-box-shadow mt-3">
        <div class="card-body">
            <table class="table table-bordered table-hover">
                <thead class="thead-light">
                    <tr class="table-secondary">
                        <th class="text-center align-items-center border-top-left">No</th>
                        <th class="text-center">Tanggal Pengeluaran</th>
                        <th class="text-center">Kantor</th>
                        <th class="text-center">Nama Pembelian</th>
                        <th class="text-center">Total Pengeluaran</th>
                        <th class="text-center">No Rekening dan Nama Penerima</th>
                        <th class="text-center">Notes</th>
                        <th class="text-center">Status</th>
                        <th class="text-center not-export border-top-right">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($data as $index => $d)
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td class="text-center">{{ \Carbon\Carbon::parse($d->purchase_date)->format('d/m/Y') }}</td>
                            <td class="text-center">{{ $office->office }}</td>
                            <td class="text-center">{{ $purchase->purchase_name }}</td>
                            <td class="text-center">{{ rupiah_format($d->total_expense) }}</td>
                            <td class="text-center">
                                {{ $d->vendor }} {{ $d->account_number }} <br>
                                Nama Penerima: {{ $d->receiver_name ?? '-' }}
                            </td>
                            <td class="text-center">{{ $d->notes }}</td>
                            <td class="text-center">
                                @if ($d->status == 'approved')
                                    <span class="badge-custom badge-approved">Approved</span>
                                @elseif($d->status == 'pending')
                                    <span class="badge-custom badge-pending">Pending</span>
                                @else
                                    <span class="badge-custom badge-rejected">rejected</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if ($d->status == 'approved')
                                    <a href="{{ route('office-expense.item.edit', ['office' => $office->id, 'purchase' => $purchase->id, 'id' => $d->id]) }}"
                                        class="btn btn-outline-primary">Edit</a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">No Data Found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="mt-4 d-flex justify-content-end">
                {{ $data->links() }}
            </div>
        </div>
    </div>
</div>
