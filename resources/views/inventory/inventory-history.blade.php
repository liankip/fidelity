@extends('layouts.app')

@section('content')
    <div>
        <x-common.back :to="route('inventory.index')" />
        <div class="d-flex justify-content-between align-items-center">
            <h2>
                Inventory History
            </h2>
        </div>
        <hr>
        @foreach (['danger', 'warning', 'success', 'info'] as $key)
            @if (Session::has($key))
                <div class="alert alert-{{ $key }} alert-dismissible fade show mb-1 mt-1" role="alert">
                    {{ Session::get($key) }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                    </button>
                </div>
            @endif
        @endforeach
        <h2 class="mt-10">
            {{ $inventory->item->name }}
        </h2>
        <div class="card">
            <div class="card-body">
                <x-common.table id="inventoryTable">
                    <thead>
                        <tr>
                            <th style="width: 5%">No</th>
                            <th>Date</th>
                            <th>Quantity</th>
                            <th>Action By</th>
                            <th>Notes</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($histories as $history)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $history->created_at }}</td>
                                <td>
                                    @if ($history->type === 'IN')
                                        <span class="text-success">+{{ $history->stock_change }}</span>
                                    @elseif($history->type === 'OUT')
                                        <span class="text-danger">-{{ $history->stock_change }}</span>
                                    @endif
                                </td>
                                <td>{{ $history->user->name }}</td>
                                <td>{{ $history->notes }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </x-common.table>
            </div>
        </div>
    </div>
@endsection
