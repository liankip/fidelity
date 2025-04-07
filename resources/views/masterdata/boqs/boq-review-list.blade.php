@extends('layouts.app')

@section('content')
    <div class="mt-2" wire:ignore>
        <div class="row">
            <div class="col-lg-12 margin-tb">
                <div class="pull-left d-lg-flex justify-content-between">
                    <div class=" ">
                        <h2 class="text-black">Submitted BOQ - {{ $project->name }}</h2>
                    </div>
                </div>
                <hr>
                <x-common.notification-alert />
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <table class="table">
                    <thead class="thead-light">
                        <th class="text-center align-middle" width="5%">No</th>
                        <th class="text-center align-middle" width="15%">Created By</th>
                        <th class="text-center align-middle" width="15%">Total Items</th>
                        <th class="text-center align-middle" width="5%">Total Price</th>
                        <th class="text-center align-middle" width="10%">Action</th>
                    </thead>
                    <tbody>
                        @foreach ($submittedBOQs as $submitted)
                            <tr>
                                <td class="text-center align-middle">{{ $loop->iteration }}</td>
                                <td class="text-center align-middle">{{ $submitted->user->name }}</td>
                                <td class="text-center align-middle">{{ count($submitted->getJsonDataAsObjectArray()) }}
                                </td>
                                <td class="text-center align-middle">{{ rupiah_format($submitted->getTotalPrice()) }}</td>
                                <td class="text-center align-middle">
                                    <a href="{{ route('boq.review.detail', ['projectId' => $project->id, 'boqId' => $submitted->id]) }}"
                                        class="btn btn-sm btn-outline-primary">Review</a>
                                </td>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>
@endsection
