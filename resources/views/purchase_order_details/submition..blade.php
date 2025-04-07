@extends('layouts.app')

@section('content')

<div class="container mt-2">
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>{{ config('app.company', 'SNE') }} - ERP | Konfirmasi Barang PO Sampai</h2>
            </div>

        </div>
    </div>
    @if ($message = Session::get('success'))
    <div class="alert alert-success">
    <p>{{ $message }}</p>
    </div>
@endif
<div class="card">
    <div class="card-header"></div>
    <div class="card-body"></div>
    <div class="card-footer"></div>
</div>

@endsection
