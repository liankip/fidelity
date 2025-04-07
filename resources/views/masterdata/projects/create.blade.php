@extends('layouts.app')

@section('content')
    <form action="{{ route('projects.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="container mt-2">
            <div class="row">
                <div class="col-lg-12 margin-tb">
                    <div class="pull-left">
                        <a href="{{ route('projects.index') }}" class="third-color-sne"> <i
                            class="fa-solid fa-chevron-left fa-xs"></i> Back</a>
                        <h2 class="primary-color-sne">Add New Project</h2>
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
            @if ($errors->any())
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            @endif

            <div class="card mt-5 primary-box-shadow">
                <div class="card-body my-4">
                    <div class="row">
                        @if (!empty($documentsData))
                            <div class="form-group">
                                <strong>Project Documents</strong>
                                <select name="group_id" id="document"
                                    class="js-example-basic-single form-control @error('groups') is-invalid @enderror">
                                    <option value="" hidden>Pilih Document</option>
                                    @foreach ($documentsData as $documents)
                                        <option value="{{ json_encode($documents['data']) }}">{{ $documents['name'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @endif
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Project Name<span class="text-danger">*</span></strong>
                                <input type="text" name="name" value="{{ old('name') ? old('name') : '' }}"
                                    class="form-control @error('name') is-invalid @enderror" placeholder="Project Name">
                                @error('name')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <input type="hidden" name="project_type" value="project">
                        {{-- <div class="col-xs-12 col-sm-12 col-md-12 mt-4">
                            <div class="form-group">
                                <strong>PO Number <small>(optional)</small></strong>
                                <input type="text" name="po_number"
                                    value="{{ old('po_number') ? old('po_number') : '' }}"
                                    class="form-control @error('po_number') is-invalid @enderror" placeholder="PO Number">
                                @error('po_number')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div> --}}
                        {{-- <div class="col-xs-12 col-sm-12 col-md-12 mt-4">
                            <div class="form-group">
                                <strong>Project Budget<span class="text-danger">*</span></strong>
                                <input type="number" name="project_value"
                                    value="{{ old('project_value') ? old('project_value') : '' }}"
                                    class="form-control @error('project_value') is-invalid @enderror"
                                    placeholder="Project Budget">
                                @error('project_value')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div> --}}

                        {{-- <div class="col-xs-12 col-sm-12 col-md-12 mt-4">
                            <div class="form-group">
                                <strong>Proposed Date<span class="text-danger">*</span></strong>
                                <input type="date" name="start_date"
                                    value="{{ old('start_date') ? old('start_date') : '' }}"
                                    class="form-control @error('start_date') is-invalid @enderror" placeholder="Start Date">
                                @error('start_date')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div> --}}

                        {{--                        <div class="col-xs-12 col-sm-12 col-md-12 mt-4"> --}}
                        {{--                            <div class="form-group"> --}}
                        {{--                                <strong>End Date<span class="text-danger">*</span></strong> --}}
                        {{--                                <input type="date" name="end_date" value="{{ old('end_date') ? old('end_date') : '' }}" --}}
                        {{--                                    class="form-control @error('end_date') is-invalid @enderror" placeholder="End Date"> --}}
                        {{--                                @error('end_date') --}}
                        {{--                                    <div class="text-danger">{{ $message }}</div> --}}
                        {{--                                @enderror --}}
                        {{--                            </div> --}}
                        {{--                        </div> --}}
                        {{-- <div class="col-xs-12 col-sm-12 col-md-12 mt-4">
                            <div class="form-group">
                                <strong>Assign to (berkaitan dengan akses boq)<span class="text-danger">*</span></strong>
                                <select name="user_id" id="user_id"
                                    class="js-example-basic-single form-control @error('user_id') is-invalid @enderror">
                                    <option value="" hidden>Pilih User</option>
                                </select>
                                @error('user_id')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div> --}}

                        <div class="col-xs-12 col-sm-12 col-md-12 mt-4">
                            <div class="form-group">
                                <strong>Document Upload</strong>
                                <div id="document-upload-container">
                                    <div class="d-flex gap-2 mb-2 document-upload-row">
                                        <input type="file" class="form-control" name="documents[]" />
                                        <button type="button" class="btn btn-danger delete-document">Delete</button>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-success mt-1" id="add-document">Add more
                                    document</button>
                                @error('documents')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12 mt-4">
                            <div class="form-group">
                                <strong>Project Code<span class="text-danger">*</span></strong>
                                <input type="text" name="project_code"
                                    value="{{ old('project_code') ? old('project_code') : '' }}"
                                    class="form-control @error('project_code') is-invalid @enderror"
                                    placeholder="Project Code">
                                @error('project_code')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-12 mt-4">
                            <div class="form-group">
                                <strong>Project Group</strong>
                                <select name="group_id" id="groups"
                                    class="js-example-basic-single form-control @error('groups') is-invalid @enderror">
                                    <option value="" hidden>Pilih Group</option>
                                    @foreach ($groups as $group)
                                        <option value="{{ $group->id }}">{{ $group->name }}</option>
                                    @endforeach
                                </select>
                                <div class="mt-2">
                                    @livewire('project-group.add-group')
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mt-4 primary-box-shadow">
                <div class="card-body">
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>Company / Client Name<span class="text-danger">*</span></strong>
                            <input type="text" name="company_name"
                                value="{{ old('company_name') ? old('company_name') : '' }}"
                                class="form-control @error('company_name') is-invalid @enderror" placeholder="Company Name">
                            @error('company_name')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    @livewire('project.create.p-i-c', ['userData' => $userData])
                    <div class="col-xs-12 col-sm-12 col-md-12 mt-4">
                        <div class="form-group">
                            <strong>Project Address<span class="text-danger">*</span></strong>
                            <textarea type="text" name="address" class="form-control @error('address') is-invalid @enderror"
                                placeholder="Address">{{ old('address') ? old('address') : '' }}</textarea>
                            @error('address')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-12 mt-4">
                        <div class="form-group">
                            <strong>Province<span class="text-danger">*</span></strong>
                            <select name="province" id="provinceDropdown"
                                class="form-control @error('province') is-invalid @enderror" placeholder="Province">
                                <option value="" disabled selected>Select Province</option>
                                @foreach ($provinces as $province)
                                    <option value="{{ $province['name'] }}" data-id="{{ $province['id'] }}">
                                        {{ $province['name'] }}</option>
                                @endforeach
                            </select>
                            @error('province')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- <div class="col-xs-12 col-sm-12 col-md-12 mt-4">
                        <div class="form-group">
                            <strong>City<span class="text-danger">*</span></strong>
                            <select name="city" id="cityDropdown" value="{{ old('city') ? old('city') : '' }}"
                                class="form-control @error('city') is-invalid @enderror" placeholder="City">
                                <option value="" disabled selected>Select City</option>
                            </select>
                            @error('city')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div> --}}

                    {{--    <div class="col-xs-12 col-sm-12 col-md-12 mt-4">
                        <div class="form-group">
                            <strong>Project Kode Pos<span class="text-danger">*</span></strong>
                            <input type="number" name="post_code"
                                value="{{ old('post_code') ? old('post_code') : '' }}"
                                class="form-control @error('post_code') is-invalid @enderror"
                                placeholder="Kode Pos">
                            @error('post_code')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-12 mt-4">
                        <div class="form-group">
                            <strong>Project Status<span class="text-danger">*</span></strong>
                            <select name="status" class="form-select @error('status') is-invalid @enderror"
                                placeholder="Status">
                                <option value="On going" {{ old('status') == 'On going' ? 'selected' : '' }}>
                                    On going</option>
                                <option value="Finished" {{ old('status') == 'Finished' ? 'selected' : '' }}>
                                    Finished</option>
                            </select>
                            @error('status')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div> --}}
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary ml-3">Save</button>
                    <a class="btn btn-danger" href="{{ route('projects.index') }}"
                        enctype="multipart/form-data">Back</a>
                </div>
            </div>
        </div>
    </form>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const documentUploadContainer = document.getElementById('document-upload-container');
            const addDocumentButton = document.getElementById('add-document');

            addDocumentButton.addEventListener('click', function() {
                const newDocumentRow = document.createElement('div');
                newDocumentRow.classList.add('d-flex', 'gap-2', 'mb-2', 'document-upload-row');
                newDocumentRow.innerHTML = `
                    <input type="file" class="form-control" name="documents[]" />
                    <button type="button" class="btn btn-danger delete-document">Delete</button>
                `;
                documentUploadContainer.appendChild(newDocumentRow);

                newDocumentRow.querySelector('.delete-document').addEventListener('click', function() {
                    documentUploadContainer.removeChild(newDocumentRow);
                });
            });

            document.querySelectorAll('.delete-document').forEach(button => {
                button.addEventListener('click', function() {
                    const documentRow = this.parentElement;
                    documentUploadContainer.removeChild(documentRow);
                });
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $("#document").change((e) => {
                const data = JSON.parse(e.target.value);

                $("input[name='name']").val(data.project_name);
                $("input[name='po_number']").val(data.po_number);
                $("input[name='company_name']").val(data.company_name);
                $("input[name='project_value']").val(data.budget);
                $("textarea[name='address']").val(data.address);
            })
            $('#user_id').select2({
                theme: 'bootstrap-5',
                "language": {
                    "noResults": function() {
                        return "No Results Found";
                    }
                },
                escapeMarkup: function(markup) {
                    return markup;
                },
                ajax: {
                    url: '/api/getusers',
                    dataType: 'json',
                    delay: 250,
                    processResults: function(data) {
                        return {
                            results: $.map(data, function(user) {
                                return {
                                    id: user.id,
                                    text: user.name + ' (' + user.email + ')'
                                };
                            })
                        };
                    },
                    cache: true
                }
            });

            $('#provinceDropdown, #cityDropdown').select2({
                theme: 'bootstrap-5'
            });

            // $('#provinceDropdown').on('change', function() {
            //     const selectedProvinceId = $(this).find('option:selected').data('id');
            //     $.ajax({
            //         url: 'https://www.emsifa.com/api-wilayah-indonesia/api/regencies/' +
            //             selectedProvinceId + '.json',
            //         method: 'GET',
            //         success: function(data) {
            //             $('#cityDropdown').empty();
            //             $('#cityDropdown').append(
            //                 '<option value="" disabled selected>Select City</option>');
            //             data.forEach(function(city) {
            //                 $('#cityDropdown').append('<option value="' + city.name +
            //                     '">' + city.name + '</option>');
            //             });
            //             $('#cityDropdown').trigger('change');
            //         },
            //         error: function() {
            //             console.error('Error fetching cities.');
            //         }
            //     });
            // });

        });
    </script>
@endsection
