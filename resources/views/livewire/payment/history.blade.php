<div class="mt-2">
    <div class="row">
        <div class="col-lg-12 margin-tb">
            {{-- <div class="pull-right mb-2">
                    <a class="btn btn-success" href="{{ route('payments.create') }}"> Create payment</a>
                </div> --}}
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
    <h2>{{ config('app.company', 'SNE') }} - ERP | Payment List Page</h2>
    <div class="card">

        <div class="card-body">
            <div class="input-group mb-3">
                <input type="text" class="form-control" wire:model="search" name="search" placeholder="Search"
                    value="" aria-label="Recipient's username" aria-describedby="button-addon2">
            </div>
            <table class="table table-bordered">
                <tr class="table-secondary">
                    <th class="text-center">no</th>
                    <th class="text-center">Payment Picture</th>
                    <th class="text-center">PO No</th>
                    <th class="text-center">Project</th>
                    <th class="text-center">Warehouse</th>
                    <th class="text-center">Items</th>
                    <th class="text-center">Total Amount</th>
                    <th class="text-center">Status</th>
                    <th class="text-center">Notes</th>
                    <th class="text-center">Action</th>
                    {{-- <th width="280px">Action</th> --}}
                </tr>
                @foreach ($payments as $key => $payment)
                    @if (!is_null($payment->purchaseorder))
                        <tr onmouseover="this.style.backgroundColor='#F4F6F6'"
                            onmouseout="this.style.backgroundColor='white'">
                            <td class="text-center">{{ $key + 1 }}</td>
                            <td>
                                <a href="/{{ $payment->payment_pict }}" target="_blank">
                                    <img src="{{ url($payment->payment_pict, []) }}" class="img-fluid img-thumbnail"
                                        alt="" width="100" height="100">
                                </a>
                                {{-- <img src={{ url($invoice->foto_invoice) }} alt="" width="225 px"> --}}
                            </td>
                            {{-- <td>{{ $payment->payment_pict }}</td> --}}
                            <td><b>{{ $payment->purchaseorder->po_no }}</b></td>
                            <td>{{ $payment->purchaseorder->project->name }}</td>
                            <td>
                                @if ($payment->purchaseorder->warehouse)
                                    {{ $payment->purchaseorder->warehouse->name }}
                                @else
                                    {{ $payment->purchaseorder->project->name }}
                                @endif
                            </td>
                            <td class="text-center">{{ count($payment->purchaseorder->podetail) }}</td>
                            <td>
                                @php
                                    $totalamount = 0;
                                @endphp
                                @foreach ($payment->purchaseorder->podetail as $sajs)
                                    @php
                                        $totalamount += $sajs->amount;
                                    @endphp
                                @endforeach
                                @php
                                    $ongkir = 0;
                                @endphp
                                @if ($payment->purchaseorder->deliver_status == 1)
                                    @php
                                        $ongkir = $payment->purchaseorder->tarif_ds;
                                    @endphp
                                @endif
                                @php
                                    $ppn = 0;
                                @endphp
                                @if ($payment->purchaseorder->podetail->first()->tax_status == 2)
                                    @php
                                        $ppn = 0;
                                    @endphp
                                @else
                                    @php
                                        $ppn = round($totalamount * 0.11);
                                    @endphp
                                @endif
                                @if ($payment->purchaseorder->tax_custom)
                                    @php
                                        $ppn = $payment->purchaseorder->tax_custom;
                                    @endphp
                                @endif
                                <div class="d-flex justify-content-between">
                                    <div>
                                        Rp.
                                    </div>
                                    <div>
                                        {{ number_format($totalamount + $ppn + $ongkir, 0, ',', '.') }}
                                    </div>
                                </div>

                            </td>
                            <td class="fw-bold">
                                @if ($payment->status == 'Lunas')
                                    <b class="text-success">
                                        {{ $payment->status }}
                                    </b>
                                @else
                                    <b class="text-warning">
                                        {{ $payment->status }}
                                    </b>
                                @endif
                            </td>
                            <td>{{ $payment->notes }}</td>
                            <td class="text-center">
                                <a class="btn btn-primary btn-sm"
                                    href="{{ url('po_details', $payment->purchaseorder->id) }}">
                                    Detail
                                </a>
                            </td>
                            {{-- <td>
                           <form action="{{ route('payments.destroy', $payment->id) }}" method="Post">
                               <a class="btn btn-primary" href="{{ route('payments.edit', $payment->id) }}">Edit</a>
                               @csrf
                               @method('DELETE')
                               <button type="submit" class="btn btn-danger">Delete</button>
                           </form>
                       </td> --}}
                        </tr>
                    @endif
                @endforeach
            </table>
        </div>
        <div class="mt-3">
            {{ $payments->links() }}
        </div>
    </div>
</div>
