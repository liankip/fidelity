@php use Carbon\Carbon; @endphp
@props([
    'data',
    'itemid',
    'boqdata',
    'boq',
    'boqVerification',
    'isMultipleApproval',
    'boqArray',
    'project_id',
    'task_number',
    'section',
    'select_section',
    'updated_at',
])

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

    $latest_data = App\Models\BOQSpreadsheet::where('project_id', $project_id)
        ->where('status', 'Finalized')
        ->when(!empty($task_number), function ($query) use ($task_number) {
            return $query->where('task_number', $task_number);
        })
        ->orderByDesc('id')
        ->get();

    $latest = [];
    foreach ($latest_data as $data) {
        $decodedData = json_decode($data->data, true);
        if (is_array($decodedData)) {
            $latest = array_merge($latest, $decodedData);
        }
    }

    $item_additional = 0;
    foreach ($latest as $data_latest) {
        if ($data_latest[0] == $boq->item_id) {
            $item_additional = $data_latest[3];
            break;
        }
    }

    $revision = $boq->revision ?: 0;
    $qtyBefore = $boqArray ? $boqArray['qty_before'] : null;

    $sec = $section ? $section->secction : 0;
    $checkSection = $sec == $select_section;

    $cutoffDate = Carbon::parse('2025-01-20');

    $updatedAtDate = Carbon::parse($boq->updated_at);

    $isNewProject = $updatedAtDate->greaterThanOrEqualTo($cutoffDate);
    $isOldProject = $updatedAtDate->lessThan($cutoffDate) && is_null($boq->approved_by_3);

    $additionalQty = $boq->qty - (floatval($boqdata->qty) - $item_additional);
    $formattedQty = $boq->qty ? rtrim(rtrim(number_format($additionalQty, 2, ',', '.'), '0'), ',') : '';
@endphp

@if ($qtyBefore)
    <div class="w-100 badge text-bg-info mb-1 text-white">
        <small>
            @if ($isMultipleApproval)
                @if ($boq->qty)
                    @if (number_format($boq->qty - (floatval($boqdata->qty) - $item_additional), 2, ',', '.') >= 0)
                        <div>
                            Penambahan:
                        </div>
                        @if (is_null($boq->approved) || is_null($boq->approved2))
                            <span>
                                <strong>{{ $boq->qty ? rtrim(rtrim(number_format($boq->qty - (floatval($boqdata->qty) - $item_additional), 2, ',', '.'), '0'), ',') : '' }}</strong>
                            </span>
                        @else
                            <span>
                                <strong>{{ $boq->qty ? rtrim(rtrim(number_format($boq->qty - (floatval($boqdata->qty) - $item_additional), 2, ',', '.'), '0'), ',') : '' }}</strong>
                            </span>
                        @endif
                    @endif
                @endif
            @else
                @if (is_null($boq->approved))
                    <span>
                        Total:
                        <strong>{{ $boq->qty ? rtrim(rtrim(number_format($boq->qty, 2, ',', '.'), '0'), ',') : '' }}</strong>
                    </span>
                @else
                    <span>
                        <strong>{{ $boq->qty ? rtrim(rtrim(number_format($boq->qty, 2, ',', '.'), '0'), ',') : '' }}</strong>
                    </span>
                @endif
            @endif
        </small>
    </div>
@else
    @if ($isOldProject)
        <div></div>
    @elseif ($isNewProject)
        <div class="w-100 badge text-bg-info mb-1 text-white">
            <small>
                <div>
                    Penambahan:
                </div>
                <strong>{{ $formattedQty }}</strong>
            </small>
        </div>
    @else
        @dump('Proyek tidak valid: Pastikan updated_at dan approved_by_3 sesuai')
    @endif
@endif
