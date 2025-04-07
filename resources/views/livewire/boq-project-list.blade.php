<div class="mt-2 pb-5">
    <div class="row">
        <div class="col-lg-12 mb-5">
            <a class="btn btn-danger" href="{{ route('task-monitoring.index', $task->id) }}">
                <i class="fas fa-arrow-left"></i>
                Back
            </a>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left d-lg-flex justify-content-between">
                <div class=" ">
                    <h3 class="text-black">Daftar Spreadsheet Pengajuan BOQ Project - {{ $project->name }} | Task
                        - {{ $task->task_number }}</h3>
                </div>
            </div>
            <hr>
            <x-common.notification-alert />
        </div>
    </div>

    <div class="card">
        <div class="card-body">

            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <button class="nav-link {{ $status == 0 ? 'active' : '' }}" wire:click="updateStatus(0)">Waiting
                        Approval
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link {{ $status == 1 ? 'active' : '' }}" wire:click="updateStatus(1)">Approved
                    </button>
                </li>
            </ul>

            <div class="d-flex justify-content-end mb-4">
                <a href="{{ route('boq.project.insert', ['projectId' => $project->id, 'taskId' => $task->id]) }}"
                    class="btn btn-primary">Buat BOQ</a>
            </div>
            <table class="table">
                <thead class="thead-light">
                    <th class="text-center align-middle">No</th>
                    <th class="text-center align-middle">Task</th>
                    <th class="text-center align-middle">Created By</th>
                    <th class="text-center align-middle">Total Items</th>
                    <th class="text-center align-middle">Total Price</th>
                    <th class="text-center align-middle">Status</th>
                    {{-- <th class="text-center align-middle">Approval Status</th> --}}
                    <th class="text-center align-middle">Created At</th>
                    <th class="text-center align-middle">Action</th>
                </thead>
                <tbody>
                    @forelse ($submittedBOQs as $submitted)
                        <tr>
                            <td class="text-center align-middle">{{ $loop->iteration }}</td>
                            <td class="text-center align-middle">{{ $submitted->task_number }}</td>
                            <td class="text-center align-middle">{{ $submitted->user->name }}</td>
                            <td class="text-center align-middle">{{ count($submitted->getJsonDataAsObjectArray()) }}
                            </td>
                            <td class="text-center align-middle">{{ rupiah_format($submitted->getTotalPrice()) }}</td>
                            <td class="text-center align-middle">
                                @if ($submitted->status == 'Submitted')
                                    <span class="badge bg-warning">Submitted</span>
                                @elseif($submitted->status == 'Approved')
                                    <span class="badge bg-success">Checked</span>
                                @elseif($submitted->rejected_by)
                                    <span class="badge bg-danger">Rejected</span>
                                @elseif($submitted->status == 'Reviewed')
                                    <span class="badge bg-primary">Reviewed</span>
                                @elseif ($submitted->status == 'Finalized')
                                    <span class="badge bg-info">Approved</span>
                                @elseif ($submitted->status == 'Draft')
                                    <span class="badge bg-secondary">Draft</span>
                                @endif
                            </td>
                            {{-- <td class="text-center align-middle"> --}}
                            {{-- <x-boq.status-approval-k3 :boq="$submitted" :setting="$setting" :max_version="0"/> --}}
                            {{-- </td> --}}
                            <td class="text-center align-middle">
                                {{ date('d-m-Y H:i:s', strtotime($submitted->created_at)) }}
                            </td>
                            <td class="text-center align-middle">
                                @if ($submitted->status === 'Draft' || $submitted->status === 'Submitted')
                                    <a href="{{ route('boq.project.edit', ['projectId' => $project->id, 'taskId' => $task->id, 'boqId' => $submitted->id]) }}"
                                        class="btn btn-sm btn-outline-primary">Edit
                                    </a>
                                @endif
                                <button class="btn" wire:click="view({{ $submitted->id }})"
                                    class="btn btn-sm btn-outline-primary">View
                                </button>

                                @hasanyrole('manager|top-manager|it')
                                    @if ($submitted->status == 'Submitted')
                                        <button class="btn btn-sm btn-outline-danger"
                                            wire:click="delete({{ $submitted->id }})">Delete
                                        </button>
                                    @endif
                                @endhasanyrole
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">
                                <span class="text-black text-center">No Data</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div>

        <div class="d-flex justify-content-between">
            <div class="d-flex">
                <button class="btn btn-outline-primary" type="button" data-bs-toggle="collapse"
                    data-bs-target="#collapse" aria-expanded="false" aria-controls="collapse">
                    Keterangan
                </button>
            </div>
        </div>
        <div class="collapse mt-3" id="collapse">
            <div class="alert alert-secondary mb-0" role="alert">
                <ul class="mb-0 ">
                    <li class="mb-2">
                        <div class="d-flex">
                            <div class="">
                                <span class="badge bg-warning">Submitted</span>
                            </div>
                            <div class="ms-2">
                                : Pengajuan Spreadsheet BOQ sudah dibuat
                            </div>
                        </div>
                    </li>
                    <li class="mb-2">
                        <div class="d-flex">
                            <div class="">
                                <span class="badge bg-primary">Reviewed</span>
                            </div>
                            <div class="ms-2">
                                : Pengajuan Spreadsheet BOQ sudah direview
                            </div>
                        </div>
                    </li>
                    <li class="mb-2">
                        <div class="d-flex">
                            <div class="">
                                <span class="badge bg-success">Checked</span>
                            </div>
                            <div class="ms-2">
                                : Pengajuan Spreadsheet BOQ sudah dicek
                            </div>
                        </div>
                    </li>
                    <li class="mb-2">
                        <div class="d-flex">
                            <div class="">
                                <span class="badge bg-info">Approved</span>
                            </div>
                            <div class="ms-2">
                                : Pengajuan Spreadsheet BOQ <strong>sudah final</strong> dan <strong>sudah
                                    disubmit</strong> ke
                                dalam BOQ Proyek
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>

</div>
