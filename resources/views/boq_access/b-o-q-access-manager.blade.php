@extends('layouts.app')

@section('content')
    <div class="row ">
        <div class="col-sm-12 col-md-6 mx-auto">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">
                        @if (auth()->user()->type == 'it' || auth()->user()->type == 'manager')
                            {{ $access->user->name }} has requested access for {{ $access->action }} BOQ form project
                            <b>{{ $access->project->name }}</b>
                        @else
                            You have requested access for edit BOQ form project
                            <b>{{ $access->project->name }}</b>
                        @endif

                    </h5>
                    <p>
                        @if (auth()->user()->type == 'it' || auth()->user()->type == 'manager')
                            Please approve the request to give access to {{ $access->user->name }}.
                        @else
                            Please wait for approval from Manager.
                        @endif
                    </p>
                    @if ($access->status == 'pending')
                        @if (auth()->user()->type == 'it' || auth()->user()->type == 'manager')
                            <div class="d-flex gap-2 ">
                                <form action="{{ route('boq.access.submit-approval', $access->project->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="access_id" value="{{$access->id}}">
                                    <input type="hidden" name="is_approve" value="1">
                                    <button type="submit" class="btn btn-success">Approve</button>
                                </form>
                                <form action="{{ route('boq.access.submit-approval', $access->project->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="access_id" value="{{$access->id}}">
                                    <input type="hidden" name="is_approve" value="0">
                                    <button type="submit" class="btn btn-danger">Reject</button>
                                </form>
                            </div>
                        @endif
                    @else
                        @if ($access->status == 'approved')
                            <div>
                            <span class="badge text-bg-success">
                                <i class="fas fa-check"></i>
                                Approved
                            </span>
                            </div>
                        @else
                            <div>
                            <span class="badge text-bg-danger">
                                <i class="fas fa-times"></i>
                                Rejected
                            </span>
                            </div>
                        @endif
                    @endif

                </div>
            </div>
        </div>
    </div>
@endsection
