<div>
    <h1 class="primary-color-sne">WBS - {{ $projectName }}</h1>
    @foreach (['danger', 'warning', 'success', 'info'] as $key)
        @if (Session::has($key))
            <div class="alert alert-{{ $key }} alert-dismissible fade show mb-1 mt-1" role="alert">
                {{ Session::get($key) }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
    @endforeach

    <div class="pt-4">
        @if ($project->task_file_path)
            <ul class="list-group mb-2">
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <a href="{{ asset('storage/' . $project->task_file_path->file_path) }}" class="secondary-color-sne"
                        style="font-size: 20px" target="_blank">Submission
                        <strong>
                            @if (
                                $tasks->every(fn($task) => $task->status === 'Pending') &&
                                    $tasks->every(fn($task) => $task->is_chart_submitted === 'false'))
                                (Draft)
                            @endif

                            @if ($tasks->every(fn($task) => $task->status === 'Approved'))
                                (Approved)
                            @elseif ($tasks->every(fn($task) => $task->status === 'Finish'))
                                (Finish)
                            @elseif (
                                $tasks->every(fn($task) => $task->status === 'Pending') &&
                                    $tasks->every(fn($task) => $task->is_chart_submitted === 'true'))
                                (Pending)
                            @elseif ($tasks->every(fn($task) => $task->status === 'Rejected'))
                                (Rejected)
                            @elseif ($tasks->every(fn($task) => $task->status === 'Revision'))
                                (Revisi)
                            @endif
                        </strong>
                    </a>
                </li>
            </ul>
        @else
            {{-- <p>No files uploaded yet for this project.</p> --}}
        @endif

        @if ($tasks->count() != 0)
            @if (
                $tasks->every(fn($task) => $task->status === 'Pending') &&
                    $tasks->every(fn($task) => $task->is_chart_submitted === 'false'))
                <p>
                    *WBS dalam status draft, menunggu submission chart
                </p>
            @endif
            @if ($tasks->first()->status == 'Pending' && $tasks->first()->is_chart_submitted == 'true')
                <p>
                    *WBS dalam status pending, menunggu approval atasan terkait.
                </p>
            @elseif ($tasks->first()->status == 'Revision')
                <p>
                    *WBS dalam status menunggu approval revisi, menunggu approval atasan terkait.
                </p>
            @endif
            @if ($tasks->every(fn($task) => $task->status === 'Rejected'))
                <div class="alert alert-danger" role="alert">
                    Mohon upload ulang file task, karena file task yang diupload sebelumnya telah ditolak.
                </div>
            @endif
        @endif

        @if ($tasks->count() == 0)
            <a href="{{ route('task.chart', $project->id) }}" class="btn btn-primary mb-3">Create Gantt Chart</a>
        @else
            <a href="{{ route('task.chart', $project->id) }}" class="btn btn-primary mb-3">View Gantt Chart</a>
        @endif

        @if ($tasks->count() != 0 && $tasks[0]->status != 'Pending')
            @if ($tasks->every(fn($task) => $task->status == 'Revision Approved'))
                {{-- <div class="d-flex align-items-center gap-2">
                    <div class="ms-auto d-flex mb-2">
                        <button class="btn btn-primary btn-outline" style="margin-right: -0.1rem" data-bs-toggle="modal"
                            data-bs-target="#taskRevisionConfirmModal" type="button" wire:loading.attr="disabled"
                            wire:target="earliestStart, earliestFinish, calculateDuration"
                            @if ($errors->isNotEmpty()) disabled @endif>
                            Submit Revisi Task
                        </button>

                        <div wire:loading wire:target="earliestStart, earliestFinish, calculateDuration"
                            class="position-absolute top-50 end-0 translate-middle-y">
                            <div class="spinner-border spinner-border-sm text-light" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                    </div>
                </div> --}}
            @else
                <div class="d-flex align-items-center gap-2">
                    <div class="ms-auto d-flex mb-2">
                        <button class="btn btn-outline-primary " data-bs-toggle="modal"
                            data-bs-target="#taskRevisionModal" type="button"
                            {{ (!is_null($tasks->first()->comment) && $tasks->first()->revision == true) || $tasks->count() < 0 ? 'disabled' : '' }}>
                            Revisi WBS
                        </button>
                    </div>
                </div>
            @endif
        @endif


        <table class="table primary-box-shadow" style="overflow-x: auto; display: block;border-radius: 10px;">
            <thead class="thead-light">
                <tr>
                    <th class="text-center align-items-center border-top-left">Section</th>
                    <th class="text-center align-items-center">WBS</th>
                    <th class="text-center align-items-center">Weight</th>
                    <th class="text-center align-items-center">WBS Number</th>
                    <th class="text-center align-items-center">ES</th>
                    <th class="text-center align-items-center">Start Date</th>
                    <th class="text-center align-items-center">Duration</th>
                    <th class="text-center align-items-center">EF</th>
                    <th class="text-center align-items-center">Completion Schedule Date</th>
                    <th class="text-center align-items-center">Status</th>
                    <th class="text-center align-items-center">Deviation</th>
                    <th class="text-center align-items-center">Action</th>
                    <th class="text-center align-items-center">Slack</th>
                    <th class="text-center align-items-center">Total Approve BOQ</th>
                    <th class="text-center align-items-center border-top-right">Path</th>
                </tr>
            </thead>
            <tbody class="bg-white">
                @php
                    $totalBobot = $tasks->sum('bobot');
                    $currentSection = null;
                    $isOldTask = $tasks->every(fn($task) => $task->type === null);
                    $grandTotalBOQ = 0;
                    $cutoffDate = '2025-01-20';
                @endphp
                @foreach ($tasks as $index => $t)
                    @php
                        $purchaseRequestExists = false;

                        if ($t->purchaseRequest && strpos($t->purchaseRequest->partof, $t->task_number) !== false) {
                            $purchaseRequestExists = true;
                        }

                        $isPending = $t->status === 'Pending';

                        $startDate = Carbon\Carbon::parse($t->start_date);
                        $today = Carbon\Carbon::today();
                        $daysLeft = $today->diffInDays($startDate, false);

                        $taskBoq = \App\Models\BOQ::where('task_number', $t->task_number)->first();

                        // Make a condition of section where the value isnt null and can be converted to intval
                        $isSubTask = false;
                        $isSection = false;
                        if (!is_null($t->section) && is_numeric($t->section)) {
                            $isSubTask = $tasks->where('id', $t->section)->first()?->type == 'task';
                        }

                        if ($isSubTask) {
                            continue;
                        }

                        if (!is_numeric($t->section) && $t->section !== '' && $t->type == 'project') {
                            $isSection = true;
                        }

                        $taskSection = \App\Models\Task::where('id', $t->section)->first()?->task;
                    @endphp

                    @if ($isOldTask)
                        <tr>
                            <td>{{ $t->section }}</td>
                            <td>{{ $t->task }}
                                @if ($taskBoq === null)
                                    <br>
                                    <small class="badge badge-danger">
                                        Belum ada BOQ
                                    </small>
                                @endif
                            </td>
                            <td>{{ $t->bobot }}</td>
                            <td>{{ $t->task_number }}</td>
                            <td>{{ $t->earliest_start }}</td>
                            <td>{{ Carbon\Carbon::parse($t->start_date)->format('d-m-Y') }}
                                @if ($daysLeft >= 0 && $daysLeft <= 14)
                                    <br>
                                    @if ($daysLeft == 0)
                                        <small class="badge badge-success">
                                            Dimulai hari ini
                                        </small>
                                    @else
                                        <small class="badge badge-danger">
                                            H - {{ $daysLeft }}
                                        </small>
                                    @endif
                                @endif
                            </td>
                            <td>{{ $t->duration }}</td>
                            <td>{{ $t->earliest_finish }}</td>
                            <td>{{ Carbon\Carbon::parse($t->finish_date)->format('d-m-Y') }}</td>
                            <td>
                                <div class="badge badge-success">
                                    {{ $t->status }}
                                </div>
                            </td>
                            <td>{{ $t->deviasi }}</td>
                            <td>
                                @if ($t->status != 'Pending' && $t->status != 'Rejected')
                                    <div class="d-flex justify-content-end gap-1">
                                        <a href="{{ route('task-monitoring.index', $t->id) }}"
                                            class="btn btn-outline-success">
                                            Monitoring
                                        </a>
                                    </div>
                                @endif
                            </td>

                            <td>{{ $t->slack ?? '-' }}</td>
                            <td>
                                @php
                                    $total = 0;
                                @endphp
                                @foreach ($t->boqs as $boq)
                                    @if ($boq->approved_by_3 !== null)
                                        @php
                                            $subtotal = $boq->qty * $boq->price_estimation;
                                            $total += $subtotal;
                                            $grandTotalBOQ += $total;
                                        @endphp
                                    @endif
                                @endforeach
                                {{ rupiah_format($total) }}
                            </td>
                            <td>
                                @if ($t->slack !== null && $t->slack == 0.0)
                                    <small class="badge badge-danger">Critical</small>
                                @else
                                    @if ($t->slack !== null)
                                        <small class="badge badge-success">Non Critical</small>
                                    @else
                                        -
                                    @endif
                                @endif
                            </td>
                        </tr>
                    @else
                        @if ($taskSection == 'Consumables' || (!$isSubTask && !$isSection))
                            <tr>
                                <td>
                                    @if ($t->section !== $currentSection)
                                        {{ $taskSection }}

                                        @php
                                            $currentSection = $t->section;
                                        @endphp
                                    @endif
                                </td>
                                <td>{{ $t->task }}
                                    @if ($taskBoq === null)
                                        <br>
                                        <small class="badge badge-danger">
                                            Belum ada BOQ
                                        </small>
                                    @endif
                                </td>
                                <td>{{ $t->bobot }}</td>
                                <td>{{ $t->task_number }}</td>
                                <td>{{ $t->earliest_start }}</td>
                                <td>{{ Carbon\Carbon::parse($t->start_date)->format('d-m-Y') }}
                                    @if ($daysLeft >= 0 && $daysLeft <= 14)
                                        <br>
                                        @if ($daysLeft == 0)
                                            <small class="badge badge-success">
                                                Dimulai hari ini
                                            </small>
                                        @else
                                            <small class="badge badge-danger">
                                                H - {{ $daysLeft }}
                                            </small>
                                        @endif
                                    @endif
                                </td>
                                <td>{{ $t->duration }}</td>
                                <td>{{ $t->earliest_finish }}</td>
                                <td>{{ Carbon\Carbon::parse($t->finish_date)->format('d-m-Y') }}</td>
                                <td>
                                    <div class="badge badge-success">
                                        {{ $t->status }}
                                    </div>
                                </td>
                                <td>{{ $t->deviasi }}</td>
                                <td>
                                    @if ($t->status != 'Pending' && $t->status != 'Rejected')
                                        <div class="d-flex justify-content-end gap-1">
                                            <a href="{{ route('task-monitoring.index', $t->id) }}"
                                                class="btn btn-outline-success">
                                                Monitoring
                                            </a>
                                        </div>
                                    @endif
                                </td>

                                <td>{{ $t->slack ?? '-' }}</td>
                                <td>
                                    @php
                                        $total = 0;
                                    @endphp

                                    @foreach ($t->boqs as $boq)
                                        @php
                                            $subtotal = $boq->qty * $boq->price_estimation;
                                        @endphp

                                        @if ($boq->updated_at < $cutoffDate && $boq->approved_by_2 !== null)
                                            @php
                                                $total += $subtotal;
                                            @endphp
                                        @elseif ($boq->updated_at >= $cutoffDate && $boq->approved_by_3 !== null)
                                            @php
                                                $total += $subtotal;
                                            @endphp
                                        @endif
                                    @endforeach

                                    @php
                                        $grandTotalBOQ += $total; // Move this outside the inner loop
                                    @endphp

                                    {{ rupiah_format($total) }}
                                </td>
                                <td>
                                    @if ($t->slack !== null && $t->slack == 0.0)
                                        <small class="badge badge-danger">Critical</small>
                                    @else
                                        @if ($t->slack !== null)
                                            <small class="badge badge-success">Non Critical</small>
                                        @else
                                            -
                                        @endif
                                    @endif
                                </td>
                            </tr>
                        @endif
                    @endif

                @endforeach
            </tbody>
            <tfoot class="bg-white">
                <tr>
                    <td colspan="2"><strong>Total Bobot</strong></td>
                    <td><strong>{{ $totalBobot }}</strong></td>
                    <td colspan="10"></td>
                    <td colspan="3">{{ rupiah_format($grandTotalBOQ) }}</td>
                </tr>
            </tfoot>
        </table>
    </div>

    <x-common.modal id="taskRevisionModal" title="Task Revision">
        <x-slot:modal-body>
            <form wire:submit.prevent="taskRevision">
                <div class="form-group">
                    <label for="comment">Comment Task Revision</label>
                    <textarea required class="form-control" name="comment" wire:model="comment" placeholder="Masukkan komentar task revisi"></textarea>
                </div>

                <button class="btn btn-success">Save</button>
            </form>
        </x-slot:modal-body>
        <x-slot:modal-footer>
        </x-slot:modal-footer>
    </x-common.modal>

    <x-common.modal id="taskRevisionConfirmModal" title="Task Revision Confirm">
        <x-slot:modal-body>
            <h6>Apakah task yang direvisi sudah sesuai?</h6>
        </x-slot:modal-body>
        <x-slot:modal-footer>
            <button wire:click="taskRevisonConfirm" class="btn btn-success">Save</button>
            <x-common.modal.button-cancel />
        </x-slot:modal-footer>
    </x-common.modal>
</div>
