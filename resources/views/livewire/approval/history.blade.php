<div class="mt-2">
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2 class="primary-color-sne">History Approval</h2>
            </div>
        </div>
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
    <div class="card mt-5 primary-box-shadow">
        <div class="card-body">
            <form action="" method="get" class="d-flex">
                <div class="input-group mb-3">
                    <input type="text" class="form-control" wire:model="search" name="search" placeholder="Search"
                        value="" aria-label="Recipient's username" aria-describedby="button-addon2">
                </div>
            </form>
            <table class="table primary-box-shadow">
                <tr class="thead-light">
                    <th style="text-align: center" class="align-middle border-top-left">No</th>
                    <th style="text-align: center" class="align-middle">PO/SPK No</th>
                    <th style="text-align: center" class="align-middle">Vendor</th>
                    <th style="text-align: center" class="align-middle">Project</th>
                    <th style="text-align: center" class="align-middle">Jumlah Item</th>
                    <th style="text-align: center" class="align-middle">Total Amount</th>
                    <th style="text-align: center" class="align-middle border-top-right">Status</th>
                </tr>
                @foreach ($purchase_requests as $key => $purchaserequest)
                    <tr>
                        <td class="text-center">{{ $key + 1 }}</td>
                        <td>
                            <a class="font-bold" href="{{ url('po_details', $purchaserequest->id) }}">
                                {{ $purchaserequest->po_no }}
                            </a>
                            <div class="mt-4 font-bold">
                                Tgl Request:
                            </div>
                            <em>
                                {{ $purchaserequest->created_at->format('d F Y, H:i') }}
                            </em>
                        </td>
                        <td>{{ $purchaserequest->supplier->name }}</td>
                        <td>
                            <div class="font-bold">
                                {{ $purchaserequest->project->name }}
                            </div>
                            <div class="font-bold mt-4">
                                Warehouse:
                            </div>
                            <div>
                                @if ($purchaserequest->warehouse)
                                    {{ $purchaserequest->warehouse->name }}
                                @else
                                    {{ $purchaserequest->project->name }}
                                @endif
                            </div>
                        </td>
                        <td class="text-center">{{ count($purchaserequest->podetail) }}</td>
                        <td align="right">
                            @php
                                $totalamount = 0;
                            @endphp
                            @foreach ($purchaserequest->podetail as $sajs)
                                @php
                                    $totalamount += $sajs->amount;
                                @endphp
                            @endforeach
                            <div class="d-flex justify-content-between">
                                <div>Rp.</div>
                                <div>{{ number_format($totalamount, 0, ',', '.') }}</div>
                            </div>
                        </td>
                        <td>
                            <div
                                class="badge badge-success text-center font-bold {{ $purchaserequest->status == 'Approved' ? 'text-success' : 'text-black' }}">
                                {{ $purchaserequest->status }}
                            </div>
                            <div class="mt-4 font-bold border p-2 rounded">
                                @if ($purchaserequest->approvedby)
                                    {{ $purchaserequest->approvedby->name }}
                                @else
                                    Unknown
                                @endif
                            </div>
                            <div class="mt-1 font-bold border p-2 rounded">
                                @if ($purchaserequest->approvedby2)
                                    {{ $purchaserequest->approvedby2->name }}
                                @else
                                    Unknown
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforeach
            </table>

        </div>
        <div class="mx-3">
            {{ $purchase_requests->links() }}
        </div>
    </div>
</div>
