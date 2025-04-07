<div class="mt-2">
    <div class="row">
        <div class="col-lg-12 margin-tb">

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
    <h2>{{ config('app.company', 'SNE') }} - ERP | Payment List</h2>
    <div class="card">
        <div class="card-body">
            <x-common.table id="paymentList">
                <thead class="thead-light">
                    <tr class=" ">
                        <th style="text-align: center" class="align-middle">No</th>
                        <th style="text-align: center" class="align-middle">PO NO</th>
                        {{-- <th>PR Type</th> --}}
                        <th style="text-align: center" class="align-middle">Project</th>
                        <th style="text-align: center" class="align-middle">Warehouse</th>
                        <th style="text-align: center" class="align-middle">Tgl Barang Sampai</th>
                        <th style="text-align: center" class="align-middle">Status</th>
                        <th style="text-align: center" class="align-middle">ToP</th>
                        <th style="text-align: center" class="align-middle">Due Date</th>
                        <th style="text-align: center" class="align-middle">Total Amount</th>
                        <th style="text-align: center" class="align-middle not-export">Image</th>
                        {{-- <th style="text-align: center" class="align-middle">Note</th> --}}
                        <th style="text-align: center" class="align-middle not-export">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($ntp as $key => $val_non_cash)
                        {{-- @if ($val_non_cash->status == 'Wait For Approval') --}}
                        <tr style="font-size: 13px" onmouseover="this.style.backgroundColor='#F4F6F6'"
                            onmouseout="this.style.backgroundColor='white'">
                            <td class="text-center">{{ $key + 1 }}</td>
                            <td>{{ $val_non_cash->po_no }}</td>
                            <td>{{ $val_non_cash->project->name }}</td>
                            <td>
                                @if ($val_non_cash->warehouse)
                                    {{ $val_non_cash->warehouse->name }}
                                @else
                                    {{ $val_non_cash->project->name }}
                                @endif
                            </td>
                            <td>{{ $val_non_cash->updated_at }}</td>
                            <td>{{ $val_non_cash->status }}</td>
                            <td>{{ $val_non_cash->term_of_payment }}</td>
                            <td class="text-center">
                                @if ($val_non_cash->top_date)
                                    {{ date('d-m-Y', strtotime($val_non_cash->top_date)) }}
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                @php
                                    $totalamount = 0;
                                @endphp
                                @foreach ($val_non_cash->podetail as $sajs)
                                    @php
                                        $totalamount += $sajs->amount;
                                    @endphp
                                @endforeach
                                @php
                                    $ongkir = 0;
                                @endphp
                                @if ($val_non_cash->deliver_status == 1)
                                    @php
                                        $ongkir = $val_non_cash->tarif_ds;
                                    @endphp
                                @endif

                                @php
                                    $ppn = 0;
                                @endphp
                                @if ($val_non_cash->podetail->first()->tax_status == 2)
                                    @php
                                        $ppn = 0;
                                    @endphp
                                @else
                                    @php
                                        $ppn = round($totalamount * 0.11);
                                    @endphp
                                @endif
                                @if ($val_non_cash->tax_custom)
                                    @php
                                        $ppn = $val_non_cash->tax_custom;
                                    @endphp
                                @endif

                                <div class="d-flex justify-content-between">
                                    <div>Rp.</div>
                                    <div>{{ number_format($totalamount + $ppn + $ongkir, 0, ',', '.') }}</div>
                                </div>

                            </td>
                            {{-- <td>{{ $val_non_cash->remark }}</td> --}}
                            <td class="text-center">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-secondary dropdown-toggle btn-sm"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        Image
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">

                                        <a href="{{ route('viewphoto_inv', $val_non_cash->id) }}" target="_blank"
                                            class="dropdown-item">View Invoice</a>

                                        {{-- <form action="{{ route('viewphoto_submition', $val_non_cash->id) }}"
                                            method="post">
                                            @csrf
                                            @method('put')
                                            <button type="submit" class="dropdown-item">View Barang</button>
                                        </form> --}}
                                        <a href="{{ route('viewphoto_submition', $val_non_cash->id) }}"
                                            class="btn btn-primary">View Photo Barang</a>
                                    </ul>
                                </div>
                            </td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center">
                                    <a class="btn btn-primary btn-sm me-1"
                                        href="{{ url('po_details', $val_non_cash->id) }}">View</a>
                                    <form action="{{ route('upload-payment', $val_non_cash->id) }}" method="post">
                                        @csrf
                                        @method('get')
                                        <button type="submit" class="btn btn-success btn-sm">Pay</button>
                                    </form>
                                </div>
                                {{-- <form action="{{ route('paydir', $val_non_cash->id) }}" method="post">
                                    @csrf
                                    @method('put')
                                    <button type="submit" class="btn btn-primary">List Pay</button>
                                </form>
                                <form action="{{ route('concern', $val_non_cash->id) }}" method="post">
                                    @csrf
                                    @method('put')
                                    <button type="submit" class="btn btn-warning">Concern</button>
                                </form> --}}
                            </td>

                        </tr>
                        {{-- @endif --}}
                    @endforeach
                </tbody>
            </x-common.table>
        </div>
    </div>
    <script>
        // $(document).ready(function() {
        //     initDataTables("#paymentList");
        // });
    </script>
</div>
