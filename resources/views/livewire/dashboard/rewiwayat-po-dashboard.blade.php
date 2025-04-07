<div class="col-md-6 col-lg-4 col-xl-4 order-0 mb-4 mt-4">
    <div class="card h-100  shadow-sm">
        <div class="bg-primary card-header d-flex align-items-center justify-content-between">
            <div class="card-title mb-0">
                <h5 class="m-0 me-2 fw-normal text-white">Purchase Order History</h5>
                <small class="text-white">Last 10 Purchase Orders</small>
            </div>
            <div>
                <a href="" wire:click='refresh'>
                    <i class="fa-solid fa-rotate text-white"></i>
                </a>
            </div>
        </div>

        <div class="card-body">
            <div>
                @php
                    $total_amount_po = 0;
                @endphp
                @foreach ($po as $pod)
                    <div class="row gap-3 mt-3">
                        <div class="col-12">
                            <a class="text-black text-decoration-none"
                                href="{{ route('po-detail', ['id' => $pod->id]) }}"><strong>{{ $pod->pr->project->name }}</strong></a>
                            <div>

                            </div>
                        </div>
                        <div class="col-12">
                            @if ($pod->status == 'Paid')
                                <span class="badge bg-success">{{ $pod->status }}</span>
                            @elseif ($pod->status == 'Partially Paid')
                                <span class="badge bg-primary">{{ $pod->status }}</span>
                            @elseif ($pod->status == 'Need to Pay')
                                <span class="badge bg-warning">{{ $pod->status }}</span>
                            @elseif ($pod->status == 'Approved')
                                <span class="badge bg-secondary">{{ $pod->status }}</span>
                            @endif
                        </div>
                        <div class="col-12">
                            <div class="fw-bold text-end">
                                <div class="">Rp
                                    @if ($pod->total_amount != 0)
                                        {{ str_replace(',00', '', number_format($amount = $pod->total_amount, 2, ',', '.')) }}
                                    @else
                                        @php
                                            $getamount = App\Helpers\GetAmount::get($pod);
                                        @endphp
                                        {{ str_replace(',00', '', number_format($amount = $getamount['total'], 2, ',', '.')) }}
                                    @endif
                                </div>
                            </div>

                            @php
                                $total_amount_po += $amount;
                            @endphp
                        </div>
                    </div>
                @endforeach
                <div class="text-end border-top mt-2 pt-2">
                    <b>Total: Rp {{ str_replace(',00', '', number_format($total_amount_po, 2, ',', '.')) }}</b>
                </div>
            </div>

        </div>
    </div>
</div>
