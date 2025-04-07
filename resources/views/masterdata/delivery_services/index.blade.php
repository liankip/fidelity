@extends('layouts.app')

@section('content')
    <div class="container mt-2">
        <div class="row">
            <div class="col-lg-12 margin-tb">
                <div class="pull-left">
                    <h2 class="primary-color-sne">Delivery Service</h2>
                </div>
            </div>
        </div>
        @if ($message = Session::get('success'))
            <div class="alert alert-success">
                <p>{{ $message }}</p>
            </div>
        @endif
        @can(\App\Permissions\Permission::CREATE_DELIVERY_SERVICE)
            <a class="btn btn-success mt-5" href="{{ route('delivery_services.create') }}">
                <i class="fa-solid fa-plus pe-2"></i>
                Create
            </a>
        @endcan
        <div class="card primary-box-shadow mt-2">
            <div class="card-body">

                <table class="table primary-box-shadow mt-2">
                    <tr class="thead-light">
                        <th style="text-align: center" class="border-top-left">No.</th>
                        <th class="text-center">Nama Jasa Pengiriman</th>
                        <th class="text-center">Ground</th>
                        <th class="text-center">Keterangan</th>
                        <th class="text-center border-top-right">Action</th>
                    </tr>
                    @php
                        $no = 1;
                    @endphp
                    @foreach ($delivery_services as $deliveryservice)
                        <tr>
                            <td style="text-align: center; width: 4%">{{ $no }}</td>
                            <td class="text-center">{{ $deliveryservice->name }}</td>
                            <td class="text-center">{{ $deliveryservice->ground }}</td>
                            <td class="text-center">{{ $deliveryservice->keterangan }}</td>
                            <td class="text-center">
                                @can(\App\Permissions\Permission::CREATE_DELIVERY_SERVICE)
                                    <form action="{{ route('delivery_services.destroy', $deliveryservice->id) }}"
                                        method="Post">
                                        <a class="btn btn-primary"
                                            href="{{ route('delivery_services.edit', $deliveryservice->id) }}">Edit</a>
                                    </form>
                                @endcan
                            </td>
                        </tr>
                        @php
                            $no++;
                        @endphp
                    @endforeach
                </table>
                {!! $delivery_services->links() !!}
            </div>
        </div>
    @endsection
