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
    <a href="{{ route('suppliers.index') }}" class="third-color-sne"> <i
        class="fa-solid fa-chevron-left fa-xs"></i>
    Back</a>
    <h2 class="primar-color-sne">History of Supplier
        @if ($supplierdata)
            <b>{{ $supplierdata->name }}</b>
        @endif
    </h2>
    <div class="card primary-box-shadow mt-5">
        <div class="card-header">
            <div class="pull-left">
                <button wire:click='export' class="btn btn-info"> <i class="fa-solid fa-download"></i> Export</button>
            </div>
        </div>
        <div class="card-body">
            <div class="input-group mb-3">
                <input type="text" class="form-control" wire:model="search" name="search" placeholder="Search"
                    value="" aria-label="Recipient's username" aria-describedby="button-addon2">
            </div>
            <table class="table primary-box-shadow">
                <tr class="thead-light">
                    <th class="text-center border-top-left">no</th>
                    <th class="text-center">PO No</th>
                    <th class="text-center">PO Status</th>
                    <th class="text-center">PR No</th>
                    <th class="text-center">Item</th>
                    <th class="text-center">Status Barang</th>
                    <th class="text-center">Total</th>
                    <th class="text-center table-top-right">Date</th>
                    {{-- <th width="280px">Action</th> --}}
                </tr>
                @foreach ($datas as $key => $value)
                    <tr onmouseover="this.style.backgroundColor='#F4F6F6'"
                        onmouseout="this.style.backgroundColor='white'">
                        <td class="text-center">{{ $key + 1 }}</td>
                        <td><b><a class="text-black"
                                    href="{{ route('po-detail', ['id' => $value->id]) }}">{{ $value->po_no }}</a></b>
                        </td>
                        <td>{{ $value->status }}</td>
                        <td><b>{{ $value->pr_no }}</b></td>

                        <td>
                            <ul>
                                @foreach ($value->podetail as $detail)
                                    <li>
                                        @if ($detail->prdetail)
                                            <a class="text-black"
                                                href="{{ route('history.items', ['id' => $detail->prdetail->item_id]) }}"
                                                target="_blank">
                                                {{ $detail->prdetail->item_name }}
                                                <b>({{ str_replace(',00', '', number_format($detail->qty, 2, ',', '.')) }}
                                                    @if ($detail->unit)
                                                        {{ $detail->unit }})
                                                    @else
                                                        {{ $detail->prdetail->unit }})
                                                    @endif
                                                </b>
                                            </a>
                                        @else
                                            {{ $detail->item->name }}
                                            <b>({{ str_replace(',00', '', number_format($detail->qty, 2, ',', '.')) }}
                                                {{ $detail->unit }})</b>
                                        @endif
                                    </li>
                                @endforeach

                            </ul>
                        </td>
                        <td>
                            {{ $value->status_barang }}
                        </td>
                        <td>
                            @php
                                $totalamount = 0;
                            @endphp
                            @foreach ($value->podetail as $sajs)
                                @php
                                    $totalamount += $sajs->amount;
                                @endphp
                            @endforeach
                            @php
                                $ongkir = 0;
                            @endphp
                            @if ($value->deliver_status == 1)
                                @php
                                    $ongkir = $value->tarif_ds;
                                @endphp
                            @endif

                            @php
                                $ppn = 0;
                            @endphp
                            @if ($value->podetail->first()->tax_status == 2)
                                @php
                                    $ppn = 0;
                                @endphp
                            @else
                                @php
                                    $ppn = round($totalamount * 0.11);
                                @endphp
                            @endif
                            @if ($value->tax_custom)
                                @php
                                    $ppn = $value->tax_custom;
                                @endphp
                            @endif
                            @if ($value->tax_custom)
                                @php
                                    $ppn = $value->tax_custom;
                                @endphp
                            @endif
                            <div class="d-flex justify-content-between">
                                <div>Rp.</div>
                                <div>{{ number_format($totalamount + $ppn + $ongkir, 0, ',', '.') }}</div>
                            </div>
                        </td>
                        <td class="text-end">
                            @if ($value->date_approved)
                                {{ date('d-m-Y', strtotime($value->date_approved)) }}
                            @else
                                {{ date('d-m-Y', strtotime($value->created_at)) }}
                            @endif
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>
        <div class="card-footer">

        </div>
    </div>
</div>
