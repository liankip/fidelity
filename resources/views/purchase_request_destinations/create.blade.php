{{-- <!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Add purchase_request_destination Form - {{ config('app.company', 'SNE') }} - ERP</title>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" >
</head>
<body> --}}
@extends('layouts.app')

@section('content')
<div class="container mt-2">
<div class="row">
<div class="col-lg-12 margin-tb">
<div class="pull-left mb-2">
<h2>Add New purchase_request_destination</h2>
</div>
<div class="pull-right">
<a class="btn btn-primary" href="{{ route('purchase_request_destinations.index') }}"> Back</a>
</div>
</div>
</div>
@if(session('status'))
<div class="alert alert-success mb-1 mt-1">
{{ session('status') }}
</div>
@endif
<form action="{{ route('purchase_request_destinations.store') }}" method="POST" enctype="multipart/form-data">
@csrf
<div class="card">
    <div class="card-header">PR Destination</div>
    <div class="card-body">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>PR No:</strong>
                <input readonly type="text" name="pr_no" class="form-control" placeholder="pr_no"
                    value="@foreach ($idxs as $idxs){{ ++$idxs->idx }} @endforeach/PR/{{ env('NO_PREFIX') }}/{{ $returnValueRoman }}/{{ $year }}">
                <input hidden type="text" name="pr_no" class="form-control" placeholder="pr_no"
                    value="@foreach ($idxs as $idxs){{ ++$idxs->idx }} @endforeach/PR/{{ env('NO_PREFIX') }}/{{ $returnValueRoman }}/{{ $year }}">
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
                {{-- <input type="submit"> --}}
                {{-- <strong>PR Type:</strong>
            <input type="text" name="pr_type" class="form-control" placeholder="PR Type">
            @error('pr_type')
            <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
            @enderror --}}
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>
                    <label for="pr_type">Project :</label>
                </strong>

                <select name="project_id" id="project_id"
                class="js-example-basic-single form-control  @error('project') is-invalid @enderror">
                    <option value="">Pilih Project</option>
                    @foreach ($projects as $project)
                        <option value="{{ $project->id }}">{{ $project->name }}</option>
                    @endforeach
                </select>

                {{-- Dropdown manual --}}

                {{-- <select id="project_id" name="project_id" class="form-control" placeholder="Project ID">
              <option value="">Choose Project</option>
              <option value="1">Project Name 1</option>
              <option value="2">Project Name 2</option>
              <option value="3">Project Name 3</option>
              <option value="audi">Audi</option>
            </select> --}}


                {{-- <strong>Project ID:</strong>
            <input type="text" name="project_id" class="form-control" placeholder="Project ID">
            @error('project_id')
            <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
            @enderror --}}
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>
                    <label for="warehouse_id">Warehouse :</label>
                </strong>

                <select name="warehouse_id" id="warehouse_id"
                class="js-example-basic-single form-control  @error('warehouse') is-invalid @enderror">
                    <option value="">Pilih Warehouse</option>
                    @foreach ($warehouses as $warehouse)
                        <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                    @endforeach
                </select>


                {{-- <strong>Warehouse ID:</strong>
            <input type="text" name="warehouse_id" class="form-control" placeholder="Warehouse ID">
            @error('warehouse_id')
            <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
            @enderror --}}
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Notes:</strong>
                <textarea id="notes" name="notes" rows="4" cols="50" name="notes" class="form-control"
                    placeholder="Notes">

            </textarea>
                {{-- <input type="text" name="remark" class="form-control" placeholder="Notes">
            @error('remark')
            <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
            @enderror --}}
            </div>
        </div>

    </div>
    <div class="card-footer">
        <button type="submit" class="btn btn-primary ml-3">Submit</button>


    </div>

</div>
{{-- <div class="row">





</div> --}}
</form>
@endsection
{{-- </body>
</html> --}}
