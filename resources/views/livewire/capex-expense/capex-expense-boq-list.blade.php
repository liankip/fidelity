<div class="mt-2">
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <a href="{{ route('capex-expense.boq', $project_id) }}" class="third-color-sne"> <i
                        class="fa-solid fa-chevron-left fa-xs"></i> Back</a>
                <h3 class="text-black">List Spreadsheet BOQ Capex Expense</h3>
            </div>
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
                <a href="{{ route('capex-expense.boq.insert', ['project_id' => $project_id]) }}"
                    class="btn btn-primary">Buat
                    BOQ</a>
            </div>
            <table class="table">
                <thead class="thead-light">
                    <th class="text-center align-middle">No</th>
                    <th class="text-center align-middle">Project</th>
                    <th class="text-center align-middle">Created By</th>
                    <th class="text-center align-middle">Total Items</th>
                    <th class="text-center align-middle">Total Price</th>
                    <th class="text-center align-middle">Status</th>
                    <th class="text-center align-middle">Created At</th>
                    <th class="text-center align-middle">Action</th>
                </thead>
                <tbody>
                    @forelse ($submittedBOQs as $index => $submitted)
                        <tr>
                            <td class="text-center align-middle">{{ $index + 1 }}</td>
                            <td class="text-center align-middle">
                                {{ $submitted->project->name }}
                            </td>
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
                            <td class="text-center align-middle">
                                {{ date('d-m-Y H:i:s', strtotime($submitted->created_at)) }}
                            </td>
                            <td class="text-center align-middle">
                                @if ($submitted->status === 'Draft' || $submitted->status === 'Submitted')
                                    <a href="{{ route('capex-expense.boq.edit', ['project_id' => $project_id, 'id' => $submitted->id]) }}"
                                        class="btn btn-sm btn-outline-primary">Edit
                                    </a>
                                @endif
                                <button class="btn" wire:click="view('{{ $submitted->id }}')"
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
</div>
