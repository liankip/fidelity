@extends('layouts.app')

@section('content')
    <div>
        <div class="container mt-2">
            <div class="row">
                <div class="col-lg-12 margin-tb">
                    <h4>
                        Request For Quotation
                    </h4>
                    <hr>
                    <x-common.notification-alert />

                    <div class="card mt-3">
                        <div class="card-body">
                            <table class="table" id="table">
                                <thead class="thead-light">
                                    <tr>
                                        <th class="align-middle" style="width: 5%">#</th>
                                        <th class="align-middle">PR No</th>
                                        <th class="align-middle">Supplier</th>
                                        <th class="align-middle">Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($groupedRfqs as $group)
                                        <tr>
                                            <td class="align-top">
                                                {{ $loop->iteration }}
                                            </td>
                                            <td class="align-top">
                                                <a class=""
                                                    href="{{ url('/po_details', ['id' => $group['purchase_request']->id]) }}"
                                                    target="__blank">
                                                    {{ $group['purchase_request']->pr_no }}
                                                </a>&nbsp;

                                            </td>
                                            <td>
                                                @foreach ($group['rfqs'] as $rfq)
                                                    <ul>
                                                        <li>
                                                            <a href="{{ route('vendors.price-quotation.show', $rfq->id) }}"
                                                                target="_blank">
                                                                {{ $rfq->supplier->name }}
                                                            </a>
                                                            @if ($rfq->is_submitted == 0)
                                                                @if ($rfq->expired_at->isPast())
                                                                    <span class="badge bg-danger">Expired</span>
                                                                @else
                                                                    <span class="badge bg-warning">Waiting</span>
                                                                @endif
                                                            @else
                                                                <span class="badge bg-success">Submitted</span>
                                                            @endif
                                                        </li>
                                                    </ul>
                                                @endforeach
                                            </td>
                                            <td class="align-top">
                                                {{ date('d F Y', strtotime($rfq->created_at)) }}
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
