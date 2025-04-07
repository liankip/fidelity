@php
    use App\Models\Inventory;
    use App\Models\PurchaseRequest;
    use App\Models\Task;
    use Carbon\Carbon;
@endphp
<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Print Purchase Request</title>
    <style>
        body {
            font-size: 10pt;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }

        th {
            background-color: #f2f2f2;
        }

        @media print {
            body {
                -webkit-print-color-adjust: exact;
            }

            thead {
                display: table-row-group
            }

            table,
            #signature {
                page-break-inside: auto
            }

            tr,
            #signature {
                page-break-inside: avoid;
                page-break-after: auto
            }
        }
    </style>
</head>

<body onload="window.print()">
    <h1>Monitoring {{ $task->task_number }}</h1>
    <div class="card p-4">
        <div class="row">
            <div class="col-md-8">
                <p><strong>PROJECT:</strong> {{ $task->project->name }}</p>
                <p><strong>Task Name: {{ $task->task }}</strong></p>
            </div>
            <div class="col-md-4 text-start">
                <p><strong>Start: {{ $task->start_date }}</strong></p>
                <p><strong>Finish: {{ $task->finish_date }}</strong></p>
            </div>
        </div>
    </div>
    <hr>
    @forelse ($prData as $prNo => $pr)
        <div class="card p-4" wire:key="pr-{{ $prNo }}">
            @foreach ($pr as $prItem)
                @php
                    $purchaseRequest = $prItem;
                @endphp
            @endforeach
            @if (count($purchaseRequest->prdetail) != 0)
                <h3>PR No: {{ $prNo }}</h3>
            @endif

            @if (count($purchaseRequest->prdetail) != 0)
                <table class="table table-bordered text-center table-responsive w-100">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 20%">Item</th>
                            <th colspan="2">Requested</th>
                            <th style="width: 20%">PO</th>
                            <th>Tanggal Sampai</th>
                            <th colspan="2">Actual PO</th>
                            <th>Site Check</th>
                        </tr>
                        <tr>
                            <th></th>
                            <th>Qty</th>
                            <th>unit</th>
                            <th></th>
                            <th></th>
                            <th>Qty</th>
                            <th>unit</th>
                            <th></th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($pr as $prItem)
                            @foreach ($prItem->prdetail as $prDetail)
                                <tr>
                                    <td>{{ $prDetail->item->name }}</td>
                                    <td>{{ rtrim(rtrim(number_format($prDetail->qty, 2, ',', '.'), '0'), ',') }}</td>
                                    <td>{{ $prDetail->item->unit }}</td>
                                    <td class="align-middle">
                                        @foreach (collect($prDetail->podetail) as $podetail)
                                            @if (isset($podetail->po) && $podetail->po->po_no)
                                                <span
                                                    class="badge badge-success mb-2">{{ $podetail->po->po_no }}</span>
                                            @else
                                                <span class="badge badge-danger mt-2">-</span>
                                            @endif
                                        @endforeach
                                    </td>
                                    <td>

                                    </td>
                                    <td class="align-middle">

                                    </td>
                                    <td class="align-middle">
                                        @php
                                            $allPoDetails = collect($prDetail->podetail)->every(function ($detail) {
                                                return isset($detail->po) &&
                                                    in_array($detail->po->status, ['Approved', 'Paid']);
                                            });
                                        @endphp

                                        @if ($allPoDetails === true)
                                            {{ $prDetail->item->unit }}
                                        @endif
                                    </td>
                                    <td>

                                    </td>
                                </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    @empty
        <p class="text-center text-danger bg-white p-3 rounded fw-bold">No PR data found</p>
    @endforelse
</body>

</html>
