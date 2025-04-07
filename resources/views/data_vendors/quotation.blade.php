@extends('layouts.app')
@section('content')
    <div>
        <div class="container mt-2">
            <div class="row">
                <div class="col-lg-12 margin-tb">
                    <h4>
                        Quotation - {{ $item->name }}
                    </h4>
                    <hr>
                    <x-common.notification-alert />

                    <div class="card mt-3">
                        <div class="card-body">
                            <table class="table" id="table">
                                <thead class="thead-light">
                                    <tr>

                                        <th class="align-middle" style="width: 5%">#</th>
                                        <th class="align-middle">Vendor Name</th>
                                        <th class="align-middle">Price</th>
                                        <th class="align-middle">Notes</th>
                                        <th class="align-middle">Vendor Contact</th>
                                        <th class="align-middle">
                                            Submission Date
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($quotations as $quotation)
                                        @php
                                            $supplier = $quotation->requestForQuotation?->supplier;
                                        @endphp
                                        <tr>
                                            <td>
                                                {{ $loop->iteration }}
                                            </td>
                                            <td>
                                                {{ $supplier?->name }}
                                            </td>
                                            <td>{{ rupiah_format($quotation->price) }}</td>
                                            <td>{{ $quotation->notes }}</td>
                                            <td>{{ $supplier?->phone }}</td>
                                            <td>
                                                {{ $quotation->updated_at->format('d M Y') }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <script>
                $(document).ready(function() {
                    const dTable = new DataTable('#table', {
                        ordering: false,
                    });
                });
            </script>
        </div>
    </div>
@endsection
