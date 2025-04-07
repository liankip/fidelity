@extends('layouts.app')
@section('content')
    <div>
        <div class="container mt-2">
            <div class="row">
                <div class="col-lg-12 margin-tb">
                    <h4>
                        Vendor Items
                    </h4>
                    <hr>
                    <x-common.notification-alert/>

                    <div class="card mt-3">
                        <div class="card-body">
                            <table class="table" id="table">
                                <thead class="thead-light">
                                <tr>

                                    <th class="align-middle" style="width: 5%">#</th>
                                    <th class="align-middle">Vendor Name</th>
                                    <th class="align-middle">Item Name</th>
                                    <th class="align-middle">Price</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($items as $item)
                                    <tr>
                                        <td>
                                            {{ $loop->iteration }}
                                        </td>
                                        <td>
                                            <a href="{{route('vendors.show', $item->vendor_id)}}" target="_blank">
                                                {{ $item->vendor->name }}
                                            </a>
                                        </td>
                                        <td>
                                            {{ $item->item_name }}
                                        </td>
                                        <td>{{ rupiah_format($item->price) }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <script>
                $(document).ready(function () {
                    const dTable = new DataTable('#table', {
                        ordering: false,
                    });
                });
            </script>
        </div>
    </div>
@endsection

