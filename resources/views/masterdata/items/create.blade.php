@extends('layouts.app')

@section('content')
    <div class="container mt-2">
        <div class="row">
            <div class="col-lg-12 margin-tb">
                <div class="pull-left">
                    <a href="{{ route('items.index') }}" class="third-color-sne"> <i
                            class="fa-solid fa-chevron-left fa-xs"></i> Back</a>
                    <h2 class="primary-color-sne mt-3">Add New Item</h2>
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

        <form action="{{ route('items.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="card primary-box-shadow mt-5">
                <div class="card-body">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group relative">
                                <strong>Item Name<span class="text-danger">*</span></strong>
                                <input type="text" name="name" id="nameitem" autocomplete="off"
                                    class="form-control @error('name') is-invalid @enderror" placeholder="Item Name"
                                    value="{{ old('name') }}">
                                <div class="" id="namerelative"></div>
                                @error('name')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <script>
                                $(document).ready(function() {
                                    function getdata() {
                                        fetch('/api/getitems?search=' + $('#nameitem').val())
                                            .then((response) => response.json())
                                            .then((json) => {
                                                $('.listname').remove()
                                                $('.existitem').remove()
                                                if (json.length) {
                                                    $("#namerelative").append(
                                                        "<div class='existitem text-dark'>item name similar with:</div> <ul class='listname'></ul>"
                                                    )
                                                    $("#namerelative").addClass("bg-white")

                                                } else {
                                                    $('.existitem').remove()
                                                }

                                                json.forEach(element => {
                                                    $(".listname").append(
                                                        "<li class='toremove text-danger'>" + element
                                                        .name + "</li>")
                                                });
                                            });
                                    }

                                    $('#nameitem').focus(function() {
                                        $('#nameitem').on('input', function() {
                                            getdata()
                                        });

                                        if ($('#nameitem').val()) {
                                            getdata()
                                        }
                                    })
                                    $('#nameitem').blur(function() {
                                        $('.listname').remove()
                                        $('.existitem').remove()
                                    })
                                });
                            </script>
                        </div>
                        <div class="form-group ">
                            <strong>Brand</strong>
                            <input type="text" name="brand" id="brand" autocomplete="off"
                                class="form-control @error('brand') is-invalid @enderror" placeholder="Brand Name"
                                value="{{ old('brand') }}">
                            @error('brand')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="row mt-3">
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <strong>Image<span class="text-danger">*</span></strong>
                                    <input type="file" name="image"
                                        class="form-control @error('image') is-invalid @enderror" placeholder="item image"
                                        required>
                                    @error('image')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="container mt-3">
                            <div id="file-upload-container">
                                <div class="row mt-3 file-upload-row">
                                    <div class="col-xs-12 col-sm-12 col-md-12">
                                        <div class="form-group">
                                            <label for="file_upload">Product Catalog (PDF and Images)</label>
                                            <input type="file" name="file_upload[]" class="form-control"
                                                accept="application/pdf, image/*">
                                            @error('file_upload')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button type="button" id="add-file-upload" class="btn btn-primary mt-2">Add More Files</button>
                        </div>


                        <div class="row mt-3">
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <strong>Kategori Barang</strong>
                                    <select id="category" class="form-select" name="category_id">
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('image')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="mt-3">
                                <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal"
                                    data-bs-target="#add_category">
                                    Create New Category
                                </button>
                            </div>
                        </div>

                        @php
                            $projectsArray = $projects
                                ->map(function ($project) {
                                    return ['value' => $project->name, 'id' => $project->id];
                                })
                                ->toArray();
                        @endphp
                        <div class="row mt-3">
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <strong>RFA</strong>
                                    <input id="tagify-input" name="rfa" placeholder="Select projects..."
                                        class="form-control" />
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <strong>Jenis Barang</strong>
                                    <select class="form-select" name="type">
                                        @php
                                            $types = \App\Helpers\ItemType::get();
                                        @endphp

                                        @foreach ($types as $key => $type)
                                            <option value="{{ $key }}">{{ $type }}</option>
                                        @endforeach
                                    </select>
                                    @error('image')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-12 mt-4">
                            <div class="form-group">
                                <strong>Unit<span class="text-danger">*</span></strong>
                                <select name="unit" id="unit"
                                    class="js-example-basic-single form-select @error('unit') is-invalid @enderror">
                                    <option value="" hidden>Pilih Unit</option>
                                    @foreach ($units as $unit)
                                        <option value="{{ $unit->id }}"
                                            {{ old('unit') == $unit->id ? 'selected' : '' }}>
                                            {{ $unit->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('unit')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-3">
                            <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal"
                                data-bs-target="#add_unit">
                                Create New Unit
                            </button>
                        </div>

                        <div class="form-group mt-5">
                            <strong>Notes K3 <span class="text-danger">*</span></strong>
                            <textarea type="text" name="notes_k3" autocomplete="off" class="form-control" placeholder="Notes K3"
                                maxlength="200" required></textarea>
                        </div>
                        <div class="form-group">
                            <strong>Waktu tunggu (hari)<span class="text-danger">*</span></strong>
                            <input type="text" name="lead_time" id="lead_time" autocomplete="off"
                                class="form-control @error('lead_time') is-invalid @enderror" placeholder="Lead Time"
                                value="{{ old('lead_time') }}">
                            @error('lead_time')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary ml-3"><i class="fa-solid fa-floppy-disk pe-2"></i> Save</button>
                </div>
            </div>

        </form>

        {{-- Create new Unit Modal --}}
        <div class="modal fade" id="add_unit" tabindex="-1" aria-labelledby="add_unit_label" aria-hidden="true">
            <div class="modal-dialog">
                <form id="add_unit_form" action="{{ route('unit') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="add_unit_label">Create New Unit</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body my-3">
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <strong>Unit Name<span class="text-danger">*</span></strong>
                                    <input type="text" name="unit_name" class="form-control" placeholder="Unit Name">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Save</button>
                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- Create new Category Modal --}}
        <div class="modal fade" id="add_category" tabindex="-1" aria-labelledby="add_category_label"
            aria-hidden="true">
            <div class="modal-dialog">
                <form action="{{ route('category-item.store') }}" id="add_category_form" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="add_category_label">Create New Category</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body my-3">
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <strong>Category Name<span class="text-danger">*</span></strong>
                                    <input type="text" name="name" class="form-control"
                                        placeholder="Category Name" required>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Save</button>
                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $('#unit').select2({
                theme: 'bootstrap-5'
            });

            document.getElementById('add-file-upload').addEventListener('click', function() {
                var container = document.getElementById('file-upload-container');

                var newRow = document.createElement('div');
                newRow.classList.add('row', 'mt-3', 'file-upload-row');
                newRow.innerHTML = `
                        <div class="form-group">
                            <input type="file" name="file_upload[]" class="form-control" accept="application/pdf, image/*">
                            @error('file_upload')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                            <button type="button" class="btn btn-danger remove-file-upload mt-2">Remove</button>
                        </div>
                `;

                container.appendChild(newRow);
            });

            document.getElementById('file-upload-container').addEventListener('click', function(event) {
                if (event.target.classList.contains('remove-file-upload')) {
                    var row = event.target.closest('.file-upload-row');
                    if (document.querySelectorAll('.file-upload-row').length > 1) {
                        row.remove();
                    } else {
                        alert('At least one file upload field is required.');
                    }
                }
            });
        });

        const initializeModalForm = (modalId, formId, selectId, route, method) => {
            $(formId).on('submit', function(e) {

                // Prevent the form from submitting
                e.preventDefault();

                // Submit the form using AJAX
                $.ajax({
                    url: route,
                    method: method,
                    data: $(this).serialize(),
                    success: function(response) {

                        var optionValue = response.option_value;
                        var optionText = response.option_text;
                        var $parentSelect = $(selectId);
                        $parentSelect.append($('<option>', {
                            value: optionValue,
                            text: optionText,
                            selected: true
                        }));

                        // Clear the form fields
                        $(formId)[0].reset();

                        // Close the modal
                        $(modalId).modal('hide');
                    },
                    error: function(xhr, status, error) {

                        // Display the validation errors
                        $(formId).find('.invalid-feedback').remove();
                        $.each(xhr.responseJSON.errors, function(field, errors) {
                            $(formId).find('[name="' + field + '"]').addClass(
                                    'is-invalid')
                                .after('<div class="invalid-feedback">' + errors.join(
                                        '<br>') +
                                    '</div>');
                        });

                        // Keep the modal open
                        $(modalId).modal('show');
                    }
                });
            });

            $(modalId).on('hidden.bs.modal', function() {

                // Clear the form fields
                $(formId)[0].reset();

                // Remove the error message elements from the form fields
                $(`${formId} .is-invalid`).removeClass('is-invalid');
                $(`${formId} .invalid-feedback`).remove();
            });
        }

        initializeModalForm('#add_unit', '#add_unit_form', '#unit', "{{ route('unit') }}", "PATCH");
        initializeModalForm('#add_category', '#add_category_form', '#category', "{{ route('category-item.store') }}",
            "POST");

        document.addEventListener("DOMContentLoaded", function() {
            const projects = @json($projectsArray);

            const input = document.querySelector("#tagify-input");
            const tagify = new Tagify(input, {
                whitelist: projects,
                dropdown: {
                    maxItems: 200,
                    classname: "projects-list",
                    enabled: 0,
                    closeOnSelect: false
                }
            });
        });
    </script>
@endsection
