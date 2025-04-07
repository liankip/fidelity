<div class="container mt-2">
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
    <div class="card">
        <div class="card-header">
            <div class="pull-left">
                <h2>{{ config('app.company', 'SNE') }} - ERP | Payment List Non Cash</h2>
            </div>
        </div>
        <div class="card-body">
            <div class="input-group mb-3">
                <input type="text" class="form-control" wire:model="search" name="search" placeholder="Search"
                    value="" aria-label="Recipient's username" aria-describedby="button-addon2">
            </div>
            <table class="table table-bordered">
                <tr class="table-secondary">
                    <th style="text-align: center">No</th>

                    <th style="text-align: center">PO NO</th>
                    {{-- <th>PR Type</th> --}}
                    <th style="text-align: center">Project</th>
                    <th>Warehouse</th>
                    <th style="text-align: center">Tgl Barang Sampai</th>
                    <th style="text-align: center">Status</th>
                    <th style="text-align: center">ToP</th>
                    <th style="text-align: center">Item</th>
                    <th style="text-align: center">Total Amount</th>
                    <th style="text-align: center">Image</th>
                    <th style="text-align: center">Action</th>
                </tr>
                @foreach ($non_cash as $key => $val_non_cash)
                    {{-- @if ($val_non_cash->status == 'Wait For Approval') --}}
                    <tr style="font-size: 13px" onmouseover="this.style.backgroundColor='#F4F6F6'"
                        onmouseout="this.style.backgroundColor='white'">
                        <td class="text-center">{{ $key + 1 }}</td>
                        <td><b>{{ $val_non_cash->po_no }}</b></td>
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
                        <td style="text-align: center">{{ count($val_non_cash->podetail) }}</td>
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
                        <td>
                            <div class="btn-group">
                                <button type="button" class="btn btn-secondary dropdown-toggle btn-sm"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    View
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <form action="{{ route('viewphoto_inv', $val_non_cash->id) }}" method="post">
                                        @csrf
                                        @method('put')
                                        <button type="submit" class="dropdown-item">View Invoice</button>
                                    </form>
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

                        <td>
                            <div class="btn-group">
                                <button type="button" class="btn btn-secondary dropdown-toggle btn-sm"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    Action
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    {{-- <form action="{{ route('upload-payment', $val_non_cash->id) }}" method="post">
                                            @csrf
                                            @method('get')
                                            <button type="submit" class="btn btn-success">Pay</button>
                                        </form> --}}
                                    <form action="{{ route('paydir', $val_non_cash->id) }}" method="post">
                                        @csrf
                                        @method('put')
                                        <button type="submit" class="dropdown-item">List Pay</button>
                                    </form>
                                    {{-- <form action="{{ route('concern', $val_non_cash->id) }}" method="post">
                                            @csrf
                                            @method('put')
                                            <button type="submit" class="btn btn-warning">Concern</button>
                                        </form> --}}
                                    <a class="dropdown-item" href="{{ url('po_details', $val_non_cash->id) }}">View</a>
                                    <button type="button" class="dropdown-item" data-toggle="modal"
                                        data-target="#remarkconcern-{{ $val_non_cash->id }}">Concern</button>
                                </ul>
                            </div>

                        </td>

                    </tr>
                    {{-- @endif --}}
                    <div class="modal fade" id="remarkconcern-{{ $val_non_cash->id }}" tabindex="-1" role="dialog"
                        aria- labelledby="demoModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="demoModalLabel">Concern Reason PO
                                        {{ $val_non_cash->po_no }}</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria- label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    {{-- Welcome, Websolutionstuff !! --}}
                                    <form action="{{ route('concern', $val_non_cash->id) }}" method="post">
                                        <strong>Concern Reason:</strong>
                                        <div class="form-group">
                                            <textarea id="remark_concern" name="remark_concern" rows="4" class="form-control"></textarea>
                                            @error('remark_concern')
                                                <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                                            @enderror
                                        </div>
                                </div>
                                <div class="modal-footer">
                                    {{-- <button type="button" class="btn btn-secondary" data-
                                        dismiss="modal">Close</button> --}}
                                    {{-- <button type="button" class="btn btn-primary">Reject</button> --}}

                                    <span>

                                        @csrf
                                        @method('put')
                                        <button type="submit" class="btn btn-warning">Concern</button>
                                        </form>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </table>
        </div>

        <div class="card-footer">
            {{ $non_cash->links() }}
        </div>

        {{-- <div class="card-footer"></div> --}}
    </div>
</div>
{{-- {!! $val_cash->links() !!} --}}
