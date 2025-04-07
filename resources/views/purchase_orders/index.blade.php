@extends('layouts.app')

@section('content')
    <div class="container mt-2">
        <div class="row">
            <div class="col-lg-12 margin-tb">
                <div class="pull-left">
                    <h2>{{ config('app.company', 'SNE') }} - ERP | Purchase Order Page</h2>
                </div>
                @if (auth()->user()->type == 'it' || auth()->user()->type == 'purchasing')
                    <div class="pull-right mb-2">
                        {{-- <a class="btn btn-success" href="{{ route('purchase_orders.create') }}"> Create purchaseorder</a> --}}
                        {{-- <a class="btn btn-success" href="{{ url('purchase_requests') }}"> Create purchaseorder</a> --}}
                    </div>
                @endif
            </div>
        </div>
        @if ($message = Session::get('success'))
            <div class="alert alert-success">
                <p>{{ $message }}</p>
            </div>
        @endif
        @if ($message = Session::get('error'))
            <div class="alert alert alert-danger mt-1 mb-1">
                <p>{{ $message }}</p>
            </div>
        @endif
        <div class="card">
            <div class="card-header">
                Purchase Order List
                @if (auth()->user()->type == 'it' || auth()->user()->type == 'purchasing')
                    | <a class="btn btn-success" href="{{ url('purchase_requests') }}"> New PO </a>
                @endif
            </div>
            <div class="card-body">
                <form action="" method="get" class="d-flex">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" name="search" placeholder=""
                            value="{{ $searchcompact }}" aria-label="Recipient's username" aria-describedby="button-addon2">
                        <button class="btn btn-outline-secondary" type="submit" id="button-addon2">Search</button>
                    </div>
                </form>

                <table class="table table-bordered">
                    <tr>
                        <th>No</th>
                        <th>PO No</th>
                        <th>PR No</th>
                        <th>Payment Metode</th>
                        <th>Project Name</th>
                        {{-- <th>Warehouse Name</th> --}}
                        <th>Vendor Name</th>
                        <th>Date Request</th>
                        <th>Status</th>
                        <th>Notes</th>
                        <th>Action</th>
                    </tr>
                    @foreach ($purchase_orders as $key => $purchaseorder)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $purchaseorder->po_no }}</td>
                            <td>{{ $purchaseorder->pr_no }}</td>
                            <td>{{ $purchaseorder->term_of_payment }}</td>
                            <td>
                                @if ($purchaseorder->project)
                                    {{ $purchaseorder->project->name }}
                                @endif
                            </td>
                            {{-- <td>{{ $purchaseorder->warehouse->name }}</td> --}}
                            <td>{{ $purchaseorder->supplier->name }}</td>
                            <td>{{ $purchaseorder->date_request }}</td>
                            <td>{{ $purchaseorder->status }}</td>
                            <td>
                                @if ($purchaseorder->status == 'Review')
                                    {{ $purchaseorder->remark_review }}
                                @endif
                                @if ($purchaseorder->status == 'Rejected')
                                    {{ $purchaseorder->remark_reject }}
                                @endif
                                @if ($purchaseorder->status != 'Review' || $purchaseorder->status != 'Review')
                                    {{ $purchaseorder->notes }}
                                @endif
                            </td>

                            <td>
                                {{-- <div class="dropdown">
                                    <a class="btn btn-secondary dropdown-toggle" href="#" role="button"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        Action
                                    </a>

                                    <ul class="dropdown-menu">
                                        @if ($purchaseorder->status == 'Approved' && (auth()->user()->type == 'it' || auth()->user()->type == 'finance'))
                                            <li>
                                                <form action="{{ route('cancel', $purchaseorder->id) }}" method="post">
                                                    @csrf
                                                    @method('put')
                                                    <button type="submit" class="btn btn-danger w-100"
                                                        style="width: 100%;">Cancel</button>
                                                </form>
                                            </li>
                                        @endif
                                        @if ($purchaseorder->status == 'Approved' || $purchaseorder->status == 'Completed')
                                            <li>
                                                <form class="w-100" action="{{ route('create_inv', $purchaseorder->id) }}"
                                                    method="post">
                                                    @csrf
                                                    @method('get')
                                                    <button type="submit" class="btn btn-warning w-100">Upload
                                                        Invoice</button>
                                                </form>
                                            </li>
                                        @endif
                                        @if ($purchaseorder->status == 'Approved')
                                            @if ($purchaseorder->deliver_status == 1)
                                                <li>
                                                    <form class="w-100"
                                                        action="{{ route('printpo_ds', $purchaseorder->id) }}"
                                                        method="post">
                                                        @csrf
                                                        @method('put')
                                                        <button type="submit" class="btn btn-success w-100">Print
                                                            PO</button>
                                                    </form>
                                                </li>
                                            @endif
                                            @if ($purchaseorder->ds_id == null || $purchaseorder->deliver_status == 0)
                                                <li>
                                                    <form class="w-100"
                                                        action="{{ route('printpo', $purchaseorder->id) }}" method="post">
                                                        @csrf
                                                        @method('put')
                                                        <button type="submit" class="btn btn-success w-100">Print
                                                            PO</button>
                                                    </form>
                                                </li>
                                            @endif
                                            @if ($purchaseorder->deliver_status == '0')
                                                @if ($purchaseorder->driver_memo_status == null)
                                                    <li>
                                                        <button type="button" class="btn btn-danger w-100"
                                                            data-toggle="modal"
                                                            data-target="#remark-memo-{{ $purchaseorder->id }}">Add
                                                            Supir</button>
                                                    </li>
                                                @endif
                                                @if ($purchaseorder->driver_memo_status == '1')
                                                    <li>
                                                        <form class="w-100"
                                                            action="{{ route('printmemo', $purchaseorder->id) }}"
                                                            method="post">
                                                            @csrf
                                                            @method('put')
                                                            <button type="submit" class="btn btn-success w-100">Print
                                                                Memo</button>
                                                        </form>
                                                    </li>
                                                @endif
                                            @endif

                                            @if (auth()->user()->type == 'it' || auth()->user()->type == 'adminlapangan' || auth()->user()->type == 'lapangan')
                                                <li>

                                                    <form class="w-100"
                                                        action="{{ route('create_do', $purchaseorder->id) }}"
                                                        method="post">
                                                        @csrf
                                                        @method('get')
                                                        <button type="submit" class="btn btn-warning w-100">Upload Surat
                                                            Jalan</button>
                                                    </form>
                                                </li>
                                            @endif
                                        @endif
                                        @if ($purchaseorder->status == 'New' || $purchaseorder->status == 'Review' || $purchaseorder->status == 'New With Delivery Services')
                                            @if (auth()->user()->type == 'it' || auth()->user()->type == 'purchasing')
                                                @if ($purchaseorder->deliver_status == '1')
                                                    @if ($purchaseorder->status == 'New' || $purchaseorder->status == 'Review')
                                                        <li>
                                                            <button type="button" class="btn btn-danger w-100"
                                                                data-toggle="modal"
                                                                data-target="#remark-ds-{{ $purchaseorder->id }}">Jasa
                                                                Pengiriman</button>
                                                        </li>
                                                    @endif
                                                    @if ($purchaseorder->status == 'New With Delivery Services')
                                                        <li>
                                                            <form class="w-100"
                                                                action="{{ route('ajukan', $purchaseorder->id) }}"
                                                                method="post">
                                                                @csrf
                                                                @method('put')
                                                                <button type="submit" class="btn btn-success w-100">Ajukan
                                                                    PO</button>
                                                            </form>
                                                        </li>
                                                    @endif
                                                @endif
                                                @if ($purchaseorder->deliver_status == '0')
                                                    <li>
                                                        <form class="w-100"
                                                            action="{{ route('ajukan', $purchaseorder->id) }}"
                                                            method="post">
                                                            @csrf
                                                            @method('put')
                                                            <button type="submit" class="btn btn-success w-100">Ajukan
                                                                PO</button>
                                                        </form>
                                                    </li>
                                                @endif
                                                <li>
                                                    <a class="btn btn-primary w-100"
                                                        href="{{ route('purchase_orders.edit', $purchaseorder->id) }}">Edit</a>
                                                </li>
                                            @endif
                                        @endif
                                        @if ($purchaseorder->status == 'Arrived')
                                            <li>
                                                <a class="btn btn-danger w-100" href="#">Retur</a>
                                            </li>
                                        @endif


                                        @if (auth()->user()->type == 'it' || auth()->user()->type == 'manager')
                                        @endif

                                        <li>
                                            <a class="btn btn-primary w-100"
                                                href="{{ url('po_details', $purchaseorder->id) }}">Detail</a>
                                        </li>
                                    </ul>
                                </div> --}}

                                @if ($purchaseorder->status == 'Approved' && (auth()->user()->type == 'it' || auth()->user()->type == 'finance'))
                                    <form action="{{ route('cancel', $purchaseorder->id) }}" method="post">
                                        @csrf
                                        @method('put')
                                        <button type="submit" class="btn btn-danger">Cancel</button>
                                    </form>
                                @endif
                                {{-- <form action="{{ route('purchase_orders.destroy',$purchaseorder->id) }}" method="Post"> --}}
                                @if ($purchaseorder->status == 'Approved' || $purchaseorder->status == 'Completed')
                                    <form action="{{ route('create_inv', $purchaseorder->id) }}" method="post">
                                        @csrf
                                        @method('get')
                                        <button type="submit" class="btn btn-warning">Upload Invoice</button>
                                    </form>
                                @endif
                                @if ($purchaseorder->status == 'Approved')
                                    @if ($purchaseorder->deliver_status == 1 || $purchaseorder->deliver_status == 2)
                                        <form target="__blank" action="{{ route('printpo_ds', $purchaseorder->id) }}"
                                            method="post">
                                            @csrf
                                            @method('put')
                                            <button type="submit" class="btn btn-success">Print PO</button>
                                        </form>
                                    @endif
                                    @if ($purchaseorder->ds_id == null && $purchaseorder->deliver_status == 0)
                                        <form target="__blank" action="{{ route('printpo', $purchaseorder->id) }}"
                                            method="post">
                                            @csrf
                                            @method('put')
                                            <button type="submit" class="btn btn-success">Print PO</button>
                                        </form>
                                    @endif
                                    @if ($purchaseorder->deliver_status == '0')
                                        {{-- @if ($purchaseorder->driver_memo_status == null)
                                            <button type="button" class="btn btn-danger" data-toggle="modal"
                                                data-target="#remark-memo-{{ $purchaseorder->id }}">Add Supir</button>
                                        @endif --}}
                                        {{-- @if ($purchaseorder->driver_memo_status == '1')
                                            <form action="{{ route('printmemo', $purchaseorder->id) }}" method="post">
                                                @csrf
                                                @method('put')
                                                <button type="submit" class="btn btn-success">Print Memo</button>
                                            </form>
                                        @endif --}}

                                        <form target="__blank" action="{{ route('printmemo', $purchaseorder->id) }}"
                                            method="post">
                                            @csrf
                                            @method('put')
                                            <button type="submit" class="btn btn-success">Print Memo</button>
                                        </form>
                                    @endif
                                    {{-- <a class="btn btn-success" href="{{ url('tespdf') }}">Print</a> --}}

                                    @if (auth()->user()->type == 'it' ||
                                        auth()->user()->type == 'adminlapangan' ||
                                        auth()->user()->type == 'lapangan')
                                        {{-- Submition lama --}}
                                        {{-- <form action="{{ route('arrivedpo', $purchaseorder->id) }}" method="post">
                                            @csrf
                                            @method('put')
                                            <button type="submit" class="btn btn-success">Submition</button>
                                        </form> --}}
                                        {{-- <a class="btn btn-warning" href="{{ route('submitions.create') }}">
                                            Upload Foto
                                        </a> --}}
                                        {{-- <a class="btn btn-warning" href="{{ url('upload-invoice') }}"> Upload Invoice</a> --}}

                                        <form action="{{ route('create_do', $purchaseorder->id) }}" method="post">
                                            @csrf
                                            @method('get')
                                            <button type="submit" class="btn btn-warning">Upload Surat Jalan</button>
                                        </form>
                                        {{-- <a class="btn btn-warning" href="{{ route('delivery_orders.create') }}"> Upload Surat Jalan</a> --}}

                                        {{-- <a class="btn btn-danger"
                                            href="{{ route('purchase_orders.edit', $purchaseorder->id) }}">Retur</a> --}}
                                    @endif
                                @endif
                                @if ($purchaseorder->status == 'New' ||
                                    $purchaseorder->status == 'Review' ||
                                    $purchaseorder->status == 'New With Delivery Services')
                                    @if (auth()->user()->type == 'it' || auth()->user()->type == 'purchasing')
                                        @if ($purchaseorder->deliver_status == '1')
                                            @if ($purchaseorder->status == 'New' || $purchaseorder->status == 'Review')
                                                <button type="button" class="btn btn-danger" data-toggle="modal"
                                                    data-target="#remark-ds-{{ $purchaseorder->id }}">Jasa
                                                    Pengiriman</button>
                                            @endif
                                            @if ($purchaseorder->status == 'New With Delivery Services')
                                                <form action="{{ route('ajukan', $purchaseorder->id) }}" method="post">
                                                    @csrf
                                                    @method('put')
                                                    <button type="submit" class="btn btn-success">Ajukan PO</button>
                                                </form>
                                            @endif
                                        @elseif ($purchaseorder->deliver_status == '2')
                                            @if ($purchaseorder->status == 'New' || $purchaseorder->status == 'Review')
                                                <button type="button" data-toggle="modal" class="btn btn-danger"
                                                    data-target="#remark-ds-{{ $purchaseorder->id }}">ongkos
                                                    Pengiriman</button>
                                            @endif
                                            @if ($purchaseorder->status == 'New With Delivery Services')
                                                <form action="{{ route('ajukan', $purchaseorder->id) }}" method="post">
                                                    @csrf
                                                    @method('put')
                                                    <button type="submit" class="btn btn-success">Ajukan PO</button>
                                                </form>
                                            @endif
                                        @endif
                                        @if ($purchaseorder->deliver_status == '0')
                                            <form action="{{ route('ajukan', $purchaseorder->id) }}" method="post">
                                                @csrf
                                                @method('put')
                                                <button type="submit" class="btn btn-success">Ajukan PO</button>
                                            </form>
                                            {{-- <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#change-po-no-{{ $purchaseorder->id }}">Ubah No PO</button> --}}
                                            {{-- <form action="{{ route('ajukan', $purchaseorder->id) }}" method="post">
                                            @csrf
                                            @method('put')
                                            <button type="submit" class="btn btn-success">Ajukan PO</button>
                                        </form> --}}
                                        @endif

                                        {{-- <a class="btn btn-primary"
                                            href="{{ route('purchase_orders.edit', $purchaseorder->id) }}">Edit</a> --}}
                                        {{-- <form action="{{ route('ajukan', $purchaseorder->id) }}" method="post">
                                            @csrf
                                            @method('put')
                                            <button type="submit" class="btn btn-success">Ajukan PO</button>
                                        </form> --}}
                                    @endif
                                @endif
                                @if ($purchaseorder->status == 'Arrived')
                                    <a class="btn btn-danger" href="#">Retur</a>
                                @endif


                                @if (auth()->user()->type == 'it' || auth()->user()->type == 'manager')
                                @endif


                                <a class="btn btn-primary" href="{{ url('po_details', $purchaseorder->id) }}">Detail</a>
                                @csrf
                                @method('DELETE')
                                {{-- <button type="submit" class="btn btn-danger">Delete</button> --}}
                                {{-- </form> --}}
                            </td>
                        </tr>
                        {{-- Modal Jasa Pengiriman Start --}}


                        <div class="modal fade" id="remark-ds-{{ $purchaseorder->id }}" tabindex="-1" role="dialog"
                            aria-labelledby="demoModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="demoModalLabel">
                                            @if ($purchaseorder->deliver_status == 1)
                                                Pengiriman Untuk PO
                                            @elseif ($purchaseorder->deliver_status == 2)
                                                Ongkir pengiriman Untuk PO
                                            @endif
                                            {{ $purchaseorder->po_no }}
                                        </h5>
                                        <button type="button" class="close" data-dismiss="modal" aria- label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="{{ route('up_ds', $purchaseorder->id) }}" method="post">
                                            <input type="text" name="ds_status"
                                                value="{{ $purchaseorder->deliver_status }}" style="display: none">
                                            {{-- Welcome, Websolutionstuff !! --}}
                                            @if ($purchaseorder->deliver_status == 1)
                                                <div class="form-group">
                                                    <strong>Jasa Pengiriman:</strong>
                                                    <select required name="ds_id" id="supplier_id"
                                                        class="js-example-basic-single form-control">
                                                        <option value="">Pilih Jasa Pengiriman</option>
                                                        @foreach ($ds as $val_ds)
                                                            <option value="{{ $val_ds->id }}">
                                                                {{ $val_ds->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('ds_id')
                                                        <div class="alert alert-danger mt-1 mb-1">Jasa pengiriman field is
                                                            required
                                                        </div>
                                                    @enderror

                                                </div>
                                            @endif

                                            {{-- <div class="form-group">
                                                <strong>value by unit:</strong>
                                                <input type="number" id="berat" name="berat" class="form-control" placeholder="">
                                                @error('berat')
                                                    <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                                                @enderror
                                            </div> --}}

                                            @if ($purchaseorder->deliver_status == 2)
                                                <div class="form-group">
                                                    <strong>Ongkos kirim:</strong>
                                                    {{-- <input type="text" id="ongkir" name="ongkir" class="form-control"
                                                    placeholder=""> --}}
                                                    <input required type="text" id="biaya" name="tarif_ds"
                                                        class="form-control" placeholder="Total Biaya">
                                                    @error('tarif_ds')
                                                        <div class="alert alert-danger mt-1 mb-1">Ongkos kirim field is
                                                            required
                                                        </div>
                                                    @enderror
                                                </div>
                                            @endif

                                            <script>
                                                $(document).ready(function() {
                                                    $("#supplier_id{{ $purchaseorder->id }}")
                                                });
                                            </script>
                                    </div>

                                    <div class="modal-footer">
                                        {{-- <button type="button" class="btn btn-secondary" data-
                                        dismiss="modal">Close</button> --}}
                                        {{-- <button type="button" class="btn btn-primary">Reject</button> --}}

                                        <span>

                                            @csrf
                                            @method('put')
                                            <button type="submit" class="btn btn-danger">save</button>
                                            </form>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- Modal Jasa Pengiriman End --}}
                        {{-- Modal Memo Start --}}
                        <div class="modal fade" id="remark-memo-{{ $purchaseorder->id }}" tabindex="-1" role="dialog"
                            aria-labelledby="demoModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="demoModalLabel">Detail Memo untuk PO
                                            {{ $purchaseorder->po_no }}</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria- label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="{{ route('up_driver_memo', $purchaseorder->id) }}" method="post">
                                            {{-- Welcome, Websolutionstuff !! --}}
                                            <div class="form-group">
                                                <strong>Plat Nomor:</strong>
                                                <input type="text" name="vehicle" class="form-control"
                                                    placeholder="Plat Nomor:">
                                                @error('vehicle')
                                                    <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="form-group">
                                                <strong>Nama Supir:</strong>
                                                <input type="text" name="driver_name" class="form-control"
                                                    placeholder="Nama Supir">
                                                @error('driver_name')
                                                    <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                                                @enderror
                                            </div>
                                    </div>

                                    <div class="modal-footer">
                                        {{-- <button type="button" class="btn btn-secondary" data-
                                        dismiss="modal">Close</button> --}}
                                        {{-- <button type="button" class="btn btn-primary">Reject</button> --}}

                                        <span>

                                            @csrf
                                            @method('put')
                                            <button type="submit" class="btn btn-danger">Add Driver</button>
                                            </form>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- Modal Memo End --}}
                        {{-- Modal Change PO No Start --}}
                        <div class="modal fade" id="change-po-no-{{ $purchaseorder->id }}" tabindex="-1"
                            role="dialog" aria-labelledby="demoModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="demoModalLabel">Ubah Nomor PO
                                            {{ $purchaseorder->po_no }}</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria- label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="{{ route('change_po_num', $purchaseorder->id) }}" method="post">
                                            {{-- Welcome, Websolutionstuff !! --}}
                                            <div class="form-group">

                                                <input type="text" name="new_po_num" class="form-control"
                                                    placeholder="{{ substr($purchaseorder->po_no, 0, 3) }}">

                                            </div>

                                            <div class="modal-footer">
                                                {{-- <button type="button" class="btn btn-secondary" data-
                                        dismiss="modal">Close</button> --}}
                                                {{-- <button type="button" class="btn btn-primary">Reject</button> --}}

                                                <span>

                                                    @csrf
                                                    @method('put')
                                                    <button type="submit" class="btn btn-danger">Ubah PO</button>
                                        </form>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Modal Change PO No End --}}
                    @endforeach
                </table>
                <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
                    integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous">
                </script>
                <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"
                    integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous">
                </script>
                <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"
                    integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous">
                </script>

                <script>
                    $(document).ready(function() {
                        // $("#itemselected").select2();
                        // $('#ds_id').select2({
                        //     theme: 'bootstrap-5',
                        //     "language": {
                        //         "noResults": function() {
                        //             return "No Results Found <a href='/delivery_services/create' class='btn btn-success' target='_blank'>Tambah Jasa Pengiriman</a>";
                        //         }
                        //     },
                        //     escapeMarkup: function(markup) {
                        //         return markup;
                        //     }
                        // });

                        var datads = @php
                            echo $ds;
                        @endphp;
                        console.log(datads);
                        // console.log(datads);
                        $("#supplier_id").change(function() {
                            if ($("#berat").val()) {
                                datads.forEach(element => {
                                    if (element.id == $("#supplier_id").val()) {
                                        $("#biaya").val(element.tarif_per_kg * $("#berat").val())
                                    }
                                });
                            }
                        })

                        $("#berat").keyup(function() {
                            if ($("#supplier_id").val()) {
                                datads.forEach(element => {
                                    if (element.id == $("#supplier_id").val()) {
                                        $("#biaya").val(element.tarif_per_kg * $("#berat").val())
                                    }
                                });
                            }
                        })





                    });
                </script>
            </div>
            <div class="card-footer">
                {{ $purchase_orders->links() }}
            </div>
        </div>
    </div>


@endsection
