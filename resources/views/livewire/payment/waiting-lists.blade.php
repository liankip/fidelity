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
    <div class="card">
        <div class="card-header">
            <div class="pull-left">
                <h2>Payable List</h2>
            </div>
        </div>
        <div class="card-body">
            <div class="input-group mb-3">
                <input type="text" class="form-control" wire:model="search" name="search" placeholder="Search"
                    value="" aria-label="Recipient's username" aria-describedby="button-addon2">
            </div>
            <table class="table table-bordered">
                <tr class="table-secondary">
                    <th style="text-align: center">No.</th>
                    <th style="text-align: center">PO NO</th>
                    {{-- <th>PR Type</th> --}}
                    <th style="text-align: center">Project</th>
                    <th style="text-align: center">Warehouse</th>
                    {{-- <th style="text-align: center">Tgl Barang Sampai</th> --}}
                    <th style="text-align: center">Status</th>
                    <th style="text-align: center">ToP</th>
                    <th style="text-align: center">Due Date</th>
                    <th style="text-align: center">Item</th>
                    <th style="text-align: center">Total Amount</th>
                    <th style="text-align: center">Image</th>
                    <th style="text-align: center">Action</th>
                </tr>
                @php
                    $no = 1;
                @endphp

                @foreach ($po as $key => $val_non_cash)
                    {{-- @if ($val_non_cash->status == 'Wait For Approval') --}}
                    <tr onmouseover="this.style.backgroundColor='#F4F6F6'"
                        onmouseout="this.style.backgroundColor='white'">
                        <td style="text-align: center">{{ $no }}</td>
                        <td>{{ $val_non_cash->po_no }}</td>
                        {{-- <td></td> --}}
                        <td>{{ $val_non_cash->project->name }}</td>
                        <td>
                            @if ($val_non_cash->warehouse)
                                {{ $val_non_cash->warehouse->name }}
                            @else
                                {{ $val_non_cash->project->name }}
                            @endif
                        </td>
                        {{-- <td>{{ $val_non_cash->updated_at }}</td> --}}
                        <td>{{ $val_non_cash->status }}</td>
                        <td>{{ $val_non_cash->term_of_payment }}</td>
                        <td>
                            @if ($val_non_cash->top_date)
                                {{ date('d-m-Y', strtotime($val_non_cash->top_date)) }}
                            @else
                                -
                            @endif
                        </td>
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
                            <div class="d-flex justify-content-between">
                                <div>Rp.</div>
                                <div>{{ number_format($totalamount, 0, ',', '.') }}</div>
                            </div>

                        </td>
                        <td class="text-center">
                            <div class="btn-group ">
                                <button type="button" class="btn btn-secondary dropdown-toggle btn-sm"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    View
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    {{-- <form action="{{ route('viewphoto_inv', $val_non_cash->id) }}" method="post">
                                        @csrf
                                        @method('put')
                                        <button type="submit" class="dropdown-item">View Invoice</button>
                                    </form> --}}
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
                                    {{-- <form action="{{ route('viewphoto_do', $val_non_cash->id) }}" method="post">
                                        @csrf
                                        @method('put')
                                        <button type="submit" class="dropdown-item">View DO</button>
                                    </form> --}}
                                    <a href="{{ route('viewphoto_do', $val_non_cash->id) }}"
                                        class="btn btn-primary">View DO</a>
                                </ul>
                            </div>
                        </td>
                        <td class="text-center">
                            <div class="btn-group">
                                <button type="button" class="btn btn-secondary dropdown-toggle btn-sm"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    Action
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    {{-- <form action="{{ route('uppayment', $val_non_cash->id) }}" method="post">
                                        @csrf
                                        @method('put')
                                        <button type="submit" class="dropdown-item">Up Payment</button>
                                    </form> --}}
                                    <form action="{{ route('paydir', $val_non_cash->id) }}" method="post">
                                        @csrf
                                        @method('put')
                                        <button type="submit" class="dropdown-item">List Pay</button>
                                    </form>
                                    <a class="dropdown-item" href="{{ url('po_details', $val_non_cash->id) }}">View
                                        detail</a>
                                </ul>
                            </div>

                        </td>

                    </tr>
                    {{-- @endif --}}
                    @php
                        $no++;
                    @endphp
                @endforeach
            </table>
        </div>
        <div class="card-footer">
            {{ $po->links() }}
        </div>
    </div>
</div>
