@extends('layouts.app')

@section('content')
    <div>
        <div class="container mt-2">
            <div class="row">
                <div class="col-lg-12 mb-5">
                    <a class="btn btn-danger" href="{{ route('vendors.price-quotation') }}">
                        <i class="fas fa-arrow-left"></i>
                        Back
                    </a>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12 margin-tb">
                    <h4>
                        {{ $rfq->supplier->name }}
                    </h4>
                    <hr>
                    <x-common.notification-alert />

                    <div class="card mt-3">
                        <div class="card-body">
                            <div class="d-flex mb-3 ">
                                <a class="btn btn-primary" href="{{ route('request-for-quotation', $rfq->id) }}"
                                    class="btn btn-primary" target="_blank">
                                    <i class="fas fa-external-link"></i>
                                    Visit Link
                                </a>

                            </div>
                            <table class="table" id="table">
                                <thead class="thead-light">
                                    <tr>
                                        <th class="align-middle" style="width: 5%">#</th>
                                        <th class="align-middle">Item Name</th>
                                        <th class="align-middle">Unit</th>
                                        <th class="align-middle">Price</th>
                                        <th class="align-middle">Notes</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($rfq->itemDetail as $rfq_item)
                                        <tr>
                                            <td>
                                                {{ $loop->iteration }}
                                            </td>
                                            <td>
                                                {{ $rfq_item->item->name }}
                                            </td>
                                            <td>
                                                {{ $rfq_item->unit }}
                                            </td>
                                            <td>
                                                @if ($rfq_item->price == null)
                                                    @if ($rfq->expired_at->isPast())
                                                        <span class="badge bg-danger">Expired</span>
                                                    @else
                                                        <span class="badge bg-warning">Waiting</span>
                                                    @endif
                                                @else
                                                    {{ rupiah_format($rfq_item->price) }}
                                                @endif
                                            </td>
                                            <td>
                                                {{ $rfq_item->notes }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
