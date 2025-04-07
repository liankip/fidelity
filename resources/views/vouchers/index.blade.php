@php
    use App\Permissions\Permission;
    use Carbon\Carbon;
@endphp
@extends('layouts.app')

@section('content')
    <a href="{{ route('payment-submission') }}" class="third-color-sne"> <i class="fa-solid fa-chevron-left fa-xs"></i>
        Back</a>
    <h2 class="primary-color-sne">
        Pengajuan Pembayaran {{ $submission->type }}
        Tanggal {{ Carbon::parse($submission->created_at)->isoFormat(' D MMMM Y') }}
    </h2>
    <div class="card mt-5 primary-box-shadow">
        <x-common.notification-alert />
        <div class="card-body">
            @if ($submission->status == 'Draft')
                @can(Permission::PRINT_VOUCHER)
                    <livewire:voucher.print-voucher />
                @endcan
                @can(Permission::CREATE_VOUCHER)
                    <div class="d-flex justify-content-end">
                        <div class="btn-group mb-3">
                            <button type="button" class="btn btn-primary dropdown-toggle" id="orederStatistics"
                                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-plus"></i> Create
                            </button>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a href="{{ route('payment-submission.voucher.create', $submission) }}"
                                    class="dropdown-item">Voucher PO</a>
                                <a href="{{ route('payment-submission.additional.create', $submission) }}"
                                    class="dropdown-item">Voucher Non PO</a>
                            </div>
                        </div>
                    </div>
                @endcan
            @endif

            <livewire:voucher.list-voucher :submission="$submission" />
        </div>
    </div>
@endsection
