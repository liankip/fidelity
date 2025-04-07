<div class="mt-2">
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2 class="primary-color-sne">Master Data Project</h2>
            </div>
            <hr>

            @foreach (['danger', 'warning', 'success', 'info'] as $key)
                @if (Session::has($key))
                    <div class="alert alert-{{ $key }} alert-dismissible fade show mb-1 mt-1" role="alert">
                        {{ Session::get($key) }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                        </button>
                    </div>
                @endif
            @endforeach

            <div class="pull-right mb-2 mt-5">
                @can(\App\Permissions\Permission::CREATE_PROJECT)
                    <div class="mr-2">
                        <a class="btn btn-success" href="{{ route('projects.create') }}">Create Project</a>
                    </div>
                @endcan
            </div>
        </div>
    </div>
    <ul class="nav nav-tabs mt-4">
        <li class="nav-item">
            <a class="nav-link " aria-current="page" href="/projects">On
                going</a>
        </li>
        <li class="nav-item">
            <a class="nav-link tabs-link-active" href="/projects/group">Groups</a>
        </li>
        <li class="nav-item">
            <a class="nav-link " href="/projects/finished">Finished</a>
        </li>
        <li class="nav-item">
            <a class="nav-link " href="/projects/draft">Draft</a>
        </li>
    </ul>

    <div class="d-flex mt-3">
        <div class="w-100">
            <form wire:submit.prevent="submitSearch">
                <div class="input-group">
                    <input type="text" class="form-control" name="search" placeholder="Search" wire:model="search">
                    {{-- <button class="btn btn-outline-secondary" type="submit" id="button-addon2">Search</button> --}}
                </div>
            </form>
        </div>
    </div>
    <div class="row mt-3">
        @can(\App\Permissions\Permission::MANAGE_GROUP)
            <div class="mb-3 d-flex justify-content-end">
                <livewire:project-group.add-group wire:key="unique-9928" />
            </div>
        @endcan
        @foreach ($groups as $group)
            <div class="accordion mb-3" id="group-{{ $group->id }}">
                <div class="accordion-item bg-white">
                    <h2 class="accordion-header pb-3" id="panelsStayOpen-heading-{{ $group->id }}">
                        <div class="accordion-head">
                            <button class="accordion-button collapsed text-white" type="button" data-bs-toggle="collapse"
                                data-bs-target="#panelsStayOpen-collapse-{{ $group->id }}" aria-expanded="false"
                                aria-controls="panelsStayOpen-collapse-{{ $group->id }}">
                                <b class="text-white">{{ $group->name }}</b>
                            </button>
                        </div>

                        <div class="d-lg-flex mt-2 justify-content-md-between align-items-center ">
                            <div>
                                <span class="badge badge-primary mt-0 ms-3" style="font-size: 14px">Total Project:
                                    {{ $group->projects->count() }}</span>
                                <span class="badge badge-success mt-0 ms-3"
                                    style="font-size: 14px; margin-bottom:10px;">Total Budget: Rp.
                                    {{ number_format($group->totalProjectBudget(), 0, ',', '.') }}</span>
                            </div>

                            <div class="d-flex">
                                <div>
                                    <a type="button" class="btn btn-secondary"
                                        style="margin-right: 50px; margin-left: 15px;"
                                        href="{{ route('projects.approvedItems', ['groupId' => $group->id]) }}">Show
                                        Approved Items</a>
                                </div>

                                <div>
                                    <a type="button" class="btn btn-info"
                                        style="margin-right: 50px; margin-left: 15px;"
                                        href="{{ route('projects.showItems', ['groupId' => $group->id]) }}">Show
                                        Items</a>
                                </div>
                            </div>
                        </div>

                    </h2>
                    <div id="panelsStayOpen-collapse-{{ $group->id }}" class="accordion-collapse collapse"
                        aria-labelledby="panelsStayOpen-heading-{{ $group->id }}">
                        <div class="accordion-body">
                            @can(\App\Permissions\Permission::MANAGE_GROUP)
                                <div class="d-flex justify-content-between">
                                    <livewire:project-group.add-project wire:key="group-{{ $group->id }}"
                                        :group="$group" />
                                    <button class="btn btn-danger" wire:click="deleteGroup({{ $group->id }})">
                                        <i class="fas fa-trash"></i>
                                        Delete Group
                                    </button>

                                </div>
                            @endcan
                            <div class="overflow-x-max">
                                <table class="table table-secondary mt-3">
                                    <tr class="">
                                        <th class="text-center align-items-center border-top-left" width="5%">No
                                        </th>
                                        <th class="text-center" width="15%">Project</th>
                                        <th class="text-center" width="15%">Company Name</th>
                                        <th class="text-center" width="10%">PIC</th>
                                        <th class="text-center" width="15%">Email / Phone</th>
                                        <th class="text-center" width="20%">Complete Address</th>
                                        <th class="text-center" width="10%">BOQ Grand Total</th>
                                        <th class="text-center" width="7%">Action</th>
                                        <th class="border-top-right">*</th>
                                    </tr>
                                    @forelse ($group->projects as $key => $project)
                                        <tr class="bg-white">
                                            <td class="text-center">{{ $key + 1 }}</td>
                                            <td>
                                                <div><strong>Name:</strong> {{ $project->name }}</div>
                                                <div><strong>Code:</strong> {{ $project->project_code }}</div>
                                                <div><strong>Project Budget:</strong>
                                                    Rp. {{ number_format($project->value, 0, ',', '.') }}</div>
                                            </td>
                                            <td>{{ $project->company_name }}</td>
                                            <td>{{ $project->pic }}</td>
                                            <td>
                                                <div>
                                                    <strong>Email:</strong>
                                                    {{ $project->email ? $project->email : '-' }}
                                                </div>
                                                <div>
                                                    <strong>Phone:</strong> {{ $project->phone }}
                                                </div>
                                            </td>
                                            <td>
                                                <div>
                                                    <strong>Address:</strong>
                                                    {{ $project->address }}
                                                    {{ $project->city }}
                                                    {{ $project->province }}
                                                    {{ $project->post_code }}
                                                </div>
                                            </td>
                                            <td>

                                                Rp. {{ number_format($project->boqGrandTotal(), 0, ',', '.') }}
                                            </td>
                                            <td class="text-left">
                                                <div>
                                                    <a href="{{ route('history.project', ['id' => $project->id]) }}"
                                                        target="_blank"
                                                        class="w-100 btn btn-secondary btn-sm">Riwayat</a>
                                                </div>
                                                {{--                                            <div class="mt-1"> --}}
                                                {{--                                                <a class="w-100 btn btn-primary btn-sm" --}}
                                                {{--                                                   href="{{ route('projects.edit', $project->id) }}">Edit</a> --}}
                                                {{--                                            </div> --}}
                                                <div class="mt-1">
                                                    <a class="w-100 btn btn-success btn-sm"
                                                        href="{{ route('boq.index', $project->id) }}">BOQ</a>
                                                </div>
                                                @if ($project->status != 'Finished' && auth()->user()->hasTopLevelAccess())
                                                    <div class="mt-1">
                                                        <a class="w-100 btn btn-primary btn-sm"
                                                            href="{{ route('project-finished', $project->id) }}">Finish</a>
                                                    </div>
                                                @endif
                                                <div class="mt-1">
                                                    <a class="w-100 btn btn-outline-success btn-sm"
                                                        href="{{ route('monitor-project', $project->id) }}">Monitoring</a>
                                                </div>
                                            </td>
                                            <td>
                                                @can(\App\Permissions\Permission::MANAGE_GROUP)
                                                    <button type="button" class="btn btn-danger btn-sm"
                                                        wire:click="removeProject({{ $project }})">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                @endcan
                                            </td>
                                        </tr>
                                    @empty
                                        <tr class="bg-white">
                                            <td colspan="9" class="text-center">No Data</td>
                                        </tr>
                                    @endforelse
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="mt-5">
        {{ $groups->links() }}
    </div>

</div>
