@extends('layouts.app')

@section('content')
    <div class="container mt-2">
        <div class="row">
            <div class="col-lg-12 margin-tb">
                <div class="pull-left mb-2">
                    <h2>Add Purchase Request Destination</h2>
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
        <form action="{{ route('purchase_requests.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="card">
                <div class="card-header">
                    <a class="btn btn-primary" href="{{ route('purchase_requests.index') }}"> Back</a>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>PR No:</strong>
                                <input readonly type="text" name="pr_no" class="form-control" placeholder="pr_no"
                                    value="@foreach ($idxs as $idxs){{ ++$idxs->idx }} @endforeach/PR/{{ env('NO_PREFIX') }}/{{ $returnValueRoman }}/{{ $year }}">
                                {{-- <input hidden type="text" name="pr_no" class="form-control" placeholder="pr_no"
                                value="@foreach ($idxs as $idxs){{ ++$idxs->idx }} @endforeach/PR/{{ env('NO_PREFIX') }}/{{ $returnValueRoman }}/{{ $year }}"> --}}
                                @error('pr_no')
                                    <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>
                                    <label for="pr_type">PR Type :</label>
                                </strong>

                                <select id="pr_type" name="pr_type" class="form-control" placeholder="PR Type">
                                    <option value="">Pilih Type PR</option>
                                    <option value="Barang">Barang</option>
                                    <option value="Jasa">Jasa</option>
                                    <option value="Sewa Mesin">Sewa Mesin</option>
                                    {{-- <option value="audi">Audi</option> --}}
                                </select>
                                @error('pr_type')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Project:</strong> &nbsp; <a href="{{ url('projects/create', []) }}"
                                    target="__blank">Tambah project </a>
                                <select name="project_id" id="project_id" class="js-example-basic-single form-control"
                                    @error('project') is-invalid @enderror">
                                    <option value="">Pilih Project</option>
                                    @foreach ($projects as $project)
                                        <option value="{{ $project->id }}">{{ $project->name }} |
                                            {{ $project->company_name }}</option>
                                    @endforeach
                                </select>
                                @error('project_id')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Requester:</strong>
                                <input type="text" name="requester" class="form-control" value=""
                                    placeholder="Yang meminta">
                                @error('requester')
                                    <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Bagian:</strong>
                                <input type="text" name="partof" class="form-control" value=""
                                    placeholder="Bagian mana">
                                @error('partof')
                                    <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Warehouse:</strong>
                                <select name="warehouse_id" id="warehouse_id" class="js-example-basic-single form-control"
                                    @error('warehouse') is-invalid @enderror">
                                    <option value="">Pilih Warehouse</option>
                                    @foreach ($warehouses as $warehouse)
                                        <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div> --}}

                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Notes:</strong>
                                <textarea id="remark" name="remark" rows="4" class="form-control"></textarea>
                                {{-- <textarea class="form-control" id="exampleFormControlTextarea1" rows="3"></textarea> --}}
                                {{-- @error('remark')
                                    <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                                @enderror --}}
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                {{-- <strong>Created By:</strong> --}}
                                <input type="hidden" name="pr_no"
                                    value="{{ $idxs->idx }}/PR/{{ env('NO_PREFIX') }}/{{ $returnValueRoman }}/{{ $year }}"
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
    </div>
@endsection
