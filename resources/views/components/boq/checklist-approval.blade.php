@php
    use Carbon\Carbon;
@endphp

@props(['max_version', 'boq', 'setting', 'boq_verification', 'task' => null])

@php
    $currentUserMatchesApproved = optional($boq->approved)->id == auth()->user()->id;
    $currentUserMatchesApproved2 = optional($boq->approved2)->id == auth()->user()->id;
    $currentUserMatchesApproved3 = optional($boq->approved3)->id == auth()->user()->id;

    if ($task != null) {
        $isTaskConsumables =
            $task->task == 'Consumables' || $task->section == 'Consumables' || $task->is_consumables == 1;
        $isTaskIndent = $task->task == 'Indent';
    } else {
        $isTaskConsumables = true;
        $isTaskIndent = true;
    }
    dd($isTaskConsumables, $isTaskIndent);

    $canApprove = auth()->user()->can('approve-boq');
    $hasTopLevelAccess = auth()->user()->hasTopLevelAccess();
    $isApprovedBy1 = is_null($boq->approved_by);
    $isApprovedBy2 = is_null($boq->approved_by_2);
    $isApprovedBy3 = is_null($boq->approved_by_3);
    $canApproveOnlyOne = $canApprove && !$hasTopLevelAccess;

    $updatedAt = $boq->updated_at ? Carbon::parse($boq->updated_at)->format('Y-m-d') : null;
    $allApproved = !$isApprovedBy1 && !$isApprovedBy2 && !$isApprovedBy3;

    $shouldShowCheckbox = function () use ($boq, $boq_verification, $currentUserMatchesApproved, $currentUserMatchesApproved2, $currentUserMatchesApproved3, $isTaskConsumables, $isTaskIndent, $allApproved, $updatedAt, $canApprove, $hasTopLevelAccess) {
        if ($isTaskConsumables || $isTaskIndent) {
            return !$currentUserMatchesApproved && !$currentUserMatchesApproved2 && !$currentUserMatchesApproved3 && $boq_verification && $boq->rejected_by == null && !$allApproved;
        } elseif ($boq->section == 0) {
            return !$currentUserMatchesApproved && !$currentUserMatchesApproved2 && !$currentUserMatchesApproved3 && $boq_verification && $boq->rejected_by == null && $updatedAt >= '2025-01-20';
        } elseif ($boq->section > 0) {
            $approvedByIds = [$boq->approved_by, $boq->approved_by_2, $boq->approved_by_3];
            $isAlreadyApproved = in_array(auth()->user()->id, $approvedByIds);

            return !$isAlreadyApproved || $canApprove || $hasTopLevelAccess;
        }

        return false;
    };
@endphp

@if ($setting->multiple_approval)
    @if ($shouldShowCheckbox())
        <td class="text-center">
            <input type="checkbox" value="{{ $boq->id }}" class="item">
        </td>
    @else
        <td class="text-center"></td>
    @endif
@else
    @if ($max_version == (int) $boq->revision && empty($boq->approved) && $boq_verification && $boq->rejected_by == null)
        <td class="text-center">
            <input type="checkbox" value="{{ $boq->id }}" class="item">
        </td>
    @else
        <td class="text-center"></td>
    @endif
@endif
