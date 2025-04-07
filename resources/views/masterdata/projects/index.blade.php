@extends('layouts.app')

@section('content')
    <div class="mt-2">
        <div class="row">
            <div class="col-lg-12 margin-tb">
                <div class="pull-left">
                    <h2 class="primary-color-sne">Master Data Project</h2>
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

                <div class="pull-right mb-2 mt-5 d-flex justify-content-between">

                    {{-- <form action="{{ route('items.import') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="file" name="file" class="form-control">
                        <br>
                        <button class="btn btn-success">Upload CSV</button>
                    </form> --}}
                    {{-- <a class="btn btn-success" href="{{ route('items.export') }}"> Downnload as CSV</a> --}}

                    <div class="d-flex gap-3">
                        @can(\App\Permissions\Permission::CREATE_PROJECT)
                            <div class="mr-2">
                                <a class="btn btn-success" href="{{ route('projects.create') }}">
                                    <i class="fas fa-plus"></i>
                                    Create Project
                                </a>
                            </div>
                        @endcan
                        @hasanyrole('it|top-manager|manager|adminlapangan')
                            <div class="mr-2">
                                <a class="btn btn-secondary" href="{{ route('projects.create-draft.store') }}">
                                    <i class="fas fa-plus"></i>
                                    Create Draft Project
                                </a>
                            </div>
                        @endhasanyrole

                        @hasanyrole('it|top-manager|manager')
                            <div class="mr-2">
                                <a class="btn btn-primary" href="{{ route('projects.reports') }}">
                                    Finished Project Report
                                </a>
                            </div>
                        @endhasanyrole
                    </div>
                    @hasanyrole('it|top-manager|manager')
                        {{-- <div class="mr-2">
                            <a class="btn btn-primary" href="{{ route('projects.document') }}">
                                <i class="fas fa-file"></i>
                                Documents
                            </a>
                        </div> --}}
                    @endhasanyrole
                </div>
            </div>
        </div>

        <ul class="nav nav-tabs mt-4">
            <li class="">
                <a class="nav-link @if ($status == 'On going') tabs-link-active @endif" aria-current="page" href="/projects">On
                    going</a>
            </li>
            <li class="">
                <a class="nav-link" href="/projects/group">Groups</a>
            </li>
            <li class="">
                <a class="nav-link @if ($status == 'Finished') tabs-link-active @endif" href="/projects/finished">Finished</a>
            </li>
            <li class="">
                <a class="nav-link @if ($status == 'Draft') tabs-link-active @endif" href="/projects/draft">Draft</a>
            </li>
        </ul>

        {{-- <div class="d-flex mt-3">
            <div class="w-100">
                <form action="" method="get" class="">
                    <div class="input-group">
                        <input type="text" class="form-control" name="search" placeholder="Search"
                            value="{{ $searchcompact }}">
                        <button class="btn btn-outline-secondary" type="submit" id="button-addon2">Search</button>
                    </div>
                </form>
            </div>
        </div> --}}

        <div class="card primary-box-shadow mt-3">
            <div class="card-body">
                <x-common.table id="projectTable">
                    <thead class="thead-light">
                        <tr class="table-secondary">
                            <th class="text-center align-items-center border-top-left" width="5%">No</th>
                            <th class="text-center" width="15%">Project</th>
                            <th class="text-center" width="15%">Company Name</th>
                            <th class="text-center" width="10%">PIC</th>
                            {{-- <th class="text-center" width="15%">Email / Phone</th> --}}
                            <th class="text-center">Complete Address</th>
                            @if ($status === 'Draft')
                                <th class="text-center">Created By</th>
                            @endif
                            <th class="text-center not-export border-top-right" width="7%">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($projects as $key => $project)
                            <tr>
                                <td class="text-center">{{ $key + 1 }}</td>
                                <td>
                                    <div>
                                        <strong>Name:</strong> {{ $project->name }}
                                        {{ $project->po_number ? '- PO ' . $project->po_number : '' }}
                                    </div>
                                    <div><strong>Code:</strong> {{ $project->project_code }}</div>
                                    {{-- <div><strong>Project Budget:</strong> Rp.
                                        {{ number_format($project->value, 0, ',', '.') }}
                                    </div> --}}
                                </td>
                                <td>{{ $project->company_name }}</td>
                                <td>
                                    @php
                                        $pic = json_decode($project->pic);
                                    @endphp

                                    @if (json_last_error() === JSON_ERROR_NONE)
                                        <ul>
                                            @foreach ($pic as $person)
                                                <li>{{ $person->value }}</li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <p>{{ $project->pic }}</p>
                                    @endif
                                </td>

                                {{-- <td>
                                    <div>
                                        <strong>Email:</strong> {{ $project->email ? $project->email : '-' }}
                                    </div>
                                    <div>
                                        <strong>Phone:</strong> {{ $project->phone }}
                                    </div>
                                </td> --}}
                                <td>
                                    <div>
                                        <strong>Address:</strong>
                                        {{ $project->address }}
                                        {{ $project->city }}
                                        {{ $project->province }}
                                        {{ $project->post_code }}
                                    </div>
                                </td>
                                @if ($status === 'Draft')
                                    <td class="text-center">{{ $project->createdby->name }}</td>
                                @endif
                                <td class="text-left">
                                    <div>
                                        <a href="{{ route('history.project', ['id' => $project->id]) }}" target="_blank"
                                            class="w-100 btn btn-secondary btn-sm">Riwayat</a>
                                    </div>
                                    @hasanyrole('it|top-manager|manager')
                                        <div class="mt-1">
                                            <a class="w-100 btn btn-outline-info btn-sm"
                                                href="{{ route('projects.edit', $project->id) }}">
                                                {{ $status === 'Draft' ? 'Approve' : 'Edit' }}
                                            </a>
                                        </div>
                                    @endhasanyrole
                                    <div class="mt-1">
                                        <a class="w-100 btn btn-warning btn-sm"
                                            href="{{ route('projects.show', $project->id) }}">
                                            {{-- {{ $status === 'Draft' ? 'Approve' : 'Edit' }} --}}
                                            Overview
                                        </a>
                                    </div>
                                    {{-- <div class="mt-1">
                                        <a class="w-100 btn btn-success btn-sm"
                                            href="{{ route('boq.index', $project->id) }}">BOQ</a>
                                    </div> --}}
                                        <div class="mt-1">
                                            <a class="w-100 btn btn-success btn-sm"
                                                href="{{ route('project.task', $project->id) }}">WBS</a>
                                        </div>
                                    @if ($project->status != 'Finished' && auth()->user()->hasTopLevelAccess())
                                        <div class="mt-1">
                                            <a class="w-100 btn btn-primary btn-sm"
                                                href="{{ route('project-finished', $project->id) }}">Finish</a>
                                        </div>
                                    @endif
                                    {{-- <div class="mt-1">
                                        <a class="w-100 btn btn-outline-success btn-sm"
                                            href="{{ route('monitor-project', $project->id) }}">Monitoring</a>
                                    </div> --}}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </x-common.table>
            </div>
        </div>
    </div>
@endsection
