@php use Carbon\Carbon; @endphp
<div>
    @foreach (['danger', 'warning', 'success', 'info'] as $key)
        @if (Session::has($key))
            <div class="alert alert-{{ $key }} alert-dismissible fade show mb-1 mt-1" role="alert">
                {{ Session::get($key) }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
    @endforeach

    <div class="row align-items-center">
        <div class="col-md-6">
            <h2 class="primary-color">Minutes Of Meeting (MoM) Approval</h2>
        </div>
        <div class="col-md-6">
            <div class="input-group search-input float-md-end" style="max-width: 300px;">
                <input type="text" class="form-control" wire:model.debounce.500ms="search" placeholder="Search"
                       style="border-color: #838383; border-radius: 20px 0 0 20px; border-right: none;">
                <button class="input-group-text"
                        style="border-color: #838383; background-color: white; border-radius: 0 20px 20px 0; border-left: none;">
                    <i class="fas fa-search" style="color: #247FF1;"></i>
                </button>
            </div>
        </div>
    </div>

    {{-- <div class="mt-3 mb-3">
        <div class="btn-group" role="group">
            <button style="border-color: #fff" class="btn {{ $filter == 'all' ? 'btn-active' : '' }}"
                    wire:click="$set('filter', 'all')">All
            </button>
            <button style="border-color: #fff" class="btn {{ $filter == 'approved' ? 'btn-active' : '' }}"
                    wire:click="$set('filter', 'approved')">Approved
            </button>
        </div>
    </div> --}}
    {{-- <ul class="nav nav-tabs mt-4">
        <li class="">
                <button class="nav-link @if ($filter == 'all') tabs-link-active @endif" wire:click="$set('filter', 'all')">All
        </button>
        </li>
        <li class="">
            <button class="nav-link @if ($filter == 'all') tabs-link-active @endif" wire:click="$set('filter', 'approved')">Approved

        </li>
    </ul> --}}

    <ul class="nav nav-tabs mb-3" style="cursor: pointer">
        <li class="">
            <a class="nav-link @if ($filter == 'all') tabs-link-active @endif" aria-current="page" wire:click="$set('filter', 'all')">All</a>
        </li>
        <li class="">
            <a class="nav-link @if ($filter == 'approved') tabs-link-active @endif" wire:click="$set('filter', 'approved')">Approved</a>
        </li>
    </ul>

    <div class="card">
        <div class="card-body">
            <div wire:poll.keep-alive>
                <div class="table-responsive primary-box-shadow">
                    <table class="table">
                        <thead class="thead-light">
                        <tr>
                            <th class="border-top-left">No</th>
                            <th>Date</th>
                            <th>Project Name</th>
                            <th>Meeting Title</th>
                            <th>Status</th>
                            <th>Approved By</th>
                            <th>Action</th>
                            <th class="border-top-right">Detail</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse ($meetings as $index => $meeting)
                            @php
                                $isRejectDisabled =
                                    (!is_null($meeting->approved_by) && !is_null($meeting->approved_by_2)) ||
                                    (is_null($meeting->approved_by) && !is_null($meeting->approved_by_2)) ||
                                    (is_null($meeting->approved_by_2) && $meeting->approved_by == auth()->user()->id) ||
                                    (!is_null($meeting->rejected_at) || $meeting->status == 'approved');
                            @endphp
                            <tr>
                                <td>{{ ($meetings->currentPage() - 1) * $meetings->perPage() + $index + 1 }}</td>
                                <td>{{ Carbon::parse($meeting->date)->format('d F Y') }}</td>
                                <td>{{ $meeting->project->name ?? '-' }}</td>
                                <td>{{ $meeting->meeting_title }}</td>
                                <td>
                                    @if ($meeting->status == 'approved')
                                        <span class="badge-custom badge-approved">Approved</span>
                                    @elseif($meeting->status == 'pending')
                                        <span class="badge-custom badge-pending">Pending</span>
                                    @elseif($meeting->status == 'waiting approval')
                                        <span class="badge-custom badge-waiting-approval">Waiting for
                                                Approval</span>
                                    @else
                                        <span class="badge-custom badge-rejected">rejected</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($meeting->rejected_by)
                                        <div class="row">
                                            <div class="col-12 text-center">
                                                <span class="text-danger">Rejected by:</span>
                                                <p class="text-danger">
                                                    {{ $meeting->rejected->name ?? '-' }}
                                                </p>
                                            </div>
                                        </div>
                                    @elseif ($meeting->approved_by == $meeting->approved_by_2)
                                        <div class="row">
                                            <div class="col-12 text-center">
                                                <span class="text-success">Approved by:</span>
                                                <p class="text-success">
                                                    {{ $meeting->approved->name ?? '-' }}
                                                </p>
                                            </div>
                                        </div>
                                    @else
                                        <div class="row">
                                            <div class="col-12 text-center">
                                                <span class="text-success">First Approved</span>
                                                <p class="text-success">{{ $meeting->approved->name ?? '-' }}</p>
                                                <span class="text-success">Second Approved</span>
                                                <p class="text-success">{{ $meeting->approved_2->name ?? '-' }}</p>
                                            </div>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex flex-column gap-2">
                                        @hasanyrole('it|manager|top-manager')
                                        @if (is_null($meeting->approved_by) && is_null($meeting->approved_at))
                                            <button data-bs-toggle="modal"
                                                    class="btn btn-sm w-100 {{ $meeting->status === 'approved' || $meeting->status === 'rejected' ? 'btn-secondary' : 'btn-success' }}"
                                                    @if ($meeting->status === 'approved' || $meeting->status === 'rejected') disabled
                                                    @endif
                                                    data-bs-target="#approvalModal-{{ $meeting->id }}">
                                                Approve
                                            </button>
                                        @elseif (
                                            $setting->multiple_mom_approval &&
                                                !is_null($meeting->approved_by) &&
                                                !is_null($meeting->approved_at) &&
                                                is_null($meeting->approved_by_2) &&
                                                is_null($meeting->approved_at_2))
                                            @if ($meeting->approved_by != auth()->user()->id)
                                                <button data-bs-toggle="modal"
                                                        class="btn btn-sm w-100 {{ $meeting->status === 'approved' || $meeting->status === 'rejected' ? 'btn-secondary' : 'btn-success' }}"
                                                        @if ($meeting->status === 'approved' || $meeting->status === 'rejected') disabled
                                                        @endif
                                                        data-bs-target="#approvalModal-{{ $meeting->id }}">
                                                    Approve
                                                </button>
                                            @else
                                                <button disabled class="btn btn-sm w-100 btn-secondary">approved
                                                </button>
                                            @endif
                                        @else
                                            <button disabled class="btn btn-sm w-100 btn-secondary">Approved
                                            </button>
                                        @endif
                                        @endhasanyrole

                                        @if ($isRejectDisabled)
                                            <button class="btn btn-sm w-100 btn-secondary" disabled>Reject</button>
                                        @else
                                            <button
                                                class="btn btn-sm w-100 btn-danger"
                                                wire:click="reject({{ $meeting->id }})">
                                                Reject
                                            </button>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <a href="{{ route('minute-of-meeting.detail', $meeting->id) }}"
                                       class="btn btn-outline-primary btn-sm">View</a>
                                </td>
                            </tr>

                            <x-common.modal id="approvalModal-{{ $meeting->id }}" title="Approval Minutes of Meeting">
                                <x-slot:modal-body>
                                    <div class="form-group">
                                        <div class="mb-3">
                                            <label for="description" class="form-label">Comment</label>
                                            <textarea class="form-control" wire:model="comment" id="description" rows="5"></textarea>
                                        </div>
                                    </div>
                                </x-slot:modal-body>
                                <x-slot:modal-footer>
                                    <x-common.modal.button-cancel/>
                                    <button type="submit" wire:loading.attr="disabled" class="btn btn-success"
                                            wire:click="approve({{ $meeting->id }})">Approve
                                    </button>
                                </x-slot:modal-footer>
                            </x-common.modal>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">No records found.</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="d-flex justify-content-center mt-3">
                {{ $meetings->links() }}
            </div>
        </div>
    </div>
</div>
