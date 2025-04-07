@extends('layouts.app')

@section('content')
    <div class="container mt-2">
        <div class="row">
            <div class="col-lg-12 margin-tb">
                <div class="pull-left">
                    <a href="{{ route('suppliers.index') }}" class="third-color-sne"> <i
                        class="fa-solid fa-chevron-left fa-xs"></i> Back</a>
                    <h2 class="primary-color-sne">{{$supplier->name}} | Item List</h2>
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
            </div>
        </div>

        <div class="card mt-5 primary-box-shadow">
            {{-- <form action="{{ route('prices.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="file" name="file" class="form-control">
                <br>
                <button class="btn btn-success">Upload CSV</button>
            </form> --}}
            <div class="card-body">

                <table class="table mt-3 primary-box-shadow">
                    <tr class="thead-light">
                        <th class="text-center align-middle border-top-left" width="5%">No.</th>
                        <th class="text-center align-middle" width="21%">Item</th>
                        <th class="text-center align-middle" width="10%">Unit</th>
                        <th class="text-center align-middle" width="11%">Price</th>
                        <th class="text-center align-middle" width="10%">Term of Payment</th>
                        <th class="text-center align-middle" width="8%">Tax</th>
                        <th class="text-center align-middle border-top-right" width="8%">IDR to USD</th>
                    </tr>
                    @forelse ($items as $key => $item)
                        <tr class="{{ $loop->even ? 'table-light' : '' }}">
                            <td class="align-middle" style="text-align: center">
                                {{ $key + 1 }}
                            </td>
                            <td class="align-middle">{{ $item->item->name }}</td>
                            <td class="align-middle">{{ $item->unit->name }}</td>
                            <td class="align-middle" style="text">
                                <div class="d-flex justify-content-between">
                                    <div>Rp.</div>
                                    <div>{{ number_format($item->price, 0, ',', '.') }}</div>
                                </div>
                            </td>
                            <td class="align-middle" style="text-align: center">
                                {{ $item->supplier->term_of_payment }}
                            </td>

                            <td class="text-center align-middle font-monospace">
                                @if ($item->tax_status == 0)
                                    <span class="badge text-bg-success">Exclude</span>
                                @endif
                                @if ($item->tax_status == 1)
                                    <span class="badge text-bg-primary">Include</span>
                                @endif
                                @if ($item->tax_status == 2)
                                    <span class="badge text-bg-warning">Non PPN</span>
                                @endif
                            </td>

                            @if (0 < $item->old_idr_by_usd)
                                <td class="align-middle text-start">
                                    {{ $item->old_idr_by_usd }}
                                </td>
                            @else
                                <td class="align-middle text-center text-danger">
                                    None
                                </td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">No Data</td>
                        </tr>
                    @endforelse
                </table>
                <div class="mt-4">
                    {{ $items->links() }}
                </div>
            </div>
        </div>

@endsection
