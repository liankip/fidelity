@extends('layouts.app')

@section('content')
    <div class="container mt-2">
        <div class="row">
            <div class="col-lg-12 margin-tb">
                <div class="pull-left">
                    <h2 class="primary-color-sne">Master Data Price</h2>
                </div>

                @foreach (['danger', 'warning', 'success', 'info'] as $key)
                    @if(Session::has($key))
                        <div class="alert alert-{{ $key }} alert-dismissible fade show mb-1 mt-1" role="alert">
                            {{ Session::get($key) }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                            </button>
                        </div>
                    @endif
                @endforeach

                <div class="pull-right mt-5">
                    @if(auth()->user()->hasGeneralAccess())
                        <a class="btn btn-success" href="{{ route('prices.create') }}"><i class="fa-solid fa-plus pe-2"></i> Create Price</a>

                        <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#update_dollar">
                            Update Dollar Price
                        </button>

                        <a class="btn btn-outline-success" href="{{ route('sync-price') }}">Sync Unit</a>

                        <a href="{{ route('export.price') }}" class="btn btn-success">Export Excel</a>
                    @endif

                    <div class="modal fade" id="update_dollar" tabindex="-1" aria-labelledby="update_dollarLabel"
                        aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form action="{{ url('Updatepricebydolar', []) }}" method="post">
                                    <div class="modal-header">
                                        <h1 class="modal-title fs-5" id="update_dollarLabel">Update Price by dolar</h1>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label for="exampleFormControlInput1" class="form-label">IDR for USD</label>
                                            <input type="number" name="rupiah" class="form-control" required
                                                placeholder="Result Rupiah">
                                        </div>
                                    </div>

                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Close</button>
                                        @csrf
                                        <button type="submit" class="btn btn-primary">Save changes</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    {{-- <a class="btn btn-success" href="{{ route('prices.import') }}"> Upload CSV</a> --}}
                    {{-- <a class="btn btn-success" href="{{ route('prices.export') }}"> Downnload as CSV</a> --}}
                </div>
            </div>
        </div>

        <div class="card mt-2 primary-box-shadow">
            {{-- <form action="{{ route('prices.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="file" name="file" class="form-control">
                <br>
                <button class="btn btn-success">Upload CSV</button>
            </form> --}}
            <div class="card-body">

                <div class="d-flex mt-3">
                    <div class="w-100">
                        <form action="" method="get" class="d-flex">
                            <div class="input-group">
                                <input type="text" class="form-control" name="search" placeholder="Search"
                                    value="{{ $searchcompact }}">
                                <button class="btn btn-outline-secondary" type="submit">Search</button>
                            </div>
                        </form>
                    </div>
                </div>

                <hr>

                <table class="table primary-box-shadow mt-3">
                    <tr class="thead-light">
                        <th class="text-center align-middle border-top-left" width="5%">No.</th>
                        <th class="text-center align-middle" width="10%">Kode barang</th>
                        <th class="text-center align-middle" width="17%">Supplier</th>
                        <th class="text-center align-middle" width="21%">Item</th>
                        <th class="text-center align-middle" width="10%">Unit</th>
                        <th class="text-center align-middle" width="11%">Price</th>
                        <th class="text-center align-middle" width="10%">Term of Payment</th>
                        <th class="text-center align-middle" width="8%">Tax</th>
                        <th class="text-center align-middle" width="8%">IDR to USD</th>
                        {{-- <th class="text-center align-middle" width="5%">Tax</th> --}}
                        <th class="text-center align-middle border-top-right" width="10%">Action</th>
                    </tr>
                    @foreach ($prices as $key => $price)
                        <tr class="{{ $loop->even ? 'table-light' : '' }}">
                            <td class="align-middle" style="text-align: center">
                                {{ $key + 1 }}
                            </td>
                            <td class="align-middle">{{ $price->item->item_code }}</td>
                            <td class="align-middle">{{ $price->supplier->name }}</td>
                            {{-- @if (!$price->item)
                                @dd($price->item_id)
                            @endif --}}
                            <td class="align-middle">{{ $price->item->name }}</td>
                            <td class="align-middle">{{ $price->unit->name }}</td>
                            <td class="align-middle" style="text">
                                <div class="d-flex justify-content-between">
                                    <div>Rp.</div>
                                    <div>{{ number_format($price->price, 0, ',', '.') }}</div>
                                </div>
                            </td>
                            <td class="align-middle" style="text-align: center">
                                {{ $price->supplier->term_of_payment }}
                            </td>

                            <td class="text-center align-middle">
                                @if ($price->tax_status == 0)
                                    <span class="badge badge-success">Exclude</span>
                                @endif
                                @if ($price->tax_status == 1)
                                    <span class="badge badge-primary">Include</span>
                                @endif
                                @if ($price->tax_status == 2)
                                    <span class="badge badge-warning">Non PPN</span>
                                @endif
                            </td>

                                @if (0 < $price->old_idr_by_usd)
                                <td class="align-middle text-start">
                                    {{ $price->old_idr_by_usd }}
                                </td>

                                @else
                                <td class="align-middle text-center text-danger">
                                    None
                                </td>
                                @endif

                            {{-- <td>{{ number_format($price->tax) }}% </td> --}}

                            <td class="align-middle text-center">
                                <form action="{{ route('prices.destroy', $price->id) }}" method="Post">
                                    <a class="btn btn-sm btn-outline-primary" href="{{ route('prices.edit', $price->id) }}">Edit</a>
                                    {{-- @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">Delete</button> --}}
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </table>
                <div class="mt-4">
                    {{ $prices->links() }}
                </div>
            </div>
        </div>

    @endsection
