@extends('layouts.app')

@section('content')
    <div class="container mt-2">
        <div class="row">
            <div class="col-lg-12 margin-tb">
                <div class="pull-left mb-2">
                    <h2>New Gudang Transfer Request</h2>
                </div>
                <div class="pull-right">

                </div>
            </div>
        </div>
        @if (session('status'))
            <div class="alert alert-success mb-1 mt-1">
                {{ session('status') }}
            </div>
        @endif
        <form action="{{ route('gudang_transfer_requests.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="card">
                <div class="card-header">
                    <a class="btn btn-primary" href="{{ route('gudang_transfers.index') }}"> Back</a>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>GT No:</strong>
                                <input readonly type="text" name="gt_no" class="form-control" placeholder="gt_no"
                                    value="@foreach ($idxs as $idxs){{++$idxs->idx}}@endforeach/GTR/{{ env('NO_PREFIX') }}/{{ $returnValueRoman }}/{{ $year }}">
                                {{-- <input hidden type="text" name="pr_no" class="form-control" placeholder="pr_no"
                                value="@foreach ($idxs as $idxs){{ ++$idxs->idx }} @endforeach/GT/{{ env('NO_PREFIX') }}/{{ $returnValueRoman }}/{{ $year }}"> --}}
                                @error('gt_no')
                                    <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>


                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>From:</strong>
                                {{-- <strong>
                                    <label for="pr_type">Project :</label>
                                </strong> --}}

                                <select name="from" id="from" class="js-example-basic-single form-control"
                                    @error('from') is-invalid @enderror">
                                    <option value="">Pilih Shipping Warehouse</option>
                                    @foreach ($warehouses as $warehouse)
                                        <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                                    @endforeach
                                </select>
                                @error('from')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>To:</strong>
                                {{-- <strong>
                                    <label for="warehouse_id">Warehouse :</label>
                                </strong> --}}

                                <select name="to" id="to" class="js-example-basic-single form-control"
                                    @error('warehouse') is-invalid @enderror">
                                    <option value="">Pilih Destination Warehouse</option>
                                    @foreach ($warehouses as $warehouse)
                                        <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                                    @endforeach
                                </select>
                                @error('to')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Notes:</strong>
                                <textarea id="notes" name="notes" rows="4" class="form-control"></textarea>
                                {{-- <textarea class="form-control" id="exampleFormControlTextarea1" rows="3"></textarea> --}}
                                {{-- @error('remark')
                                    <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                                @enderror --}}
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                {{-- <strong>Created By:</strong> --}}
                                <input type="hidden" name="gt_no"
                                    value="{{ $idxs->idx }}/GTR/{{ env('NO_PREFIX') }}/{{ $returnValueRoman }}/{{ $year }}"
                                    class="form-control" placeholder="pr_no">
                                <input type="hidden" name="idx_next" value="{{ $idxs->idx }}" class="form-control"
                                    placeholder="idx_next">
                                <input type="hidden" name="status" value="New" class="form-control"
                                    placeholder="status">
                                <input type="hidden" name="created_by" value="{{ Auth::id() }}" class="form-control"
                                    placeholder="created by">
                                @error('created_by')
                                    <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>



                    </div>

                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary ml-3">Submit</button>
                </div>
            </div>

        </form>
    @endsection
