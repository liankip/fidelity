@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    {{-- <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    You are a IT User.
                </div> --}}
                    <!-- Content wrapper -->
                    <div class="content-wrapper">
                        <!-- Content -->
                        <div class="container-xxl flex-grow-1 container-p-y">
                            <div class="row">
                                <!-- Order Statistics -->
                                <div class="col-md-6 col-lg-4 col-xl-4 order-0 mb-4 mt-4">
                                    <div class="card h-100">
                                        <div class="card-header d-flex align-items-center justify-content-between pb-0">
                                            <div class="card-title mb-0">
                                                <h5 class="m-0 me-2">Purchase Order Statistics</h5>
                                                <small class="text-muted">{{ $total_po_thisyear }} Total Purchase
                                                    Order</small>
                                            </div>
                                            <div class="dropdown">
                                                <button class="btn p-0" type="button" id="orederStatistics"
                                                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="bx bx-dots-vertical-rounded"></i>
                                                </button>
                                                <div class="dropdown-menu dropdown-menu-end"
                                                    aria-labelledby="orederStatistics">
                                                    <a class="dropdown-item" href="javascript:void(0);">Select All</a>
                                                    <a class="dropdown-item" href="javascript:void(0);">Refresh</a>
                                                    <a class="dropdown-item" href="javascript:void(0);">Share</a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <ul class="p-0 m-0">
                                                <li class="d-flex mb-4 pb-1">
                                                    <div class="avatar flex-shrink-0 me-3">
                                                        <img src="../assets/img/icons/unicons/chart.png" alt="User" class="rounded" />
                                                    </div>
                                                    <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                                        <div class="me-2">
                                                            <small class="text-muted d-block mb-1">Purchase
                                                                Order</small>
                                                            <h6 class="mb-0">Waiting For Approved</h6>
                                                        </div>
                                                        <div class="user-progress d-flex align-items-center gap-1">
                                                            <h6 class="mb-0">{{ $total_po_w_approved }}</h6>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li class="d-flex mb-4 pb-1">
                                                    <div class="avatar flex-shrink-0 me-3">
                                                        <img src="../assets/img/icons/unicons/cc-success.png" alt="User" class="rounded" />
                                                    </div>
                                                    <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                                        <div class="me-2">
                                                            <small class="text-muted d-block mb-1">Purchase
                                                                Order</small>
                                                            <h6 class="mb-0">Total Approved</h6>
                                                        </div>
                                                        <div class="user-progress d-flex align-items-center gap-1">
                                                            <h6 class="mb-0">{{ $total_po_approved }}</h6>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li class="d-flex mb-4 pb-1">
                                                    <div class="avatar flex-shrink-0 me-3">
                                                        <img src="../assets/img/icons/unicons/cc-warning.png" alt="User" class="rounded" />
                                                    </div>
                                                    <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                                        <div class="me-2">
                                                            <small class="text-muted d-block mb-1">Purchase
                                                                Order</small>
                                                            <h6 class="mb-0">Cancelled</h6>
                                                        </div>
                                                        <div class="user-progress d-flex align-items-center gap-1">
                                                            <h6 class="mb-0">{{ $total_po_cancelled }}</h6>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li class="d-flex mb-4 pb-1">
                                                    <div class="avatar flex-shrink-0 me-3">
                                                        <img src="../assets/img/icons/unicons/close.png" alt="User" class="rounded" />
                                                    </div>
                                                    <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                                        <div class="me-2">
                                                            <small class="text-muted d-block mb-1">Purchase
                                                                Order</small>
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
                                <!--/ Order Statistics -->

                                <!-- Transactions -->
                                <div class="col-md-6 col-lg-4 col-xl-4 order-0 mb-4 mt-4">
                                    <div class="card h-100">
                                        <div class="card-header d-flex align-items-center justify-content-between pb-0">
                                            <div class="card-title mb-0">
                                                <h5 class="m-0 me-2">Purchase Request Statistics</h5>
                                                <small class="text-muted">{{ $total_pr }} Total Purchase
                                                    Request</small>
                                            </div>
                                            <div class="dropdown">
                                                <button class="btn p-0" type="button" id="orederStatistics"
                                                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="bx bx-dots-vertical-rounded"></i>
                                                </button>
                                                <div class="dropdown-menu dropdown-menu-end"
                                                    aria-labelledby="orederStatistics">
                                                    <a class="dropdown-item" href="javascript:void(0);">Select All</a>
                                                    <a class="dropdown-item" href="javascript:void(0);">Refresh</a>
                                                    <a class="dropdown-item" href="javascript:void(0);">Share</a>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="card-body">
                                            <ul class="p-0 m-0">
                                                <li class="d-flex mb-4 pb-1">
                                                    <div class="avatar flex-shrink-0 me-3">
                                                        <img src="../assets/img/icons/unicons/chart.png" alt="User"
                                                            class="rounded" />
                                                    </div>
                                                    <div
                                                        class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                                        <div class="me-2">
                                                            <small class="text-muted d-block mb-1">Purchase
                                                                Request</small>
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
                                                            <small class="text-muted d-block mb-1">Purchase
                                                                Request</small>
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
                                                            <small class="text-muted d-block mb-1">Purchase
                                                                Request</small>
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
                                <!-- / Content -->


                            </div>
                        </div>
                    </div>
                @endsection
