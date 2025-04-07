@extends('layouts.app')

@section('content')
    {{-- @dd($statuspr); --}}
    <div class="container mt-2">
        <div class="row">
            <div class="col-lg-12 margin-tb">
                <div class="pull-left">
                    {{-- <a class="btn btn-primary"
                    href="{{ url()->previous() ? url()->previous() : url('purchase_requests') }}"
                    enctype="multipart/form-data">Back</a> --}}
                    <a href="{{ url('purchase_requests') }}" class="third-color-sne">
                        <i class="fa-solid fa-chevron-left fa-xs"></i> Back</a>

                    <h2 class="primary-color-sne">Purchase Request Detail - {{ $statuspr->project->name ?? 'Raw Materials' }}</h2>
                    @if ($statuspr->pr_no)
                        <div class="">
                            PR Number : <h4 class="primary-color-sne"><strong>{{ $statuspr->pr_no }}</strong></h4>
                        </div>
                    @endif
                    <div class="">
                        Requested by : <h4 class="primary-color-sne"><strong>{{ $statuspr->createdBy?->name }}</strong></h4>
                    </div>
                </div>
            </div>
        </div>

        @if ($message = Session::get('success'))
            <div class="alert alert-success">
                <p>{{ $message }}</p>
            </div>
        @endif

        <div class="card primary-box-shadow mt-5">
            <div class="card-body">
                @if ($purchaserequestdetail->count() == 0)
                    @csrf
                    <a class="btn btn-success" href="{{ route('itempr.index', $statuspr->id) }}">Tambah Barang</a>
                @endif

                <div class="primary-box-shadow">
                    <table class="table">
                        <thead class="thead-light text-center">
                            <th class="border-top-left">No</th>
                            <th>Item Name</th>
                            <th>Type</th>
                            <th>Quantity</th>
                            <th>Unit</th>
                            <th>Notes</th>
                            <th>Created By</th>
                            <th>PO Status</th>
                            <th class="border-top-right">Action</th>
                        </thead>
                        @forelse ($purchaserequestdetail as $key => $detail)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td>{{ $detail->item->name }}</td>
                                <td>{{ $detail->type }}</td>
                                <td class="text-center align-middle">
                                    <strong>{{ $detail->qty }}</strong>

                                    @if($detail->include_stock !== null)
                                        <div class="mt-2 p-2 rounded bg-light text-success border border-success">
                                            <small class="d-block fw-bold">
                                                <i class="fas fa-warehouse"></i> {{ $detail->stock_from }}
                                            </small>
                                            <span class="fw-medium">Include Stock: {{ $detail->include_stock }}</span>
                                        </div>

                                        <a class="btn btn-primary btn-sm mt-3" title="Download Memo" href="{{ route('print-new-memo', $detail->id) }}">
                                            <i class="fas fa-download"></i>
                                        </a>
                                    @endif
                                </td>

                                <td>{{ $detail->unit }}</td>
                                <td>{{ $detail->notes }}</td>
                                <td>{{ $detail->createdBy?->name }}</td>
                                <td class="text-center">
                                    @if ($detail->status == 'Rejected')
                                        <span class="badge badge-danger">Rejected</span>
                                    @else
                                        <ul class="list-unstyled">
                                            @forelse($detail->podetailall as $podata)
                                                <li class="d-flex" style="font-size: 12px">
                                                    <a class=""
                                                        href="{{ url('/po_details', ['id' => $podata->po->id]) }}"
                                                        target="__blank">
                                                        {{ $podata->po->po_no }} ({{ $podata->po->status }})
                                                    </a>&nbsp;
                                                </li>
                                            @empty
                                                @if ($detail->is_bulk == 1)
                                                    <span class="badge badge-success">Bulk</span>
                                                @else
                                                    <span class="badge badge-warning">pending</span>
                                                @endif
                                            @endforelse
                                        </ul>
                                    @endif

                                </td>
                                @if ($statuspr->status == 'Approved' || $statuspr->status == 'Partially')
                                    <td>
                                        @if (count($detail->podetailall) == null)
                                            <button class="btn btn-danger" @if ($detail->status == 'Rejected') disabled @endif
                                                data-bs-toggle="modal" data-bs-target="#exampleModal-{{ $detail->id }}">
                                                <i class="fa-solid fa-xmark"></i>
                                                Reject
                                            </button>
                                        @endif
                                    </td>

                                    <!-- Reject Modal -->
                                    <div class="modal fade" id="exampleModal-{{ $detail->id }}" tabindex="-1"
                                        aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <form action="{{ route('reject-pr-detail', $detail->id) }}" method="post">
                                                    @csrf
                                                    @method('put')
                                                    <div class="modal-header">
                                                        <h1 class="modal-title fs-5" id="exampleModalLabel">Reject Item</h1>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        Reject {{ $detail->item->name }} ?
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Close</button>
                                                        <button type="submit" class="btn btn-primary">Confirm</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">No Data</td>
                            </tr>
                        @endforelse
                    </table>
                    @if ($purchaserequestdetail->count() == 0)
                        @csrf
                        {{-- <a class="btn btn-success" href="{{ route('itempr.index') }}">Tambah Barang</a> --}}
                    @endif



                    @if ($statuspr->status == 'New' && isset($detail))
                        @if (auth()->user()->hasGeneralAccess())
                            <a class="btn btn-success" href="{{ route('purchase_requests.edit', $detail->pr_id) }}">Edit
                                Destinasi</a>
                            <a class="btn btn-success" href="{{ route('itempr.index', $statuspr->id) }}">Tambah Barang</a>
                            @can('create-po')
                                <a class="btn btn-success" href="{{ route('purchase_orders.show', $detail->pr_id) }}">Create
                                    PO</a>
                            @endcan
                        @endif
                    @endif
                </div>
            </div>
        </div>
    @endsection
