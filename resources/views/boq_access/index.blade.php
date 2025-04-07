<div class="container mt-2">
    <div class="row">
        <div class="col-lg-12 mb-5">
            <a class="btn btn-danger" href="{{ route('boq.index', $project->id) }}">
                <i class="fas fa-arrow-left"></i>
                Back
            </a>
        </div>
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>BOQ Access for project {{ $project->name }}</h2>
                <hr>
            </div>
        </div>
    </div>

    @foreach (['danger', 'warning', 'success', 'info'] as $key)
        @if (Session::has($key))
            <div class="alert alert-{{ $key }} alert-dismissible fade show mb-1 mt-1" role="alert">
                {{ Session::get($key) }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                </button>
            </div>
        @endif
    @endforeach

    <div class="card mt-5">
        <div class="card-body p-lg-5">
            <div class="row ">
                <div class="d-lg-flex justify-content-between">
                    <h2>
                        User Access
                    </h2>

                    <button class="btn btn-primary" wire:click="showModal">
                        Add Access
                    </button>
                </div>
            </div>
            <div class="mt-3">
                <div class="">
                    <table class="table table-borderless table-striped">
                        <thead>
                            <tr class="table-primary">
                                <th style="width: 30%">Name</th>
                                <th style="width: 30%">Email</th>
                                <th style="width: 30%">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($project->boq_access->where('status', 'approved') as $item)
                                <tr>
                                    <td>{{ $item->user->name }}</td>
                                    <td>{{ $item->user->email }}</td>
                                    <td>
                                        <button class="btn btn-danger btn-sm"
                                            wire:click="removeAccess({{ $item->id }})">
                                            Remove Access
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center">
                                        No access
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>

    <div class="card mt-5">
        <div class="card-body p-lg-5">
            <div class="row">
                <h2>
                    Need Approval
                </h2>
            </div>
            <div class="mt-3">
                <div class="">
                    <table class="table table-borderless table-striped">
                        <thead>
                            <tr class="table-primary">
                                <th style="width: 30%">Name</th>
                                <th style="width: 30%">Email</th>
                                <th style="width: 30%">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($project->boq_access->where('status', 'pending') as $item)
                                <tr>
                                    <td>{{ $item->user->name }}</td>
                                    <td>{{ $item->user->email }}</td>
                                    <td class="d-flex gap-2">
                                        <button class="btn btn-success btn-sm"
                                            wire:click="submitApproval(1, {{ $item->id }})">Approve</button>
                                        <button class="btn btn-danger btn-sm"
                                            wire:click="submitApproval(0, {{ $item->id }})">Reject</button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center">
                                        No user
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>

    @if ($showModal)
        @include('components.modal_add_boq_access')
    @endif
</div>
