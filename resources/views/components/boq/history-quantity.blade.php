@props(['data', 'itemid', 'boqdata', 'project_id', 'task_number'])

<small>
    @php
        $jsonData = $data
            ->pluck('data')
            ->map(function ($jsonString) {
                return json_decode($jsonString, true);
            })
            ->toArray();

        $latestData = null;
        $latestQuantity = null;

        foreach ($jsonData as $itemData) {
            $currentItemID = $itemData[0][0];

            if ($currentItemID == $itemid) {
                foreach ($data as $item) {
                    $decodedData = json_decode($item['data'], true);

                    foreach ($decodedData as $decoded) {
                        if (isset($decoded[0]) && $decoded[0] == $currentItemID) {
                            if ($latestData === null || $item['updated_at'] > $latestData['updated_at']) {
                                $latestData = $item;
                                $latestQuantity = $decoded[3];
                                break 2;
                            }
                        }
                    }
                }
            }
        }

        if ($task_number) {
            $latest_data = App\Models\BOQSpreadsheet::where('project_id', $project_id)
                ->where('status', 'Finalized')
                ->where('task_number', $task_number)
                ->orderByDesc('id')
                ->first();
        } else {
            $latest_data = App\Models\BOQSpreadsheet::where('project_id', $project_id)
                ->where('status', 'Finalized')
                ->orderByDesc('id')
                ->first();
        }

        if ($latest_data) {
            $latest = $latest_data->toArray();
            $latest = json_decode($latest['data'], true);
        } else {
            $latest = [];
        }

        $item_additional = null;
        foreach ($latest as $data_latest) {
            if ($data_latest[0] == $boqdata->item_id) {
                $item_additional = $data_latest[3];
                break;
            }
        }

        if ($task_number) {
            $prDetailData = App\Models\PurchaseRequestDetail::whereHas('purchaseRequest', function ($query) use (
                $project_id,
                $task_number,
            ) {
                $query->where('project_id', $project_id)->where('partof', $task_number);
            })
                ->where('item_id', $boqdata->item_id)
                ->where('status', '!=', 'Rejected')
                ->get();
        } else {
            $prDetailData = App\Models\PurchaseRequestDetail::whereHas('purchaseRequest', function ($query) use (
                $project_id,
            ) {
                $query->where('project_id', $project_id);
            })
                ->where('item_id', $boqdata->item_id)
                ->where('status', '!=', 'Rejected')
                ->get();
        }

        $totalPRDetail = $prDetailData->sum(callback: 'qty');
    @endphp
    <div>
        @if ($item_additional)
            @if ($boqdata->rejected_by)
                <strong>{{ rtrim(rtrim(number_format($totalPRDetail, 2, ',', '.'), '0'), ',') }} </strong>
            @elseif($boqdata->approved_by && $boqdata->approved_by_2)
                <strong>{{ rtrim(rtrim(number_format($totalPRDetail, 2, ',', '.'), '0'), ',') }} </strong>
            @elseif($boqdata->approved_by && $boqdata->approved_by_2 && $boqdata->approved_by_3)
                <strong>{{ rtrim(rtrim(number_format($totalPRDetail, 2, ',', '.'), '0'), ',') }} </strong>
            @else
                <strong>{{ rtrim(rtrim(number_format($totalPRDetail, 2, ',', '.'), '0'), ',') }} </strong>
            @endif
        @else
            <strong>{{ rtrim(rtrim(number_format(floatval($totalPRDetail), 2, ',', '.'), '0'), ',') }}</strong>
        @endif
    </div>
</small>
