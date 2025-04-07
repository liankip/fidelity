@props(['boq', 'setting', 'max_version'])

@if ($setting->multiple_k3_approval)
    @if ($boq->rejected_by != null && $boq->rejected_by != 0)
        <span class="text-danger"><strong>Rejected by {{ $boq->rejected->name }}</strong></span>
    @else
        @if ($boq->approved && $boq->approved2)
            <div>
                <span class="text-success">
                    <strong>Approved by {{ $boq->approved->name }}</strong>
                </span>
                @if ($boq->date_approved)
                    - <em>{{ date('d F Y', strtotime($boq->date_approved)) }}</em>
                @endif
            </div>
            <div>
                <span class="text-success">
                    <strong>Approved by (K3) {{ $boq->approved2->name }}</strong>
                </span>
                @if ($boq->date_approved_2)
                    - <em>{{ date('d F Y', strtotime($boq->date_approved_2)) }}</em>
                @endif
            </div>
        @else
            @if ($boq->approved)
                <div>
                    <span class="text-success">
                        <strong>Approved by {{ $boq->approved->name }}</strong>
                    </span>
                    @if ($boq->date_approved)
                        - <em>{{ date('d F Y', strtotime($boq->date_approved)) }}</em>
                    @endif
                </div>
                @if ($boq->status != 'Finalized')
                    <div>
                        <span>
                            <strong>Waiting for K3 approval</strong>
                        </span>
                    </div>
                @endif
            @elseif($boq->approved2)
                <div>
                    <span class="text-success">
                        <strong>Approved by (K3) {{ $boq->approved2->name }}</strong>
                    </span>
                    @if ($boq->date_approved_2)
                        - <em>{{ date('d F Y', strtotime($boq->date_approved_2)) }}</em>
                    @endif
                </div>
                @if ($boq->status != 'Finalized')
                    <div>
                        <span>
                            <strong>Waiting for Manager approval</strong>
                        </span>
                    </div>
                @endif
            @else
                @if ($boq->status != 'Finalized')
                    <div>
                        <span>
                            <strong>Waiting for Manager and K3 approval</strong>
                        </span>
                    </div>
                @else
                    <div>
                        <span>
                            <strong>Approved</strong>
                        </span>
                    </div>
                @endif
            @endif
        @endif
    @endif
@else
    @if ($boq->rejected_by != null && $boq->rejected_by != 0)
        <span class="text-danger">
            <strong>Rejected by {{ $boq->rejected->name }}</strong>
        </span>
    @else
        @if ($boq->approved)
            <div>
                <span class="text-success">
                    <strong>Approved by {{ $boq->approved->name }}</strong>
                </span>
            </div>
        @else
            <div>
                <span>
                    <strong>Waiting for approval</strong>
                </span>
            </div>
        @endif
    @endif
@endif
