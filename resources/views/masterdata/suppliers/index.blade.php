@extends('layouts.app')

@section('content')
    <div class="mt-2">
        <div class="row">
            <div class="col-lg-12 margin-tb">
                <div class="pull-left">
                    <h2 class="primary-color-sne">Supplier</h2>
                </div>
                <div class="pull-right mt-5">
                    @if (auth()->user()->hasGeneralAccess())
                        <a class="btn btn-success" href="{{ route('suppliers.create') }}"><i class="fa-solid fa-plus pe-2"></i> Create Supplier</a>
                    @endif
                </div>
            </div>
        </div>
        <div class="my-5">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="all-suppliers-tab" data-bs-toggle="tab"
                        data-bs-target="#all-suppliers" type="button" role="tab" aria-controls="all-suppliers"
                        aria-selected="true">All Suppliers
                    </button>
                </li>
                <li class="nav-item position-relative" role="presentation">
                    <button class="nav-link" id="need-approval-tab" data-bs-toggle="tab" data-bs-target="#need-approval"
                        type="button" role="tab" aria-controls="need-approval" aria-selected="true">Need Approval
                    </button>
                    @if ($supplierNeedApproval->count() > 0)
                        <span class="badge bg-danger position-absolute top-0 start-100 rounded-circle"
                            style="font-size: 10px">
                            {{ $supplierNeedApproval->count() }}
                        </span>
                    @endif
                </li>
            </ul>
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
        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="all-suppliers" role="tabpanel" aria-labelledby="all-suppliers-tab">
                <div class="card primary-box-shadow">
                    <div class="card-body">
                        <div class="overflow-x-max">
                            <x-common.table id="supplierTable">
                                <thead class="thead-light">
                                    <tr class="table-secondary">
                                        <th class="align-middle text-center border-top-left">No</th>
                                        <th class="align-middle">Supplier Name</th>
                                        <th class="align-middle">PIC</th>
                                        <th class="align-middle">Term Of Payment</th>
                                        <th class="align-middle">Phone</th>
                                        <th class="align-middle">Address</th>
                                        <th class="align-middle">NPWP</th>
                                        <th class="align-middle">Account Number</th>
                                        <th class="align-middle">City</th>
                                        <th class="align-middle">Province</th>
                                        <th class="align-middle not-export border-top-right">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($suppliers as $key => $supplier)
                                        <tr @if ($supplier->blacklist) style="color:red;" @endif>

                                            <td class="text-center">{{ $key + 1 }}</td>
                                            <td>
                                                <a @if ($supplier->blacklist) style="color:red;" @endif
                                                    href="{{ route('suppliers.item-list', $supplier->id) }}">{{ $supplier->name }}</a>
                                            </td>
                                            <td>{{ $supplier->pic }}</td>
                                            <td>{{ $supplier->term_of_payment }}</td>
                                            <td>{{ $supplier->phone }}</td>
                                            <td>{{ $supplier->address }}</td>
                                            <td>{{ $supplier->npwp }}</td>
                                            <td>{{ $supplier->norek }}</td>
                                            <td>{{ $supplier->city }}</td>
                                            <td>{{ $supplier->province }}</td>
                                            <td class="d-flex flex-wrap gap-1">
                                                <form action="{{ route('blacklistsupplier', $supplier->id) }}"
                                                    method="post">
                                                    @csrf
                                                    @method('put')
                                                    <button type="submit" class="btn btn-danger btn-sm w-100 me-0">Blacklist
                                                    </button>
                                                </form>
                                                <a class="btn btn-outline-info btn-sm w-100 me-0"
                                                    href="{{ route('suppliers.edit', $supplier->id) }}">Edit</a>
                                                <a class="btn btn-secondary btn-sm w-100 me-0" target="_blank"
                                                    href="{{ route('history.supplier', ['id' => $supplier->id]) }}">Riwayat</a>
                                                <a class="btn btn-info btn-sm w-100 me-0"
                                                    href="{{ route('suppliers.show', $supplier->id) }}">Detail</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </x-common.table>
                        </div>

                    </div>

                    <div class="card-footer">
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="need-approval" role="tabpanel" aria-labelledby="need-approval-tab">
                <livewire:suppliers.approval-suppliers :suppliers="$supplierNeedApproval" />
            </div>
        </div>


    </div>
@endsection
