<div class="">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="">
                <div class="content-wrapper">
                    <div class="">
                        <div class="row">
                            @hasanyrole('it|top-manager|manager|purchasing|finance')
                                <div class="col-md-12 order-0">
                                    <div class="row">
                                        <div>
                                            <div class="">
                                                <h2 class="text-muted">Welcome to Fidelity {{ Auth::user()->roles->first()->name }}</h2>
                                            </div>
                                        </div>
                                        {{-- <div class="font-bold text-xl mt-5">
                                            Today Attendance: {{ Carbon\Carbon::now()->format('l, d F Y') }}
                                        </div>
                                        <div class="col-md-3 order-0 mt-4">
                                            <div class="card shadow-sm" style="margin-bottom: 0rem!important;">
                                                <div
                                                    class="card-header d-flex align-items-center justify-content-between pb-0 bg-success">
                                                    <div class="card-title mb-4">
                                                        <h5 class="m-0 me-2 fw-normal text-white">Ontime</h5>
                                                    </div>
                                                </div>
                                                <div class="card-body mt-4 font-bold">
                                                    <a href="{{ route('hrd.attendance') }}">
                                                        <h5>{{ $ontime }}</h5>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3 order-0 mt-4">
                                            <div class="card shadow-sm" style="margin-bottom: 0rem!important;">
                                                <div
                                                    class="card-header d-flex align-items-center justify-content-between pb-0 bg-warning">
                                                    <div class="card-title mb-4">
                                                        <h5 class="m-0 me-2 fw-normal text-white">Late</h5>
                                                    </div>
                                                </div>
                                                <div class="card-body mt-4 font-bold">
                                                    <a href="{{ route('hrd.attendance') }}">
                                                        <h5>{{ $late }}</h5>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3 order-0 mt-4">
                                            <div class="card shadow-sm" style="margin-bottom: 0rem!important;">
                                                <div
                                                    class="card-header d-flex align-items-center justify-content-between pb-0 bg-primary">
                                                    <div class="card-title mb-4">
                                                        <h5 class="m-0 me-2 fw-normal text-white">Permission</h5>
                                                    </div>
                                                </div>
                                                <div class="card-body mt-4 font-bold">
                                                    <a href="{{ route('hrd.attendance') }}">
                                                        <h5>{{ $permission }}</h5>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3 order-0 mt-4">
                                            <div class="card shadow-sm" style="margin-bottom: 0rem!important;">
                                                <div
                                                    class="card-header d-flex align-items-center justify-content-between pb-0 bg-danger">
                                                    <div class="card-title mb-4">
                                                        <h5 class="m-0 me-2 fw-normal text-white">Not Filling</h5>
                                                    </div>
                                                </div>
                                                <div class="card-body mt-4 font-bold">
                                                    <a href="{{ route('hrd.attendance') }}">
                                                        <h5>{{ $not_fill }}</h5>
                                                    </a>
                                                </div>
                                            </div>
                                        </div> --}}
                                    </div>
                                </div>

                                {{-- <div class="font-bold text-xl mt-5">
                                    Purchase Request and Purchase Order
                                </div> --}}

                                {{-- <div class="col-md-8 order-0 mb-4">
                                    <div class="row">
                                        <div class="col-md-6 order-0 mb-4 mt-4">
                                            <div class="card shadow-sm">
                                                <div
                                                    class="bg-secondary card-header d-flex align-items-center justify-content-between">
                                                    <div class="card-title mb-0">
                                                        <h5 class="m-0 me-2 fw-normal text-white">Purchase Order Statistics
                                                        </h5>
                                                        <small class="text-white">
                                                            {{ $total_po_thisyear }} Total Purchase Order
                                                        </small>
                                                    </div>
                                                </div>
                                                <div class="card-body mt-4">
                                                    <ul class="p-0 m-0">
                                                        <li class="d-flex mb-4 pb-1">
                                                            <div class="avatar flex-shrink-0 me-3">
                                                                <img src="../assets/img/icons/unicons/chart.png"
                                                                    alt="User" class="rounded" />
                                                            </div>
                                                            <div
                                                                class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                                                <div class="me-2">
                                                                    <small class="text-muted d-block mb-1">
                                                                        Purchase Order
                                                                    </small>
                                                                    <h6 class="mb-0">Waiting For Approved</h6>
                                                                </div>
                                                                <div class="user-progress d-flex align-items-center gap-1">
                                                                    <h6 class="mb-0">{{ $total_po_w_approved }}</h6>
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <li class="d-flex mb-4 pb-1">
                                                            <div class="avatar flex-shrink-0 me-3">
                                                                <img src="../assets/img/icons/unicons/cc-success.png"
                                                                    alt="User" class="rounded" />
                                                            </div>
                                                            <div
                                                                class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                                                <div class="me-2">
                                                                    <small class="text-muted d-block mb-1">
                                                                        Purchase Order
                                                                    </small>
                                                                    <h6 class="mb-0">Total Approved</h6>
                                                                </div>
                                                                <div class="user-progress d-flex align-items-center gap-1">
                                                                    <h6 class="mb-0">{{ $total_po_approved }}</h6>
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <li class="d-flex mb-4 pb-1">
                                                            <div class="avatar flex-shrink-0 me-3">
                                                                <img src="../assets/img/icons/unicons/cc-warning.png"
                                                                    alt="User" class="rounded" />
                                                            </div>
                                                            <div
                                                                class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                                                <div class="me-2">
                                                                    <small class="text-muted d-block mb-1">
                                                                        Purchase Order
                                                                    </small>
                                                                    <h6 class="mb-0">Cancelled</h6>
                                                                </div>
                                                                <div class="user-progress d-flex align-items-center gap-1">
                                                                    <h6 class="mb-0">{{ $total_po_cancelled }}</h6>
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <li class="d-flex">
                                                            <div class="avatar flex-shrink-0 me-3">
                                                                <img src="../assets/img/icons/unicons/close.png"
                                                                    alt="User" class="rounded" />
                                                            </div>
                                                            <div
                                                                class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                                                <div class="me-2">
                                                                    <small class="text-muted d-block mb-1">
                                                                        Purchase Order
                                                                    </small>
                                                                    <h6 class="mb-0">Rejected</h6>
                                                                </div>
                                                                <div class="user-progress d-flex align-items-center gap-1">
                                                                    <h6 class="mb-0">{{ $total_po_rejected }}</h6>
                                                                </div>
                                                            </div>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 order-0 mb-4 mt-4">
                                            <div class="card shadow-sm">
                                                <div
                                                    class="bg-secondary card-header d-flex align-items-center justify-content-between">
                                                    <div class="card-title mb-0">
                                                        <h5 class="m-0 me-2 fw-normal text-white">Purchase Request
                                                            Statistics</h5>
                                                        <small class="text-white">
                                                            {{ $total_pr }} Total Purchase Request
                                                        </small>
                                                    </div>
                                                </div>

                                                <div class="card-body mt-4">
                                                    <ul class="p-0 m-0">
                                                        <li class="d-flex mb-4 pb-1">
                                                            <div class="avatar flex-shrink-0 me-3">
                                                                <img src="../assets/img/icons/unicons/chart.png"
                                                                    alt="User" class="rounded" />
                                                            </div>
                                                            <div
                                                                class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                                                <div class="me-2">
                                                                    <small class="text-muted d-block mb-1">
                                                                        Purchase Request
                                                                    </small>
                                                                    <h6 class="mb-0">New</h6>
                                                                </div>
                                                                <div class="user-progress d-flex align-items-center gap-1">
                                                                    <h6 class="mb-0">{{ $total_pr_new }}</h6>
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <li class="d-flex mb-4 pb-1">
                                                            <div class="avatar flex-shrink-0 me-3">
                                                                <img src="../assets/img/icons/unicons/cc-success.png"
                                                                    alt="User" class="rounded" />
                                                            </div>
                                                            <div
                                                                class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                                                <div class="me-2">
                                                                    <small class="text-muted d-block mb-1">
                                                                        Purchase Request
                                                                    </small>
                                                                    <h6 class="mb-0">Processed</h6>
                                                                </div>
                                                                <div class="user-progress d-flex align-items-center gap-1">
                                                                    <h6 class="mb-0">{{ $total_pr_process }}</h6>
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <li class="d-flex">
                                                            <div class="avatar flex-shrink-0 me-3">
                                                                <img src="../assets/img/icons/unicons/cc-warning.png"
                                                                    alt="User" class="rounded" />
                                                            </div>
                                                            <div
                                                                class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                                                <div class="me-2">
                                                                    <small class="text-muted d-block mb-1">
                                                                        Purchase Request
                                                                    </small>
                                                                    <h6 class="mb-0">Cancel</h6>
                                                                </div>
                                                                <div class="user-progress d-flex align-items-center gap-1">
                                                                    <h6 class="mb-0">{{ $total_pr_cancel }}</h6>
                                                                </div>
                                                            </div>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="card shadow-sm">
                                                <div class="card-header">
                                                    <h4>Total Purchase Orders {{ Carbon\Carbon::now()->year }}</h4>
                                                </div>
                                                <div class="card-body">
                                                    <div id="chart"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div> --}}
                                {{-- @livewire('dashboard.exchange') --}}
                                {{-- <livewire:dashboard.rewiwayat-po-dashboard> --}}
                                @endhasanyrole
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </div>
    @push('javascript')
        <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
        <script>
            const prices = @js($poPerMonth);
            const barOptions = {
                series: [{
                    name: "Total Purchase Order",
                    data: prices.map((item) => item.total_price),
                }, ],
                chart: {
                    type: "bar",
                    height: 350,
                },
                plotOptions: {
                    bar: {
                        horizontal: false,
                        columnWidth: "55%",
                        endingShape: "rounded",
                    },
                },
                dataLabels: {
                    enabled: false,
                },
                stroke: {
                    show: true,
                    width: 2,
                    colors: ["transparent"],
                },
                xaxis: {
                    categories: prices.map((item) => item.month),
                },
                yaxis: {
                    labels: {
                        formatter: function(val) {
                            return "Rp " + rupiahFormat(val);
                        }
                    }
                },
                fill: {
                    opacity: 1,
                },
                tooltip: {
                    y: {
                        formatter: function(val) {
                            return "Rp " + rupiahFormat(val);
                        },
                    },
                },
            };

            var chart = new ApexCharts(document.querySelector('#chart'), barOptions);
            chart.render();

            function rupiahFormat(num) {
                return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            }
        </script>
    @endpush
</div>
