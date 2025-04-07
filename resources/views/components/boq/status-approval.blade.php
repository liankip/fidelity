@php use Carbon\Carbon; @endphp
@props(['boq', 'setting', 'max_version', 'task' => null])

@if ($setting->multiple_approval)
    @if ($boq->rejected_by != null && $boq->rejected_by != 0)
        <div class="text-sm text-danger">
            <strong>
                Rejected:
                <div>
                    {{ $boq->rejected->name }}
                </div>
            </strong>
        </div>
    @else
        @php
            if ($task != null) {
                $isTaskConsumables =
                    $task->task == 'Consumables' || $task->section == 'Consumables' || $task->is_consumables == 1;
                $isTaskIndent = $task->task == 'Indent';
            } else {
                $isTaskConsumables = true;
            }

            $isSectionGreaterThanZero = $boq->section > 0;
            $updatedAt = $boq->updated_at ? Carbon::parse($boq->updated_at)->format('Y-m-d') : null;
        @endphp

        @if ($isTaskConsumables || $isTaskIndent)
            @if ($updatedAt < '2025-01-20')
                @if ($boq->approved)
                    <div class="text-sm">
                        <strong class="text-success">
                            <div>Approved:</div>
                            <div>
                                {{ $boq->approved->name }}
                            </div>
                        </strong>
                        @if ($boq->date_approved)
                            <div>
                                <em>{{ date('d F Y', strtotime($boq->date_approved)) }}</em>
                            </div>
                        @endif
                    </div>
                    <hr>
                    <div class="text-sm mt-3">
                        @if ($boq->approved2)
                            <strong class="text-success">
                                <div>Approved:</div>
                                <div>
                                    {{ $boq->approved2->name }}
                                </div>
                            </strong>
                            @if ($boq->date_approved_2)
                                <div>
                                    <em>{{ date('d F Y', strtotime($boq->date_approved_2)) }}</em>
                                </div>
                            @endif
                        @else
                            <span><strong>Waiting for second approval</strong></span>
                        @endif
                    </div>
                    @if ($updatedAt >= '2025-01-15')
                        <hr>
                        <div class="text-sm mt-3">
                            @if ($boq->approved3)
                                <strong class="text-success">
                                    <div>Approved:</div>
                                    <div>
                                        {{ $boq->approved3->name }}
                                    </div>
                                </strong>
                                @if ($boq->date_approved_3)
                                    <div>
                                        <em>{{ date('d F Y', strtotime($boq->date_approved_3)) }}</em>
                                    </div>
                                @endif
                            @else
                                <span><strong>Waiting for third approval</strong></span>
                            @endif
                        </div>
                    @endif
                @else
                    <p class="text-sm">
                        <span><strong>Waiting for approval</strong></span>
                    </p>
                @endif
            @elseif ($updatedAt >= '2025-01-20')
                @hasrole('super-admin')
                    <div class="text-sm">
                        @if ($boq->approved)
                            <strong class="text-success">
                                <div>Approved:</div>
                                <div>
                                    {{ $boq->approved->name }}
                                </div>
                            </strong>
                            @if ($boq->date_approved)
                                <div>
                                    <em>{{ date('d F Y', strtotime($boq->date_approved)) }}</em>
                                </div>
                            @endif
                        @else
                            <span><strong>Waiting for First approval</strong></span>
                        @endif
                    </div>
                    <hr>
                    <div class="text-sm mt-3">
                        @if ($boq->approved2)
                            <strong class="text-success">
                                <div>Approved:</div>
                                <div>
                                    {{ $boq->approved2->name }}
                                </div>
                            </strong>
                            @if ($boq->date_approved_2)
                                <div>
                                    <em>{{ date('d F Y', strtotime($boq->date_approved_2)) }}</em>
                                </div>
                            @endif
                        @else
                            <span><strong>Waiting for second approval</strong></span>
                        @endif
                    </div>
                    @if ($updatedAt >= '2025-01-15')
                        <hr>
                        <div class="text-sm mt-3">
                            @if ($boq->approved3)
                                <strong class="text-success">
                                    <div>Approved:</div>
                                    <div>
                                        {{ $boq->approved3->name }}
                                    </div>
                                </strong>
                                @if ($boq->date_approved_3)
                                    <div>
                                        <em>{{ date('d F Y', strtotime($boq->date_approved_3)) }}</em>
                                    </div>
                                @endif
                            @else
                                <span><strong>Waiting for third approval</strong></span>
                            @endif
                        </div>
                    @endif
                @endhasrole

                @if (auth()->user()->hasAnyRole('top-manager|manager|adminlapangan') && auth()->user())

                    @php
                        $approvals = [];

                        if ($boq->approved && $boq->approved_by == auth()->user()->id) {
                            $approvals[] = $boq->approved->name;
                        }
                        if ($boq->approved2 && $boq->approved_by_2 == auth()->user()->id) {
                            $approvals[] = $boq->approved2->name;
                        }
                        if ($boq->approved3 && $boq->approved_by_3 == auth()->user()->id) {
                            $approvals[] = $boq->approved3->name;
                        }
                    @endphp

                    @if ($boq->approved && $boq->approved2 && $boq->approved3)
                        <div class="text-sm">
                            <div><strong class="text-success">Approved: {{ $boq->approved->name }}</strong></div>
                            <div>
                                <em>{{ date('d F Y', strtotime($boq->date_approved)) }}</em>
                            </div>
                        </div>
                        <hr>
                        <div class="text-sm mt-3">
                            <div><strong class="text-success">Approved: {{ $boq->approved2->name }}</strong></div>
                            <div>
                                <em>{{ date('d F Y', strtotime($boq->date_approved_2)) }}</em>
                            </div>
                        </div>
                        <hr>
                        <div class="text-sm mt-3">
                            <div><strong class="text-success">Approved: {{ $boq->approved3->name }}</strong></div>
                            <div>
                                <em>{{ date('d F Y', strtotime($boq->date_approved_3)) }}</em>
                            </div>
                        </div>
                    @elseif(count($approvals) > 0)
                        <div>
                            <strong class="text-sm text-success">Approved: {{ implode(', ', $approvals) }}</strong>
                        </div>
                    @else
                        <span><strong>Waiting for approval</strong></span>
                    @endif
                @endif
            @endif
        @elseif ($boq->approved || $boq->approved2 || $boq->approved3 || $isSectionGreaterThanZero)
            @if ($updatedAt < '2025-01-20')
                <div class="text-sm">
                    @if ($boq->approved)
                        <strong class="text-success">
                            <div>Approved:</div>
                            <div>
                                {{ $boq->approved->name }}
                            </div>
                        </strong>
                        @if ($boq->date_approved)
                            <div>
                                <em>{{ date('d F Y', strtotime($boq->date_approved)) }}</em>
                            </div>
                        @endif
                    @else
                        <span><strong>Waiting for First approval</strong></span>
                    @endif
                </div>
                <hr>
                <div class="text-sm mt-3">
                    @if ($boq->approved2)
                        <strong class="text-success">
                            <div>Approved:</div>
                            <div>
                                {{ $boq->approved2->name }}
                            </div>
                        </strong>
                        @if ($boq->date_approved_2)
                            <div>
                                <em>{{ date('d F Y', strtotime($boq->date_approved_2)) }}</em>
                            </div>
                        @endif
                    @else
                        <span><strong>Waiting for second approval</strong></span>
                    @endif
                </div>
                @if ($updatedAt >= '2025-01-15')
                    <hr>
                    <div class="text-sm mt-3">
                        @if ($boq->approved3)
                            <strong class="text-success">
                                <div>Approved:</div>
                                <div>
                                    {{ $boq->approved3->name }}
                                </div>
                            </strong>
                            @if ($boq->date_approved_3)
                                <div>
                                    <em>{{ date('d F Y', strtotime($boq->date_approved_3)) }}</em>
                                </div>
                            @endif
                        @else
                            <span><strong>Waiting for third approval</strong></span>
                        @endif
                    </div>
                @endif
            @elseif ($updatedAt >= '2025-01-20')
                @hasrole('super-admin')
                    <div class="text-sm">
                        @if ($boq->approved)
                            <strong class="text-success">
                                <div>Approved:</div>
                                <div>
                                    {{ $boq->approved->name }}
                                </div>
                            </strong>
                            @if ($boq->date_approved)
                                <div>
                                    <em>{{ date('d F Y', strtotime($boq->date_approved)) }}</em>
                                </div>
                            @endif
                        @else
                            <span><strong>Waiting for First approval</strong></span>
                        @endif
                    </div>
                    <hr>
                    <div class="text-sm mt-3">
                        @if ($boq->approved2)
                            <strong class="text-success">
                                <div>Approved:</div>
                                <div>
                                    {{ $boq->approved2->name }}
                                </div>
                            </strong>
                            @if ($boq->date_approved_2)
                                <div>
                                    <em>{{ date('d F Y', strtotime($boq->date_approved_2)) }}</em>
                                </div>
                            @endif
                        @else
                            <span><strong>Waiting for second approval</strong></span>
                        @endif
                    </div>
                    @if ($updatedAt >= '2025-01-15')
                        <hr>
                        <div class="text-sm mt-3">
                            @if ($boq->approved3)
                                <strong class="text-success">
                                    <div>Approved:</div>
                                    <div>
                                        {{ $boq->approved3->name }}
                                    </div>
                                </strong>
                                @if ($boq->date_approved_3)
                                    <div>
                                        <em>{{ date('d F Y', strtotime($boq->date_approved_3)) }}</em>
                                    </div>
                                @endif
                            @else
                                <span><strong>Waiting for third approval</strong></span>
                            @endif
                        </div>
                    @endif
                @endhasrole

                @if (auth()->user()->hasAnyRole('top-manager|manager|adminlapangan') && auth()->user())

                    @php
                        $approvals = [];

                        if ($boq->approved && $boq->approved_by == auth()->user()->id) {
                            $approvals[] = $boq->approved->name;
                        }
                        if ($boq->approved2 && $boq->approved_by_2 == auth()->user()->id) {
                            $approvals[] = $boq->approved2->name;
                        }
                        if ($boq->approved3 && $boq->approved_by_3 == auth()->user()->id) {
                            $approvals[] = $boq->approved3->name;
                        }
                    @endphp

                    @if ($boq->approved && $boq->approved2 && $boq->approved3)
                        <div class="text-sm">
                            <div><strong class="text-success">Approved: {{ $boq->approved->name }}</strong></div>
                            <div>
                                <em>{{ date('d F Y', strtotime($boq->date_approved)) }}</em>
                            </div>
                        </div>
                        <hr>
                        <div class="text-sm mt-3">
                            <div><strong class="text-success">Approved: {{ $boq->approved2->name }}</strong></div>
                            <div>
                                <em>{{ date('d F Y', strtotime($boq->date_approved_2)) }}</em>
                            </div>
                        </div>
                        <hr>
                        <div class="text-sm mt-3">
                            <div><strong class="text-success">Approved: {{ $boq->approved3->name }}</strong></div>
                            <div>
                                <em>{{ date('d F Y', strtotime($boq->date_approved_3)) }}</em>
                            </div>
                        </div>
                    @elseif(count($approvals) > 0)
                        <div>
                            <strong class="text-sm text-success">Approved: {{ implode(', ', $approvals) }}</strong>
                        </div>
                    @else
                        <span><strong>Waiting for approval</strong></span>
                    @endif
                @endif
            @endif
        @elseif ($boq->approved && $isSectionGreaterThanZero)
            <div class="text-sm">
                <strong class="text-success">
                    <div>Approved:</div>
                    <div>
                        {{ $boq->approved->name }}
                    </div>
                </strong>
                @if ($boq->date_approved)
                    <div>
                        <em>{{ date('d F Y', strtotime($boq->date_approved)) }}</em>
                    </div>
                @endif
            </div>
            <hr>
            <div class="text-sm mt-3">
                @if ($boq->approved2)
                    <strong class="text-success">
                        <div>Approved:</div>
                        <div>
                            {{ $boq->approved2->name }}
                        </div>
                    </strong>
                    @if ($boq->date_approved_2)
                        <div>
                            <em>{{ date('d F Y', strtotime($boq->date_approved_2)) }}</em>
                        </div>
                    @endif
                @else
                    <span><strong>Waiting for second approval</strong></span>
                @endif
            </div>
            @if ($updatedAt >= '2025-01-15')
                <hr>
                <div class="text-sm mt-3">
                    @if ($boq->approved3)
                        <strong class="text-success">
                            <div>Approved:</div>
                            <div>
                                {{ $boq->approved3->name }}
                            </div>
                        </strong>
                        @if ($boq->date_approved_3)
                            <div>
                                <em>{{ date('d F Y', strtotime($boq->date_approved_3)) }}</em>
                            </div>
                        @endif
                    @else
                        <span><strong>Waiting for third approval</strong></span>
                    @endif
                </div>
            @endif
        @else
            @if ($boq->approved)
                <div class="text-sm">
                    <strong class="text-success">
                        <div>Approved:</div>
                        <div>
                            {{ $boq->approved->name }}
                        </div>
                    </strong>
                    @if ($boq->date_approved)
                        <div>
                            <em>{{ date('d F Y', strtotime($boq->date_approved)) }}</em>
                        </div>
                    @endif
                </div>
            @else
                @if ($boq->project->boq_verification == 1)
                    <p class="text-sm">
                        <span><strong>Waiting for approval</strong></span>
                    </p>
                @else
                    <p class="text-sm">
                        <span><strong>Waiting for save and ajukan ke Management</strong></span>
                    </p>
                @endif
            @endif
        @endif
    @endif
@else
    @if ($boq->rejected_by != null && $boq->rejected_by != 0)
        <div class="text-sm">
            <strong class="text-danger">
                <div>
                    Rejected:
                </div>
                <div>
                    {{ $boq->rejected->name }}
                </div>
            </strong>
        </div>
    @else
        @if ($boq->approved)
            <div class="text-sm">
                <strong class="text-success">
                    <div>
                        Approved:
                    </div>
                    <div>
                        {{ $boq->approved->name }}
                    </div>
                </strong>
            </div>
        @else
            <div class="text-sm">
                <strong>
                    Waiting for approval
                </strong>
            </div>
        @endif
    @endif
@endif
