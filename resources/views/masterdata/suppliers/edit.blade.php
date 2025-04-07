@extends('layouts.app')

@section('content')
    <div class="container mt-2">
        <div class="row">
            <div class="col-lg-12 margin-tb">
                <div class="pull-left">
                    <a href="{{ route('suppliers.index') }}" class="third-color-sne"> <i
                            class="fa-solid fa-chevron-left fa-xs"></i>
                        Back</a>
                    <h2 class="primary-color-sne">Edit Supplier</h2>
                </div>
            </div>
        </div>
        @if (session('status'))
            <div class="alert alert-success mb-1 mt-1">
                {{ session('status') }}
            </div>
        @endif
        <div class="card mt-5 primary-box-shadow">
            <form action="{{ route('suppliers.update', $supplier->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="card-body">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Supplier Name<span class="text-danger">*</span></strong>
                                <input type="text" name="name" value="{{ $supplier->name }}" class="form-control"
                                    placeholder="supplier Name">
                                @error('name')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>PIC<span class="text-danger">*</span></strong>
                                <input type="text" name="pic" value="{{ $supplier->pic }}" class="form-control"
                                    placeholder="PIC">
                                @error('pic')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Term Of Payment<span class="text-danger">*</span></strong>
                                <select required class="form-select @error('term_of_payment') is-invalid @enderror"
                                    name="term_of_payment" aria-label="Default select example">
                                    <option value="">Open this select menu</option>
                                    <option value="CoD" {{ $supplier->term_of_payment == 'CoD' ? 'selected' : '' }}>CoD
                                    </option>
                                    <option value="Cash" {{ $supplier->term_of_payment == 'Cash' ? 'selected' : '' }}>
                                        Cash
                                    </option>
                                    <option value="7 hari" {{ $supplier->term_of_payment == '7 Hari' ? 'selected' : '' }}>7
                                        hari
                                    </option>
                                    <option value="30 hari"
                                        {{ $supplier->term_of_payment == '30 hari' ? 'selected' : '' }}>
                                        30 hari
                                    </option>
                                    <option value="DP 7 hari"
                                        {{ $supplier->term_of_payment == 'DP 7 hari' ? 'selected' : '' }}>DP 7 hari
                                    </option>
                                    <option value="DP 30 hari"
                                        {{ $supplier->term_of_payment == 'DP 30 hari' ? 'selected' : '' }}>
                                        DP 30 hari
                                    </option>
                                    <option value="Termin 2"
                                        {{ $supplier->term_of_payment == 'Termin 2' ? 'selected' : '' }}>
                                        Termin 2
                                    </option>
                                    <option value="Termin 3"
                                        {{ $supplier->term_of_payment == 'Termin 3' ? 'selected' : '' }}>
                                        Termin 3
                                    </option>
                                </select>
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Email</strong>
                                <input type="email" name="email" class="form-control" placeholder="Email"
                                    value="{{ $supplier->email }}">
                                @error('email')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Phone<span class="text-danger">*</span></strong>
                                <input type="number" name="phone" class="form-control" placeholder="Phone"
                                    value="{{ $supplier->phone }}">
                                @error('phone')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Address</strong>
                                <input type="text" name="address" value="{{ $supplier->address }}" class="form-control"
                                    placeholder="Address">
                                @error('address')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>City<span class="text-danger">*</span></strong>
                                <input type="text" name="city" value="{{ $supplier->city }}" class="form-control"
                                    placeholder="City">
                                @error('city')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Province<span class="text-danger">*</span></strong>
                                <input type="text" name="province" value="{{ $supplier->province }}"
                                    class="form-control" placeholder="Province">
                                @error('province')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Kode Pos</strong>
                                <input type="number" name="post_code" value="{{ $supplier->post_code }}"
                                    class="form-control" placeholder="Kode Pos">
                                @error('post_code')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>KTP</strong>
                                <input type="file" name="ktp_image" class="form-control" accept="image/*">
                                @error('ktp_image')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            @if ($supplier->ktp_image)
                                <a href="{{ asset('storage/' . $supplier->ktp_image) }}" target="_blank" class="my-2">
                                    <img src="{{ asset('storage/' . $supplier->ktp_image) }}" alt=""
                                        height="200px">
                                </a>
                            @endif
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>NPWP <span class="text-danger">*</span></strong>
                                <input type="text" name="npwp" value="{{ $supplier->npwp }}" class="form-control"
                                    placeholder="NPWP">
                                @error('npwp')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Nama Bank</strong>
                                <input type="text" name="bank_name" value="{{ $supplier->bank_name }}"
                                    class="form-control" placeholder="Nama Bank">
                                @error('bank_name')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Nomor Rekening </strong>
                                <input type="text" name="norek" value="{{ $supplier->norek }}"
                                    class="form-control" placeholder="Nomor Rekening">
                                @error('norek')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Recommended By <span class="text-danger">*</span></strong>
                                <input type="text" name="recommended_by" value="{{ $supplier->recommended_by }}"
                                    class="form-control" placeholder="Name of the person recommending">
                                @error('recommended_by')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Surveyor Name <span class="text-danger">*</span></strong>
                                <input type="text" name="surveyor_name" value="{{ $supplier->surveyor_name }}"
                                    class="form-control" placeholder="Name of the person who surveyed the location">
                                @error('surveyor_name')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary ml-3"><i class="fa-solid fa-floppy-disk pe-2"></i>Save</button>
                </div>
            </form>
        </div>

    </div>
@endsection
{{-- </body>
</html> --}}
