@props(['boq', 'boqVerification', 'isMultipleApproval', 'boqArray'])

@php
    $revision = $boq->revision ?: 0;
    $qtyBefore = $boqArray ? $boqArray['qty_before'] : null;
@endphp

@if ($qtyBefore)
    @if ($isMultipleApproval)
        @if ($boq->approved && $boq->approved2)
            <span>
                <strong>{{ $boq->qty ? rtrim(rtrim(number_format($boq->qty, 2, ',', '.'), '0'), ',') : '' }}</strong>
            </span>
        @else
            <span>
                <strong>0</strong>
            </span>
        @endif
    @else
        @if (is_null($boq->approved))
            <span>
                <strong>{{ $boq->qty ? rtrim(rtrim(number_format($boq->qty, 2, ',', '.'), '0'), ',') : '' }}</strong>
            </span>
        @else
            <span>
                <strong>{{ $boq->qty ? rtrim(rtrim(number_format($boq->qty, 2, ',', '.'), '0'), ',') : '' }}</strong>
            </span>
        @endif
    @endif
@else
    <span>
        <strong>{{ $boq->qty ? rtrim(rtrim(number_format($boq->qty, 2, ',', '.'), '0'), ',') : '' }}</strong>
    </span>
@endif
