<div class="mt-2">
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2 class="primary-color-sne">Capex Expense</h2>
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
                        <a class="btn btn-success" href="{{ route('capex-expense.insert') }}">
                            <i class="fas fa-plus"></i>
                            Create Capex Expense
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
                        <th class="text-center">Project</th>
                        <th class="text-center">ROI</th>
                        <th class="text-center">Total Budget</th>
                        <th class="text-center">Total Expense</th>
                        <th class="text-center not-export border-top-right">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($data as $index => $d)
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td class="text-center">{{ $d->name }}</td>
                            <td class="text-center">{{ $d->roi ?? '-' }}</td>
                            <td class="text-center">{{ rupiah_format($d->value) }}</td>
                            <td class="text-center">
                                @php
                                    $total = 0;
                                    $grandTotalBOQ = 0;
                                @endphp
                                @foreach ($d->boqs as $boq)
                                    @if ($boq->approved_by_3 !== null)
                                        @php
                                            $subtotal = $boq->qty * $boq->price_estimation;
                                            $total += $subtotal;
                                            $grandTotalBOQ += $total;
                                        @endphp
                                    @endif
                                @endforeach
                                {{ rupiah_format($total) }}
                            </td>
                            <td>
                                <div style="text-align: center; display: flex; justify-content: center; gap: 5px;">
                                    <a href="{{ route('capex-expense.edit', $d->id) }}"
                                        class="text-center btn btn-outline-primary">Edit</a>
                                    <a href="{{ route('capex-expense.boq', parameters: $d->id) }}"
                                        class="text-center btn btn-outline-primary">Detail</a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">No Data Found</td>
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
