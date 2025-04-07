@extends('layouts.app')

@section('content')
    <div class="container mt-2">
        <div class="row">
            <div class="col-lg-12 margin-tb">
                <div class="pull-left">
                    <h2>{{ config('app.company', 'SNE') }} - ERP | Validasi Berkas</h2>
                </div>
                {{-- <div class="pull-right mb-2">
                    <a class="btn btn-success" href="{{ route('submitions.create') }}"> Upload Foto Barang</a>
                </div> --}}
            </div>
        </div>
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
                        <th>No</th>
                        <th>PO No</th>
                        <th>Item Name</th>
                        <th>Qty</th>
                        <th>PIC Pengantar</th>
                        <th>Penerima</th>
                        <th>Tanggal</th>
                        <th>Tanggal Barang Sampai</th>
                        <th>Photo Barang</th>

                        {{-- <th width="280px">Action</th> --}}
                    </tr>
                    @foreach ($sh as $key => $val)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $val->purchaseorder->po_no }}</td>
                            <td>{{ $val->item_name }}</td>
                            <td>{{ $val->qty }}</td>
                            <td>{{ $val->pic_pengantar }}</td>
                            <td>{{ $val->penerima }}</td>
                            <td>{{ $val->created_at }}</td>
                            <td>{{ $val->actual_date ? $val->actual_date : '-' }}</td>

                            <td>
                                <div class="dropdown">
                                    <a class="dropdown-item" href="{{ $val->foto_barang }}" target="_blank">
                                        <img src="{{ asset($val->foto_barang) }}" class="w-100" alt="">
                                    </a>
                                    @if (
                                        !auth()->user()->hasRole(\App\Roles\Role::MANAGER) ||
                                            !auth()->user()->hasRole(\App\Roles\Role::TOP_MANAGER))
                                        <button type="button" class="openmodal btn btn-secondary" data-toggle="modal"
                                            data-target="#exampleModal" data-id="{{ $val->id }}"
                                            data-title="Edit Foto Barang">
                                            Edit Foto Barang
                                        </button>
                                    @endif
                                    {{-- <button class="btn btn-secondary dropdown-toggle" type="button"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        View
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="/{{ $val->foto_barang }}" target="_blank">Foto
                                                Barang</a>
                                        </li>
                                        <li><button type="button" class="dropdown-item openmodal" data-toggle="modal" data-target="#exampleModal" data-id="{{ $val->id }}" data-title="Edit Foto Barang">
                                            Edit Foto Barang
                                        </button>
                                    </li> --}}
                                    {{-- <li><a class="dropdown-item" href="/{{ $val->foto_left }}" target="_blank">Left view</a></li> --}}
                                    {{-- <li><a class="dropdown-item" href="/{{ $val->foto_right }}" target="_blank">Right view</a></li> --}}
                                    {{-- <li><a class="dropdown-item" href="/{{ $val->foto_back }}" target="_blank">Back view</a></li> --}}
                                    </ul>
                                </div>
                                {{-- <a href="/{{ $val->foto_barang }}" target="_blank"> Click Here</a> --}}

                            </td>


                        </tr>
                    @endforeach
                </table>

            </div>
            <div class="card-footer"></div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <form class="modal-content" id="uploadForm" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="recordId" name="recordId" value="">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Edit Foto Barang</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <label class="form-label" for="foto_barang">Upload Gambar</label>
                        <input type="file" id="foto_barang" name="foto_barang" class="form-control" accept="image/*"
                            required>
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

            $('.closemodal, .close').on('click', function() {
                $('#exampleModal').modal('hide');
            })

            $('#exampleModal').on('hidden.bs.modal', function() {
                $('#foto_barang').val(''); // Clear the file input field
            });

            // Handle form submission with AJAX
            $('#uploadForm').on('submit', function(event) {
                event.preventDefault();
                var formData = new FormData(this);
                var recordId = $('#recordId').val();

                $.ajax({
                    url: "{{ route('viewphoto_submition.update', '') }}/" + recordId,
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

        {{-- {!! $sh->links() !!} --}}
    @endsection
