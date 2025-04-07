@php
    use App\Models\Inventory;use App\Models\PurchaseRequest;use App\Models\Task;use App\Permissions\Permission;use Carbon\Carbon;
    $cutoffDate = Carbon::parse('2025-01-20');
@endphp

<div>
    @if (Session::has('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <p>{{ Session::get('success') }}</p>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(Session::has('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <p>{{ Session::get('error') }}</p>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @php
        $finishDate = Carbon::parse($taskData->finish_date);
        $today = Carbon::today();
        $daysLeft = $today->diffInDays($finishDate, false);

        $warning = $daysLeft <= 10 && $daysLeft >= 0;
    @endphp

    @if ($taskData->status != 'Finish' && $warning)
        <div style="background-color: #f8d7da; color: #721c24; padding: 10px; border-radius: 5px;">
            <strong>Warning:</strong> Tersisa {{ $daysLeft }} hari lagi sebelum deadline
        </div>
    @endif
    <a href="{{ route('project.task', $project->id) }}" class="third-color-sne"> <i
            class="fa-solid fa-chevron-left fa-xs"></i> Back</a>
    <h1 class="primary-color-sne mt-3">Monitoring {{ $taskData->task_number }}</h1>
    <div class="card p-4 primary-box-shadow mt-3">
        <div class="row">
            <div class="col-md-6">
                <p><strong>PROJECT:</strong> {{ $taskData->project->name }}</p>
                <p><strong>WBS No: {{ $taskData->task_number }}</strong></p>
                <p><strong>WBS Name: {{ $taskData->task }}</strong></p>
            </div>
            <div class="col-md-6 text-start">
                <p><strong>Start: {{ $taskData->start_date }}</strong></p>
                <p><strong>WBS Status: {{ $taskData->status }}</strong></p>
            </div>
            @if ($checkPurchaseRequest && $taskData->status != 'Finish')
                <div class="d-flex justify-content-center justify-content-md-end">
                    <a href="{{ route('task-monitoring-boq.print', $taskData->id) }}" target="_blank"
                       class="btn-block hidden-sm hidden-md hidden-lg">
                        Print
                    </a>
                </div>
            @endif
        </div>
    </div>

    <div class="d-flex justify-content-center mt-3 mb-4">
        <div class="tab-container">
            <ul class="nav nav-pills rounded-pill p-1" id="tabMenu">
                {{-- <li class="nav-item">
                    <button class="nav-link active" id="monitoring-tab" data-bs-toggle="tab"
                            data-bs-target="#monitoring"
                            type="button" role="tab" aria-controls="monitoring" aria-selected="true">
                        All Monitoring
                    </button>
                </li> --}}
                <li class="nav-item">
                    <button class="nav-link active" id="boq-tab" data-bs-toggle="tab" data-bs-target="#boq" type="button"
                            role="tab" aria-controls="boq" aria-selected="true">
                        Bill of Quantity
                    </button>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('monitoring-purchase-request', $taskData->id) }}">
                        Purchase Request
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <div class="tab-content mt-3">
        {{-- <div class="tab-pane fade show active" id="monitoring" role="tabpanel" aria-labelledby="monitoring-tab">
            @forelse ($prData as $prNo => $pr)
                @php
                    $prNoSanitized = str_replace(['/', ' '], '-', $prNo);
                @endphp

                <div class="card primary-box-shadow" wire:key="{{ $prNo }}">
                    <div class="card-header d-flex justify-content-between align-items-center"
                         style="cursor: pointer;"
                         onclick="toggleCollapse('monitoring', '{{ $prNoSanitized }}')">

                        <h5 class="mb-0">PR No: {{ $prNo }}</h5>
                        <i class="fa fa-chevron-down rotate-icon" id="icon-monitoring-{{ $prNoSanitized }}"></i>
                    </div>

                    <div id="collapse-monitoring-{{ $prNoSanitized }}" class="collapse-transition">
                        @livewire('task-monitoring-purchase-request', [
                        'prNo' => $prNo,
                        'pr' => $pr,
                        'taskData' => $taskData,
                        'taskName' => $taskName,
                        'taskList' => $taskList
                        ], key($prNo))
                    </div>
                </div>
            @empty
                <p class="text-center text-danger bg-white p-3 rounded fw-bold primary-box-shadow">No PR data found</p>
            @endforelse

        </div> --}}
        {{-- <div class="tab-pane fade" id="request" role="tabpanel" aria-labelledby="request-tab">
            @forelse ($prData as $prNo => $pr)
                @php
                    $prNoSanitized = str_replace(['/', ' '], '-', $prNo);
                @endphp

                <div class="card primary-box-shadow" wire:key="{{ $prNo }}">
                    <div class="card-header d-flex justify-content-between align-items-center"
                         style="cursor: pointer;"
                         onclick="toggleCollapse('request', '{{ $prNoSanitized }}')">

                        <h5 class="mb-0">PR No: {{ $prNo }}</h5>
                        <i class="fa fa-chevron-down rotate-icon" id="icon-request-{{ $prNoSanitized }}"></i>
                    </div>

                    <div id="collapse-request-{{ $prNoSanitized }}" class="collapse-transition">
                        <div class="card-body">
                            @livewire('task-monitoring-purchase-request', [
                            'prNo' => $prNo,
                            'pr' => $pr,
                            'taskData' => $taskData,
                            'taskName' => $taskName,
                            'taskList' => $taskList
                            ], key($prNo))
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-center text-danger bg-white p-3 rounded fw-bold primary-box-shadow">No PR data found</p>
            @endforelse
        </div> --}}
        <div class="tab-pane fade" id="boq" role="tabpanel" aria-labelledby="boq-tab">

        </div>
    </div>
    <!-- Livewire Component Loaded Only Once -->
    <div id="task-monitoring-boq-container">
        @livewire('task-monitoring-boq', ['taskData' => $taskData])
    </div>

    <script>
        $(document).ready(function () {
            $('#task-monitoring-boq-container').appendTo('#monitoring');

            $(document).on('shown.bs.tab', '#boq-tab', function () {
                $('#task-monitoring-boq-container').appendTo('#boq');
                var event = new CustomEvent('tabChanged', {detail: {tabId: 'boq'}});
                window.dispatchEvent(event);
            });

            $(document).on('shown.bs.tab', '#monitoring-tab', function () {
                $('#task-monitoring-boq-container').appendTo('#monitoring');
                var event = new CustomEvent('tabChanged', {detail: {tabId: 'monitoring'}});
                window.dispatchEvent(event);
            });
        });
    </script>

</div>
