<div>
    <h2>Task Revision Approval</h2>
    <hr>
    @foreach (['danger', 'warning', 'success', 'info'] as $key)
        @if (Session::has($key))
            <div class="alert alert-{{ $key }} alert-dismissible fade show mb-1 mt-1" role="alert">
                {{ Session::get($key) }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
    @endforeach

    @forelse ($groupedTasks as $projectId => $projectTasks)
        <div class="card" style="overflow-x: scroll;">
            <div class="card-header">
                <h3 class="card-title">{{ $projectTasks->first()->project->name }}</h3>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Project</th>
                            <th>Keterangan</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{ $projectTasks->first()->project->name }}</td>
                            <td>{{ $projectTasks->first()->comment }}</td>
                            <td>
                                @hasanyrole('it|top-manager|manager')
                                    @if ((bool) $this->setting->multiple_wbs_revision_approval)
                                        @if (is_null($projectTasks->first()->revision_by_user_1) && is_null($projectTasks->first()->revision_date_user_1))
                                            <span class="d-flex gap-2 mb-2">
                                                <form
                                                    wire:submit.prevent="approveMultipleWbsRevision({{ $projectTasks->first()->project_id }})"
                                                    method="post" class="w-fit">
                                                    @csrf
                                                    <button type="submit" class="btn btn-success btn-sm">
                                                        First Approve
                                                    </button>
                                                </form>
                                                <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                                    data-bs-target="#revertModal-{{ $projectTasks->first()->project_id }}">
                                                    Revert
                                                </button>
                                            </span>
                                        @elseif(
                                            !is_null($projectTasks->first()->revision_date_user_1) &&
                                                !is_null($projectTasks->first()->revision_by_user_1) &&
                                                is_null($projectTasks->first()->revision_date_user_2) &&
                                                is_null($projectTasks->first()->revision_by_user_2))
                                            @if ($projectTasks->first()->revision_by_user_1 != auth()->user()->id)
                                                <span class="d-flex gap-2 mb-2">
                                                    <form
                                                        wire:submit.prevent="approveMultipleWbsRevision({{ $projectTasks->first()->project_id }})"
                                                        method="post" class="w-fit">
                                                        @csrf
                                                        @method('put')
                                                        <button type="submit" class="btn btn-success btn-sm">Second
                                                            Approve</button>
                                                    </form>
                                                    <button type="button" class="btn btn-danger btn-sm"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#revertModal-{{ $projectTasks->first()->project_id }}">
                                                        Revert
                                                    </button>
                                                </span>
                                            @else
                                                <button disabled class="btn btn-sm btn-success mb-2">Approved</button>
                                            @endif
                                        @endif
                                    @else
                                        @if (is_null($projectTasks->first()->revision_by_user_1))
                                            <span class="d-flex gap-1 mb-2">
                                                <button type="button" class="btn btn-success btn-sm"
                                                    wire:click="approveWbsRevision({{ $projectTasks->first()->project_id }})">
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
                            </td>
                        </tr>
                    </tbody>
                </table>

                <x-common.modal id="revertModal-{{ $projectTasks->first()->project_id }}"
                    title="{{ $projectTasks->first()->project->name }}">
                    <x-slot:modal-body>
                        Are you sure you want to revert this task?
                    </x-slot:modal-body>
                    <x-slot:modal-footer>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            Close
                        </button>
                        <button type="button" class="btn btn-primary"
                            wire:click="revertFunction({{ $projectTasks->first()->project->name }})">Yes
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
