@php use Carbon\Carbon; @endphp
<div>
    <div class="row align-items-center">
        <div class="col-md-6">
            <h2 class="primary-color">Minutes Of Meeting (MoM)</h2>
        </div>
        <div class="col-md-6">
            <div class="input-group search-input float-md-end" style="max-width: 300px;">
                <input
                    type="text"
                    class="form-control"
                    wire:model.live="search"
                    placeholder="Search"
                    style="border-color: #838383; border-radius: 20px 0 0 20px; border-right: none;"
                >
                <button class="input-group-text"
                      style="border-color: #838383; background-color: white; border-radius: 0 20px 20px 0; border-left: none;">
                    <i class="fas fa-search" style="color: #247FF1;"></i>
                </button>
            </div>
        </div>
    </div>

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
                        <th class="border-top-right">Detail</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse ($meetings as $index => $meeting)
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
                                <a href="{{ route('minute-of-meeting.detail', $meeting->id) }}"
                                   class="btn btn-outline-primary btn-sm">View</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">No records found.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center mt-3">
                {{ $meetings->links() }}
            </div>
        </div>
    </div>
</div>
