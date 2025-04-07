<div>
    <h2 class="primary-color-sne">WBS List Approval</h2>
    @foreach (['danger', 'warning', 'success', 'info'] as $key)
        @if (Session::has($key))
            <div class="alert alert-{{ $key }} alert-dismissible fade show mb-1 mt-1" role="alert">
                {{ Session::get($key) }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
    @endforeach

    @php
        $groupedTasks = $tasks->groupBy('project.id');
    @endphp

    @forelse ($groupedTasks as $projectId => $projectTasks)
        @php
            $totalBobot = 0;
        @endphp
        <div class="card mt-5 primary-box-shadow" style="overflow-x: scroll;">
            <div class="card-body">
                <h3>{{ $projectTasks->first()->project->name }}</h3>

                @hasanyrole('it|top-manager|manager')
                    @if ((bool) $this->setting->multiple_wbs_approval)
                        @if (is_null($projectTasks->first()->approved_by_user_1) && is_null($projectTasks->first()->approved_date_user_1))
                            <span class="d-flex gap-2 mb-2">
                                <form action="{{ route('approve_task', $projectTasks->first()->project_id) }}"
                                    method="post" class="w-fit">
                                    @csrf
                                    @method('put')
                                    <button type="submit" class="btn btn-success btn-sm">
                                        First Approve
                                    </button>
                                </form>
                                <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#revertModal-{{ $projectTasks->first()->project_id }}">
                                    Revert
                                </button>
                                <a class="btn btn-info btn-sm"
                                    href="{{ route('task.chart', ['project' => $projectTasks->first()->project_id, 'readOnly' => true]) }}">View
                                    Chart</a>
                            </span>
                        @elseif(
                            !is_null($projectTasks->first()->approved_date_user_1) &&
                                !is_null($projectTasks->first()->approved_by_user_1) &&
                                is_null($projectTasks->first()->approved_date_user_2) &&
                                is_null($projectTasks->first()->approved_by_user_2))
                            @if ($projectTasks->first()->approved_by_user_1 != auth()->user()->id)
                                <span class="d-flex gap-2 mb-2">
                                    <form action="{{ route('approve_task', $projectTasks->first()->project_id) }}"
                                        method="post" class="w-fit">
                                        @csrf
                                        @method('put')
                                        <button type="submit" class="btn btn-success btn-sm">Second
                                            Approve</button>
                                    </form>
                                    <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#revertModal-{{ $projectTasks->first()->project_id }}">
                                        Revert
                                    </button>
                                    <a class="btn btn-info btn-sm"
                                        href="{{ route('task.chart', ['project' => $projectTasks->first()->project_id, 'readOnly' => true]) }}">View
                                        Chart</a>
                                </span>
                            @else
                                <button disabled class="btn btn-sm btn-success mb-2">Approved</button>
                            @endif
                        @endif
                    @else
                        @if (is_null($projectTasks->first()->approved_by_user_1))
                            <span class="d-flex gap-1 mb-2">
                                <button type="button" class="btn btn-success btn-sm"
                                    wire:click="approve({{ $projectTasks->first()->project_id }})">
                                    Approve
                                </button>
                                <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#revertModal-{{ $projectTasks->first()->project_id }}">
                                    Revert
                                </button>
                                <a class="btn btn-info btn-sm"
                                    href="{{ route('task.chart', ['project' => $projectTasks->first()->project_id, 'readOnly' => true]) }}">View
                                    Chart</a>
                            </span>
                        @else
                            <div class="d-flex flex-column align-items-center">
                                <span class="badge bg-success">Approved</span>
                            </div>
                        @endif
                    @endif
                @endhasanyrole

                <table class="table">
                    <thead class="thead-light">
                        <tr>
                            <th class="text-center border-top-left">Section</th>
                            <th class="text-center">WBS</th>
                            <th class="text-center">Bobot</th>
                            <th class="text-center">WBS Number</th>
                            <th class="text-center">ES</th>
                            <th class="text-center">Start Date</th>
                            <th class="text-center">Duration</th>
                            <th class="text-center">EF</th>
                            <th class="text-center">Slack</th>
                            <th class="text-center">path</th>
                            <th class="text-center border-top-right">Completion schedule date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($projectTasks->groupBy('task') as $taskName => $taskGroup)
                            @php
                                $totalBobot += $taskGroup->sum('bobot');
                                $firstTask = $taskGroup->first();
                            @endphp
                            <tr>
                                <td class="text-center">{{ $firstTask->section }}</td>
                                <td class="text-center">
                                    @hasanyrole('it|top-manager|manager|top-manager|super-admin')
                                        @if (is_null($projectTasks->first()->approved_by_user_1) && is_null($projectTasks->first()->approved_date_user_1))
                                            <textarea class="form-control" rows="3" cols="50" wire:model.defer="task.{{ $firstTask->id }}"
                                                oninput="autoResize(this)" wire:change="updateTaskName({{ $firstTask->id }})"></textarea>

                                            @error("task.{$firstTask->id}")
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        @elseif(
                                            !is_null($projectTasks->first()->approved_date_user_1) &&
                                                !is_null($projectTasks->first()->approved_by_user_1) &&
                                                is_null($projectTasks->first()->approved_date_user_2) &&
                                                is_null($projectTasks->first()->approved_by_user_2))
                                            @if ($projectTasks->first()->approved_by_user_1 != auth()->user()->id)
                                                <textarea class="form-control" rows="3" cols="50" wire:model.defer="task.{{ $firstTask->id }}"
                                                    oninput="autoResize(this)" wire:change="updateTaskName({{ $firstTask->id }})"></textarea>

                                                @error("task.{$firstTask->id}")
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            @else
                                                <textarea class="form-control" rows="3" cols="50" wire:model.defer="task.{{ $firstTask->id }}"
                                                    oninput="autoResize(this)" disabled wire:change="updateTaskName({{ $firstTask->id }})"></textarea>
                                            @endif
                                        @else
                                            {{ $firstTask->task }}
                                        @endif
                                    @endhasanyrole
                                </td>
                                <td class="text-center">{{ $taskGroup->sum('bobot') }}</td>
                                <td class="text-center">{{ $firstTask->task_number }}</td>
                                <td class="text-center">{{ $firstTask->earliest_start }}</td>
                                <td class="text-center">{{ $firstTask->start_date }}</td>
                                <td class="text-center">{{ $firstTask->duration }}</td>
                                <td class="text-center">{{ $firstTask->earliest_finish }}</td>
                                <td class="text-center">{{ $firstTask->slack }}</td>
                                <td class="text-center">
                                    @if ($firstTask->slack !== null && $firstTask->slack == 0.0)
                                        <small class="badge badge-danger">Critical</small>
                                    @else
                                        <small class="badge badge-success">Non Critical</small>
                                    @endif
                                </td>
                                <td class="text-center">{{ $firstTask->finish_date }}</td>
                            </tr>
                        @endforeach
                        <tr>
                            <td class="text-center" colspan="2"><strong>Total Bobot</strong></td>
                            <td class="text-center"><strong>{{ $totalBobot }}</strong></td>
                            <td class="text-center" colspan="6"></td>
                        </tr>
                    </tbody>
                </table>

                <x-common.modal id="revertModal-{{ $projectTasks->first()->project_id }}"
                    title="{{ $firstTask->project->name }}">
                    <x-slot:modal-body>
                        Are you sure you want to revert this task?
                    </x-slot:modal-body>
                    <x-slot:modal-footer>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            Close
                        </button>
                        <button type="button" class="btn btn-primary"
                            wire:click="revertFunction({{ $firstTask->project_id }})">Yes
                        </button>
                    </x-slot:modal-footer>
                </x-common.modal>
            </div>
        </div>
    @empty
        <div class="alert alert-info" role="alert">
            No task to approve
        </div>
    @endforelse
</div>

<script>
    function autoResize(textarea) {
        textarea.style.height = 'auto';
        textarea.style.height = (textarea.scrollHeight) + 'px';
    }
</script>
