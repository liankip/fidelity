@extends('layouts.app')

@section('content')
    <div class="container mt-2">
        <div class="row">
            <div class="col-md-4 margin-tb">
                <div class="card">
                    <div class="card-header">
                        <h5>
                            KTP
                        </h5>
                    </div>
                    <div class="card-body d-flex justify-content-center">
                        <a href="{{asset('storage/'.$vendor->ktp_image)}}" target="_blank">
                            <img src="{{asset('storage/'.$vendor->ktp_image)}}" alt="" class="img-fluid">
                        </a>
                    </div>
                </div>
                @if($vendor->npwp_image)
                    <div class="card mt-3">
                        <div class="card-header">
                            <h5>
                                NPWP
                            </h5>
                        </div>
                        <div class="card-body d-flex justify-content-center">
                            <a href="{{asset('storage/'.$vendor->npwp_image)}}" target="_blank">
                                <img src="{{asset('storage/'.$vendor->npwp_image)}}" alt="" class="img-fluid">
                            </a>
                        </div>
                    </div>
                @endif
                @if($vendor->documents->count() > 0)
                    <div class="card mt-3">
                        <div class="card-header">
                            <h5>
                                Dokumen Lainnya
                            </h5>
                        </div>
                        <div class="card-body d-flex ">
                            <ul>
                                @foreach($vendor->documents as $document)
                                    <li>
                                        <a href="{{asset('storage/'.$document->path)}}" target="_blank">
                                            {{$document->file_name}}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

            </div>
            <div class="col-md-8 margin-tb">
                <div class="row">
                    <div class="card">
                        <div class="card-header">
                            <h5>
                                Vendor Detail
                            </h5>
                        </div>
                        <div class="card-body">
                            <table class="table border table-striped no-margin">
                                <tbody>
                                <tr class="project-overview">
                                    <td class="bold" width="30%">Company Name</td>
                                    <td>{{$vendor->name}}</td>
                                </tr>
                                <tr class="project-overview">
                                    <td class="bold">Address</td>
                                    <td>{{$vendor->address}}</td>
                                </tr>

                                <tr class="project-overview">
                                    <td class="bold">Email</td>
                                    <td>{{$vendor->email}}</td>
                                </tr>
                                <tr class="project-overview">
                                    <td class="bold">No Hp</td>
                                    <td>{{$vendor->telp}}</td>
                                </tr>
                                <tr class="project-overview">
                                    <td class="bold">Nama Bank</td>
                                    <td>{{$vendor->bank_name}}</td>
                                </tr>
                                <tr class="project-overview">
                                    <td class="bold">No Rekening</td>
                                    <td>{{$vendor->account_number}}</td>
                                </tr>

                                <tr class="project-overview">
                                    <td class="bold">Nama Pemilik Rekening</td>
                                    <td>{{$vendor->bank_owner_name}}</td>
                                </tr>
                                <tr class="project-overview">
                                    <td class="bold">Cabang Bank</td>
                                    <td>{{$vendor->bank_branch}}</td>
                                </tr>
                                <tr class="project-overview">
                                    <td class="bold">Term of Payment</td>
                                    <td>
                                        @if(count($vendor->top) > 0)
                                            @foreach($vendor->top as $term)
                                                <span class="badge bg-primary">{{$term}}</span>
                                            @endforeach
                                        @else
                                            <span class="badge bg-danger">No Term of Payment</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr class="project-overview">
                                    <td class="bold">Company Profile</td>
                                    <td>
                                        @if($vendor->company_profile)
                                            <a href="{{asset('storage/'.$vendor->company_profile)}}" target="_blank">
                                                <a href="{{ asset('storage/' . $vendor->company_profile) }}"
                                                   target="_blank"
                                                   data-bs-toggle="tooltip"
                                                   data-bs-title="View Company Profile"
                                                   class="text-decoration-underline"><i
                                                        class="fas fa-file"></i></a>
                                            </a>
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                                <tr class="project-overview">
                                    <td class="bold">Product Catalogue</td>
                                    <td>
                                        @if($vendor->product_catalogue)
                                            <a href="{{asset('storage/'.$vendor->product_catalogue)}}" target="_blank">
                                                <a href="{{ asset('storage/' . $vendor->product_catalogue) }}"
                                                   target="_blank"
                                                   data-bs-toggle="tooltip"
                                                   data-bs-title="View Product Catalogue"
                                                   class="text-decoration-underline"><i
                                                        class="fas fa-file"></i></a>
                                            </a>
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                                <tr class="project-overview">
                                    <td class="bold">Company Profile</td>
                                    <td>
                                        @if($vendor->company_profile)
                                            <a href="{{asset('storage/'.$vendor->company_profile)}}" target="_blank">
                                                <a href="{{ asset('storage/' . $vendor->company_profile) }}"
                                                   target="_blank"
                                                   data-bs-toggle="tooltip"
                                                   data-bs-title="View Company Profile"
                                                   class="text-decoration-underline"><i
                                                        class="fas fa-file"></i></a>
                                            </a>
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                                <tr class="project-overview">
                                    <td class="bold">Website Link</td>
                                    <td>
                                        @if($vendor->website_link)
                                            <a href="{{$vendor->website_link}}" target="_blank">
                                                <a href="{{$vendor->website_link}}" target="_blank"
                                                   data-bs-toggle="tooltip"
                                                   data-bs-title="View Website"
                                                   class="text-decoration-underline"><i
                                                        class="fas fa-globe"></i></a>
                                            </a>
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="card">
                        <div class="card-header">
                            <h5>
                                Sales Information
                            </h5>
                        </div>
                        <div class="card-body">
                            <table class="table border table-striped no-margin">
                                <tbody>
                                <tr class="project-overview">
                                    <td class="bold" width="30%">Email</td>
                                    <td>{{$vendor->sales_email ?  $vendor->sales_email : '-'}}</td>
                                </tr>
                                <tr class="project-overview">
                                    <td class="bold">No HP</td>
                                    <td>{{$vendor->sales_phone ?  $vendor->sales_phone : '-'}}</td>
                                </tr>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                @if($vendor->items->count()> 0)
                    <div class="row">
                        <div class="card">
                            <div class="card-header">
                                <h5>
                                    Vendor Items
                                </h5>
                            </div>
                            <div class="card-body">
                                <table class="table border table-striped no-margin">
                                    <thead>
                                    <tr>
                                        <th>Item Name</th>
                                        <th>Price</th>
                                        <th>Certificate</th>
                                        <th>Notes</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($vendor->items as $item)
                                        <tr>
                                            <td>{{$item->item_name}}</td>
                                            <td>{{rupiah_format($item->price)}}</td>
                                            <td>
                                                @if($item->certificate)
                                                    <a href="{{asset('storage/'.$item->certificate)}}" target="_blank">
                                                        <a href="{{ asset('storage/' . $item->certificate) }}"
                                                           target="_blank"
                                                           data-bs-toggle="tooltip"
                                                           data-bs-title="View Certificate"
                                                           class="text-decoration-underline"><i
                                                                class="fas fa-file"></i></a>
                                                    </a>
                                                @else
                                                    -
                                            @endif
                                            </td>
                                            <td>{{ $item->item_notes }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

    </div>
@endsection
