@extends('layouts.app')

@section('content')
    <div class="container mt-2">
        <div class="row">
            <div class="col-lg-12 margin-tb">
                <div class="pull-left">
                    <h2>{{ config('app.company', 'SNE') }} - ERP | Purchase Request Page</h2>
                </div>
                @if (auth()->user()->type == 'it' || auth()->user()->type == 'adminlapangan')
                    <div class="pull-right mb-2">
                        {{-- <a class="btn btn-success" href="{{ route('itempr.index') }}"> Create purchaserequest</a> --}}
                    </div>
                @endif
            </div>
        </div>
        @if ($message = Session::get('success'))
            <div class="alert alert-success">
                <p>{{ $message }}</p>
            </div>
        @endif
        <div class="card">
            <div class="card-header"> Purchase Request
                @if (auth()->user()->type == 'it' || auth()->user()->type == 'adminlapangan')
                    {{-- | <a class="btn btn-success" href="{{ route('itempr.index') }}">New PR</a> --}}
                    <a class="btn btn-success btn-sm" href="{{ route('purchase_request_create') }}">New PR</a>
                @endif
            </div>
            <div class="card-body">
                <form action="" method="get" class="d-flex">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" name="search" placeholder=""
                            value="{{ $searchcompact }}" aria-label="Recipient's username" aria-describedby="button-addon2">
                        <button class="btn btn-outline-secondary" type="submit" id="button-addon2">Search</button>
                    </div>
                </form>
                <table class="table table-bordered">
                    <tr>
                        <th>No</th>
                        </th>
                        <th>PR NO</th>
                        <th>PR Type</th>
                        <th>Project</th>
                        <th>Requeter</th>
                        <th>bagian</th>
                        {{-- <th>Warehouse</th> --}}
                        <th>Tgl Request</th>
                        <th>Status</th>
                        <th>Notes</th>
                        <th width="15%">Action</th>
                    </tr>
                    @foreach ($purchase_requests as $key => $purchaserequest)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $purchaserequest->pr_no }}</td>
                            <td>{{ $purchaserequest->pr_type }}</td>

                            <td>
                                @if ($purchaserequest->project)
                                    {{ $purchaserequest->project->name }}
                                @endif
                            </td>
                            <td>{{ $purchaserequest->requester }}</td>
                            <td>{{ $purchaserequest->partof }}</td>
                            {{-- <td>{{ $purchaserequest->warehouse->name }}</td> --}}
                            <td>{{ $purchaserequest->created_at }}</td>
                            <td>{{ $purchaserequest->status }}</td>
                            <td>{{ $purchaserequest->remark }}</td>
                            <td>
                                @if ($purchaserequest->status == 'Cancel' || $purchaserequest->status == 'cancel')
                                @endif

                                @if (auth()->user()->type == 'it' || auth()->user()->type == 'adminlapangan')
                                    @if ($purchaserequest->status == 'Cancel')
                                        <form action="{{ route('duplicate_pr', $purchaserequest->id) }}" method="post">
                                            @csrf
                                            @method('put')
                                            <button type="submit" class="btn btn-warning btn-sm">Duplicate</button>
                                        </form>
                                    @endif
                                    @if ($purchaserequest->status != 'Cancel' || $purchaserequest->status != 'cancel')
                                        @if ($purchaserequest->status == 'Draft')
                                            {{-- <button disabled type="submit" class="btn grey">Edit</button> --}}
                                        @endif
                                        @if (
                                            $purchaserequest->status != 'Approved' &&
                                                $purchaserequest->status != 'Cancel' &&
                                                $purchaserequest->status != 'cancel')
                                            <form action="{{ route('cancel_pr', $purchaserequest->id) }}" method="post">
                                                @csrf
                                                @method('put')
                                                <button type="submit" class="btn btn-danger btn-sm">Cancel</button>
                                            </form>
                                            {{-- <a class="btn btn-danger" href="{{ url('purchase_requests.cancel',$purchaserequest->id) }}">Cancel</a> --}}
                                        @endif

                                        @if ($purchaserequest->status == 'Approved')
                                            {{-- <button disabled type="submit" class="btn grey">Edit</button> --}}
                                        @endif
                                        @if (
                                            $purchaserequest->status == 'New' ||
                                                $purchaserequest->status == 'Review' ||
                                                $purchaserequest->status == 'New Duplicate')
                                            {{-- <a class="btn btn-success"
                                                href="{{ route('purchase_requests.edit', $purchaserequest->id) }}">Edit
                                                Destinasi</a> --}}
                                            @if ($purchaserequest->pr_type != 'Barang')
                                                <a class="btn btn-success btn-sm"
                                                    href="{{ route('itempr.index', $purchaserequest->id) }}">Edit Item
                                                    Load</a>
                                            @endif
                                            @if ($purchaserequest->pr_type == 'Barang')
                                                <a class="btn btn-success btn-sm"
                                                    href="{{ route('itempr.index', $purchaserequest->id) }}">Edit
                                                    Barang</a>
                                            @endif

                                            {{-- <a class="btn btn-success" href="{{ route('purchase_request_details.create') }}">Tambah Barang</a> --}}
                                        @endif
                                    @endif

                                    {{-- <a class="btn btn-primary" href="{{ route('purchase_requests.edit',$purchaserequest->id) }}">Edit</a> --}}
                                @endif
                                @if (auth()->user()->type == 'it' || auth()->user()->type == 'purchasing')
                                    @if ($purchaserequest->status == 'Draft')
                                        {{-- <button disabled type="submit" class="btn grey">Create PO</button> --}}
                                    @endif
                                    @if ($purchaserequest->status == 'Approved')
                                        {{-- <button disabled type="submit" class="btn grey">Create PO</button> --}}
                                    @endif
                                @endif
                                @if (count($purchaserequest->podetail))
                                    @if ($purchaserequest->status == 'New' || $purchaserequest->status == 'New Duplicate')
                                        @if ($purchaserequest->pr_type == 'Barang')
                                            <a class="btn btn-success btn-sm"
                                                href="/purchase_order/{{ $purchaserequest->id }}">Create PO</a>
                                        @endif
                                        @if ($purchaserequest->pr_type != 'Barang')
                                            <a class="btn btn-success btn-sm" href="/spk/{{ $purchaserequest->id }}">Create
                                                SPK NEW</a>
                                        @endif
                                    @else
                                        @php
                                            $count = 0;
                                            $false = 0;
                                        @endphp
                                        @foreach ($purchaserequest->podetail as $item)
                                            @php
                                                $data_qty = DB::table('purchase_order_details')
                                                    ->where('purchase_request_detail_id', $item->id)
                                                    ->where('item_id', $item->item_id)
                                                    ->sum('qty');
                                            @endphp
                                            @if ($item->podetail)
                                                @php
                                                    $count++;
                                                @endphp
                                            @endif
                                            @if ($item->qty > $data_qty)
                                                @php
                                                    $false++;
                                                @endphp
                                            @endif
                                        @endforeach

                                        @if ($count != count($purchaserequest->prdetail) || $false != 0)
                                            @if ($purchaserequest->pr_type == 'Barang')
                                                <a class="btn btn-success btn-sm"
                                                    href="{{ route('itempr.index', $purchaserequest->id) }}">Edit
                                                    Barang</a>
                                                <a class="btn btn-success btn-sm"
                                                    href="/purchase_order/{{ $purchaserequest->id }}">Create PO</a>
                                            @endif
                                            @if ($purchaserequest->pr_type != 'Barang')
                                                <a class="btn btn-success btn-sm"
                                                    href="/spk/{{ $purchaserequest->id }}">Create
                                                    SPK</a>
                                            @endif
                                        @endif
                                    @endif
                                    <a class="btn btn-primary btn-sm"
                                        href="{{ route('purchase_request_details.show', $purchaserequest->id) }}">Detail</a>
                                @endif
                                @csrf
                                {{-- <form action="{{ route('purchase_requests.destroy',$purchaserequest->id) }}" method="Post">
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">Delete</button>
                                    </form> --}}
                            </td>
                        </tr>
                    @endforeach
                </table>

            </div>
            <div class="card-footer">
                {!! $purchase_requests->links() !!}
            </div>
        </div>
    </div>



@endsection
{{-- </body>
                </html> --}}
