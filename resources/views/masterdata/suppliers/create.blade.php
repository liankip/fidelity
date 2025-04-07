@extends('layouts.app')
@section('content')
    <div class="container mt-2">
        <div class="row">
            <div class="col-lg-12 margin-tb">
                <div class="pull-left mb-2">
                    <a href="{{ route('suppliers.index') }}" class="third-color-sne"> <i
                        class="fa-solid fa-chevron-left fa-xs"></i> Back</a>
                    <h2 class="primary-color-sne">Add New Supplier</h2>
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
        <form action="{{ route('suppliers.store') }}" method="POST" enctype="multipart/form-data" id="supplierForm">
            @csrf
            <div class="card mt-5 primary-box-shadow">
                <div class="card-body">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Suplier Name<span class="text-danger">*</span></strong>
                                <input type="text" name="name" value="{{ old('name') }}"
                                    class="form-control @error('name') is-invalid @enderror" placeholder="supplier Name">
                                @error('name')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>PIC<span class="text-danger">*</span></strong>
                                <input type="text" name="pic" value="{{ old('pic') }}"
                                    class="form-control @error('pic') is-invalid @enderror" placeholder="PIC">
                                @error('pic')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Term Of Payment<span class="text-danger">*</span></strong>
                                {{-- <input type="text" name="term_of_payment" value="{{ old('term_of_payment') }}"
                                    class="form-control @error('term_of_payment') is-invalid @enderror"
                                    placeholder="Term Of Payment"> --}}
                                <select required class="form-select @error('term_of_payment') is-invalid @enderror"
                                    name="term_of_payment" aria-label="Default select example">
                                    <option value="">Open this select menu</option>
                                    <option value="CoD" {{ old('term_of_payment') == 'CoD' ? 'selected' : '' }}>CoD
                                    </option>
                                    <option value="Cash" {{ old('term_of_payment') == 'Cash' ? 'selected' : '' }}>Cash
                                    </option>
                                    <option value="7 hari" {{ old('term_of_payment') == '7 Hari' ? 'selected' : '' }}>7
                                        hari
                                    </option>
                                    <option value="30 hari" {{ old('term_of_payment') == '30 hari' ? 'selected' : '' }}>
                                        30 hari
                                    </option>
                                    <option value="DP 7 hari"
                                        {{ old('term_of_payment') == 'DP 7 hari' ? 'selected' : '' }}>DP 7 hari
                                    </option>
                                    <option value="DP 30 hari"
                                        {{ old('term_of_payment') == 'DP 30 hari' ? 'selected' : '' }}>
                                        DP 30 hari
                                    </option>
                                    <option value="Termin 2"
                                        {{ old('term_of_payment') == 'Termin 2' ? 'selected' : '' }}>
                                        Termin 2
                                    </option>
                                    <option value="Termin 3"
                                        {{ old('term_of_payment') == 'Termin 3' ? 'selected' : '' }}>
                                        Termin 3
                                    </option>
                                </select>
                                @error('term_of_payment')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Email</strong>
                                <input type="email" name="email" value="{{ old('email') }}" class="form-control"
                                    placeholder="Email">
                                @error('email')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Phone<span class="text-danger">*</span></strong>
                                <input type="number" name="phone" value="{{ old('phone') }}"
                                    class="form-control @error('phone') is-invalid @enderror" placeholder="Phone">
                                @error('phone')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Address</strong>
                                <input type="text" name="address" value="{{ old('address') }}" class="form-control"
                                    placeholder="Address">
                                @error('address')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>City<span class="text-danger">*</span></strong>
                                <input type="text" name="city" value="{{ old('city') }}"
                                    class="form-control @error('city') is-invalid @enderror" placeholder="City">
                                @error('city')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Province<span class="text-danger">*</span></strong>
                                <input type="text" name="province" value="{{ old('province') }}"
                                    class="form-control @error('province') is-invalid @enderror" placeholder="Province">
                                @error('province')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Kode Pos</strong>
                                <input type="number" name="post_code" value="{{ old('post_code') }}" class="form-control"
                                    placeholder="Kode Pos">
                                @error('post_code')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>KTP </strong>
                                <input type="file" name="ktp_image" class="form-control" accept="image/*">
                                @error('ktp_image')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>NPWP <span class="text-danger">*</span></strong>
                                <input type="text" name="npwp" value="{{ old('npwp') }}" class="form-control"
                                    placeholder="NPWP">
                                @error('npwp')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Nama Bank</strong>
                                <input type="text" name="bank_name" value="{{ old('bank_name') }}"
                                    class="form-control" placeholder="Nama Bank">
                                @error('bank_name')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Nomor Rekening</strong>
                                <input type="text" name="norek" value="{{ old('norek') }}" class="form-control"
                                    placeholder="Nomor Rekening">
                                @error('norek')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Recommended By <span class="text-danger">*</span></strong>
                                <input type="text" name="recommended_by" value="{{ old('norek') }}"
                                    class="form-control" placeholder="Name of the person recommending">
                                @error('recommended_by')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Surveyor Name <span class="text-danger">*</span></strong>
                                <input type="text" name="surveyor_name" value="{{ old('norek') }}"
                                    class="form-control" placeholder="Name of the person who surveyed the location">
                                @error('surveyor_name')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Supporting Evidence <span class="text-danger">*</span></strong>
                                <input type="file" name="additional_files[]" id="additionalFile" class="form-control"
                                    accept="image/*" multiple>
                            </div>
                            <em class="text-secondary">Contoh : Foto toko bersama dengan surveyor </em>
                            <div class="mt-3 row" id="filePreview"></div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary ml-3"><i class="fa-solid fa-floppy-disk pe-2"></i>Save</button>
                </div>
            </div>
        </form>
        <script>
            $(document).ready(function() {
                var selectedFiles = [];

                $('#additionalFile').on('change', function() {
                    var files = $(this)[0].files;

                    for (var i = 0; i < files.length; i++) {
                        const isFileExist = selectedFiles.some(function(file) {
                            return file.name === files[i].name;
                        });

                        if (!isFileExist) {
                            selectedFiles.push(files[i]);
                        }
                    }
                    updateFilePreview();
                });

                $('#supplierForm').on('submit', function(e) {
                    e.preventDefault();
                    const formData = new FormData(this);

                    formData.delete('additional_files[]');
                    for (let i = 0; i < selectedFiles.length; i++) {
                        formData.append('additional_files[]', selectedFiles[i]);
                    }

                    $.ajax({
                        url: $(this).attr('action'),
                        method: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            window.location.href = "{{ route('suppliers.index') }}";
                        },
                        error: function(xhr, status, error) {
                            $("#supplierForm").find('.invalid-feedback').remove();
                            $.each(xhr.responseJSON.errors, function(field, errors) {
                                $("#supplierForm").find('[name="' + field + '"]').addClass(
                                        'is-invalid')
                                    .after('<div class="invalid-feedback">' + errors.join(
                                            '<br>') +
                                        '</div>');
                            });
                        }
                    });
                });

                $('#filePreview').on('click', '.remove-file-button', function(e) {
                    e.preventDefault();
                    const fileName = $(this).closest('.card').find('.fw-semibold').text();

                    removeFile(fileName);
                });

                function removeFile(name) {
                    selectedFiles = selectedFiles.filter(function(file) {
                        return file.name !== name;
                    });

                    updateFilePreview();
                }

                function updateFilePreview() {
                    $('#filePreview').html(''); // Clear previous previews

                    for (let i = 0; i < selectedFiles.length; i++) {
                        const file = selectedFiles[i];
                        const reader = new FileReader();

                        reader.onload = function(e) {
                            const filePreviewTemplate = `
                                    <div class="card mb-3 col-md-2">
                                        <div class="card-body">
                                            <small class="fw-semibold">${file.name}</small>
                                            <small class="card-text">${formatBytes(file.size)}</small>
                                            <button type="button" class="btn btn-sm btn-danger mt-2 remove-file-button" data-name='${file.name}'>Remove</button>
                                        </div>
                                    </div>
                                `;

                            $('#filePreview').append(filePreviewTemplate);
                        };

                        reader.readAsDataURL(file);
                    }
                }

                // Helper function to format file size
                function formatBytes(bytes) {
                    if (bytes === 0) return '0 Bytes';

                    const k = 1024;
                    const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
                    const i = Math.floor(Math.log(bytes) / Math.log(k));

                    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
                }
            });
        </script>
    </div>
@endsection
