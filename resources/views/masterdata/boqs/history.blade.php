@extends('layouts.app')

@section('content')
    <div class="mt-2">
        <div class="row">
            <div class="col-lg-12 margin-tb">
                <a class="btn btn-danger mb-5" href="{{ route('boq.index', $project->id) }}">
                    <i class="fas fa-arrow-left"></i>
                    Back
                </a>
                <div class="pull-left d-flex justify-content-between">
                    <div class=" ">
                        <h2 class="text-black">History <a class="text-decoration-none text-black"
                                                          href="{{ route('projects.index') }}">BOQ</a></h2>
                        <h4 class="text-secondary"><strong>{{ $project->name }}</strong></h4>
                    </div>
                    @if(auth()->user()->hasTopLevelAccess())
                        <div>
                            <a class="btn btn-primary" href="{{ route('boq.access.index', $project->id) }}">Daftar User
                                Akses </a>
                        </div>
                    @endif
                </div>
                <hr>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <table class="table table-borderless">
                    <thead>
                    <tr class="table-primary">
                        <th>Version</th>
                        <th>Date</th>
                        <th>Action By</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($histories as $history)
                        <tr>
                            <td>{{ $history['version'] }}</td>
                            <td>{{ $history['date'] }}</td>
                            <td>{{ $history['action_by'] }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center">No Data</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
