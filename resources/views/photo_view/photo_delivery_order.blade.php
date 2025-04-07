@extends('layouts.app')

@section('content')
    <div class="container mt-2">
        <div class="row">
            <div class="col-lg-12 margin-tb">
                <div class="pull-left">
                    <h2>{{ config('app.company', 'SNE') }} - ERP | Surat Jalan </h2>
                </div>
                <div class="pull-right mb-2">
                    <a class="btn btn-success" href="{{ route('create_do', ['id' => $po->id]) }}"> Upload Surat Jalan</a>
                    {{-- <a class="btn btn-success" href="{{ route('deliveryorders.import') }}"> Upload CSV</a>
                                        <a class="btn btn-success" href="{{ route('deliveryorders.export') }}"> Downnload as CSV</a> --}}
                </div>
            </div>
        </div>
        @if ($message = Session::get('success'))
            <div class="alert alert-success">
                <p>{{ $message }}</p>
            </div>
        @endif
        <div class="alert alert-success alert-dismissible fade show mb-2 d-none" role="alert">
            File berhasil di update
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <div class="card">
            <div class="card-header">
                PO No: {{ $po->po_no }} <a href="{{ url()->previous() }}" class="btn btn-success btn-sm">Back</a>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr>
                        <th>ID</th>
                        <th>Surat Jalan No</th>
                        <th>Surat Jalan Type</th>
                        <th>Referensi</th>
                        <th>Tanggal</th>
                        <th>Foto Surat Jalan</th>

                        {{-- <th width="280px">Action</th> --}}
                    </tr>
                    @foreach ($delivery_orders as $deliveryorder)
                        <tr>
                            <td>{{ $deliveryorder->id }}</td>
                            <td>{{ $deliveryorder->do_no }}</td>
                            <td>{{ $deliveryorder->do_type }}</td>
                            <td>{{ $deliveryorder->referensi }}</td>
                            <td>{{ $deliveryorder->created_at }}</td>
                            <td>
                                {{-- <a href="/{{ $deliveryorder->do_pict }}" target="_blank"> Click Here</a> --}}
                                <a class="dropdown-item" href="/{{ $deliveryorder->do_pict }}" target="_blank">
                                    {{-- <img src="{{ asset($deliveryorder->do_pict) }}" class="w-100" style="max-height: 200px" alt=""> --}}
                                    <a href="{{ asset($deliveryorder->do_pict) }}" target="_blank" class="btn btn-primary">Click here</a>
                                </a>
                                @if (
                                    !auth()->user()->hasRole(\App\Roles\Role::MANAGER) ||
                                        !auth()->user()->hasRole(\App\Roles\Role::TOP_MANAGER))
                                        <a href="#" class="openmodal btn btn-secondary" data-toggle="modal" data-target="#exampleModal" data-id="{{ $deliveryorder->id }}">Edit Foto DO</a>
                                    {{-- <button type="button" class="openmodal btn btn-secondary" data-toggle="modal"
                                        data-target="#exampleModal" data-id="{{ $val->id }}"
                                        data-title="Edit Foto Barang">
                                        Edit Foto Barang
                                    </button> --}}
                                @endif
                            </td>
                            {{-- <td><a href="#" class="openmodal" data-toggle="modal" data-target="#exampleModal" data-id="{{ $deliveryorder->id }}">Update Photo</a></td> --}}

                        </tr>
                    @endforeach
                </table>

            </div>
            <div class="card-footer"></div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <form class="modal-content"  id="uploadForm" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="recordId" name="recordId" value="">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Edit Foto DO</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <label class="form-label" for="foto_barang">Upload Gambar</label>
                        <input type="file" id="foto_barang" name="foto_barang"
                            class="form-control" accept="image/*" required>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary closemodal">Close</button>
                        <button type="submit" class="btn btn-primary">Upload</button>
                    </div>
                </form>
            </div>
        </div>

        <script>
            $('.openmodal').on('click', function() {
                var id = $(this).data('id');
                $('#recordId').val(id);
                $('#exampleModal').modal('show');
            });
    
            $('.closemodal, .close').on('click', function(){
                $('#exampleModal').modal('hide');
            })
    
            $('#exampleModal').on('hidden.bs.modal', function () {
                $('#foto_barang').val(''); // Clear the file input field
            });
    
                // Handle form submission with AJAX
            $('#uploadForm').on('submit', function(event) {
                event.preventDefault();
                var formData = new FormData(this);
                var recordId = $('#recordId').val();
    
                $.ajax({
                    url: "{{ route('do.updateFile', '') }}/" + recordId,
                    method: "POST", // Use POST method to override with PUT
                    data: formData,
                    contentType: false,
                    processData: false,
                    headers: {
                        'X-HTTP-Method-Override': 'PUT' // Method override header
                    },
                    success: function(response) {
                        $('#exampleModal').modal('hide');
                        $('.alert-success').removeClass('d-none');
                        window.location.reload()
                    },
                    error: function(xhr) {
                        alert('Error updating file');
                    }
                });
            });
        </script>

        {!! $delivery_orders->links() !!}
    @endsection
