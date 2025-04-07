@extends('layouts.app')

@section('content')
    <div class="my-5">
        @if (request()->get('need-approval'))
            <a href="{{ route('suppliers.index', ['tab' => 'need-approval']) }}" class="third-color-sne"> <i
                    class="fa-solid fa-chevron-left fa-xs"></i> Back</a>
        @else
            <a href="{{ route('suppliers.index') }}" class="third-color-sne"> <i
                class="fa-solid fa-chevron-left fa-xs"></i> 
                Back</a>
        @endif
    </div>
    <div class="mt-2">
        <div class="row">
            <div class="col-lg-12 margin-tb">
                <div class="pull-left d-flex">
                    <h2>{{ $supplier->name }} </h2>
                    @if ($supplier->blacklist)
                        <div class="ms-2">
                            <span class="badge bg-danger">Blackisted</span>
                        </div>
                    @endif
                </div>

            </div>
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
        <div class="card primary-box-shadow">
            <div class="card-body">
                <div class="new-user-info">
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label class="form-label" for="cname">PIC</label>
                            <label type="text" class="form-control text-black">{{ $supplier->pic }}</label>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="form-label" for="cname">Term of Paymnet</label>
                            <label type="text" class="form-control text-black">{{ $supplier->term_of_payment }}</label>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="form-label" for="cname">Email</label>
                            <label type="text"
                                class="form-control text-black">{{ $supplier->email ? $supplier->email : '-' }}</label>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="form-label" for="cname">Phone</label>
                            <label type="text" class="form-control text-black">{{ $supplier->phone }}</label>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="form-label" for="cname">Address</label>
                            <label type="text"
                                class="form-control text-black">{{ $supplier->address ? $supplier->address : '-' }}</label>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="form-label" for="cname">NPWP</label>
                            <label type="text"
                                class="form-control text-black">{{ $supplier->npwp ? $supplier->npwp : '-' }}</label>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="form-label" for="cname">Bank Name</label>
                            <label type="text"
                                class="form-control text-black">{{ $supplier->bank_name ? $supplier->bank_name : '-' }}</label>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="form-label" for="cname">Account Number</label>
                            <label type="text"
                                class="form-control text-black">{{ $supplier->norek ? $supplier->norek : '-' }}</label>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="form-label" for="cname">Recommended By</label>
                            <label type="text"
                                class="form-control text-black">{{ $supplier->recommended_by ? $supplier->recommended_by : '-' }}</label>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="form-label" for="cname">City</label>
                            <label type="text" class="form-control text-black">{{ $supplier->city }}</label>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="form-label" for="cname">Surveyor Name</label>
                            <label type="text"
                                class="form-control text-black">{{ $supplier->surveyor_name ? $supplier->surveyor_name : '-' }}</label>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="form-label" for="cname">Post Code</label>
                            <label type="text"
                                class="form-control text-black">{{ $supplier->post_code ? $supplier->post_code : '-' }}</label>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="form-label" for="cname">Identity Card</label>
                            @if ($supplier->ktp_image)
                                <div class="form-control border-0">
                                    <a href="{{ asset('storage/' . $supplier->ktp_image) }}" target="_blank"
                                        class="text-decoration-underline"> <img
                                            src="{{ asset('storage/' . $supplier->ktp_image) }}" alt=""
                                            width="100px"></a>

                                </div>
                            @else
                                <label type="text" class="form-control text-black">-</label>
                            @endif
                        </div>
                        <div class="form-group col-md-12">
                            <label class="form-label" for="cname">Supporting Evidence</label>
                            <div class="row">
                                @forelse($supplier->additionalFiles as $file)
                                    <div class="col-md-3">
                                        <div class="form-control border-1">
                                            <a href="{{ asset('storage/' . $file->path) }}" target="_blank"
                                                class="text-decoration-underline"> <img
                                                    src="{{ asset('storage/' . $file->path) }}" alt=""
                                                    width="200px"></a>
                                        </div>
                                    </div>
                                @empty
                                    <label type="text" class="form-control text-black">-</label>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>


        </div>
    @endsection
