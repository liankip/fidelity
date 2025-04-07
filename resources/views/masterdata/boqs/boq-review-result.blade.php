@extends('layouts.app')

@section('content')
    <div class="mt-2" wire:ignore>
        <div class="row">
            <div class="col-lg-12 mb-5">
                <a class="btn btn-danger" href="{{ route('boq.review.index', $project->id) }}">
                    <i class="fas fa-arrow-left"></i>
                    Back
                </a>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 margin-tb">
                <div class="pull-left d-lg-flex justify-content-between">
                    <div class=" ">
                        <h2 class="text-black">BOQ - {{ $project->name }}</h2>
                        <p>
                            <strong>
                                Reviewed By: {{ $review->reviewedBy->name }} <br>
                            </strong>
                        </p>
                    </div>
                </div>
                <hr>
                <x-common.notification-alert />
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                @if (!$boqSpreadsheet->is_closed)
                    <div class="d-flex justify-content-end mb-3">
                        @if (auth()->user()->id === $boqSpreadsheet->user_id)
                            <a href="{{ route('boq.create-boq', [$project->id, 'id' => $boqSpreadsheet->id]) }}"
                                class="btn btn-success" target="_blank">Edit</a>
                        @endif

                    </div>
                @endif
                <table class="table">
                    <thead class="thead-light">
                        <th class="text-center align-middle" width="5%">No</th>
                        <th class="text-center align-middle" width="15%">Item Name</th>
                        <th class="text-center align-middle" width="5%">Unit</th>
                        <th class="text-center align-middle" width="10%">Price Estimation</th>
                        <th class="text-center align-middle" width="10%">Quantity</th>
                        <th class="text-center align-middle" width="10%">Shipping Cost Estimation</th>
                    </thead>
                    <tbody>
                        @foreach ($results as $item)
                            <tr @if (is_null($item['item_name']['reviewed'])) class="text-danger" @endif>
                                <td class="text-center align-middle">{{ $loop->iteration }}</td>
                                @foreach ($item as $key => $val)
                                    <td class="align-middle text-center">
                                        @if ($key === 'price')
                                            <p>
                                                {{ rupiah_format($item[$key]['current']) }}
                                            </p>
                                            @if ($item[$key]['reviewed'])
                                                @if ($item[$key]['current'] != $item[$key]['reviewed'])
                                                    <p class="text-primary">Revision:
                                                        {{ rupiah_format($item[$key]['reviewed']) }}
                                                    </p>
                                                @endif
                                            @endif
                                        @else
                                            <p>{{ $item[$key]['current'] }}</p>
                                            @if ($item[$key]['reviewed'])
                                                @if ($item[$key]['current'] != $item[$key]['reviewed'])
                                                    <p class="text-primary">Revision: {{ $item[$key]['reviewed'] }}</p>
                                                @endif
                                            @endif
                                        @endif

                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="mt-1">
                    <p class="fw-semibold"> Notes : <br>
                        <span class="text-danger">Text Merah</span> : Barang dihapus <br>
                    </p>
                </div>
            </div>
        </div>


    </div>
@endsection
