@extends('layouts.app')

@section('content')
    <div class="container mt-2">
        <div class="row">
            <div class="col-lg-12 margin-tb">
                <div class="pull-left">
                    <a href="{{ route('items.index') }}" class="third-color-sne"> <i
                        class="fa-solid fa-chevron-left fa-xs"></i> Back</a>
                    <h2 class="primary-color-sne mt-3">Edit Item</h2>
                    @foreach (['danger', 'warning', 'success', 'info'] as $key)
                        @if(Session::has($key))
                            <div class="alert alert-{{ $key }} alert-dismissible fade show mb-1 mt-1" role="alert">
                                {{ Session::get($key) }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                                </button>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>

        <div class="card primary-box-shadow mt-5">
            <form action="{{ route('items.update', $item->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="card-body mb-4">

                    <div class="row">
                        <div class="col-4">
                            <img class="rounded shadow-sm col-12" src={{ $item->image == 'images/no_image.png' ? url($item->image) : url('storage/' . $item->image) }} alt="" width="100 px">
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Item Name<span class="text-danger">*</span></strong>
                                <input type="text" name="name" value="{{ $item->name }}" class="form-control @error('name') is-invalid @enderror"
                                    placeholder="item name" value="{{ old('name') }}">
                                @error('name')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Brand</strong>
                                <input type="text" name="brand" value="{{ $item->brand }}"
                                       class="form-control @error('brand') is-invalid @enderror"
                                       placeholder="item brand" value="{{ old('brand') }}">
                                @error('brand')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    @if(request()->get('from') === 'approval')
                        <input type="hidden" name="approval" value="1">
                    @endif

                    <div class="row mt-3">
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Kategori Barang</strong>
                                <select class="form-select" name="category_id">
                                    @if($item->category_id == null)
                                        <option value="" selected>Pilih Kategori Barang</option>
                                    @endif
                                    @foreach($categories as $category)
                                        @if($category->id == $item->category_id)
                                            <option value="{{ $category->id }}" selected>{{ $category->name }}</option>
                                        @else
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endif
                                    @endforeach
                                </select>
                                @error('image')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    @php
                        $projectsArray = $projects->map(function($project) {
                            return ['value' => $project->name, 'id' => $project->id];
                        });
                        $existingProjects = $existingProjects ?? [];
                    @endphp

                    <div class="row mt-3">
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>RFA</strong>
                                <input id="tagify-input" name="rfa" placeholder="Select projects..." class="form-control" />
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

                                    @if($item->type == null || $item->type == "NA")
                                        <option value="" selected>Pilih Jenis Barang</option>
                                    @endif

                                    @foreach($types as $key => $type)
                                        @if($type == $item->type)
                                            <option value="{{ $key }}" selected>{{ $type }}</option>
                                        @else
                                            <option value="{{ $key }}">{{ $type }}</option>
                                        @endif
                                    @endforeach
                                </select>
                                @error('image')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Image</strong>
                                <input type="file" name="image" value="" class="form-control @error('image') is-invalid @enderror"
                                    placeholder="item image" value="{{ old('image') }}">
                                @error('image')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- <div class="container mt-3">
                        <div id="file-upload-container">
                            <div class="row mt-3 file-upload-row">
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="form-group">
                                        <label for="file_upload">Product Info (PDF and Images)</label>
                                        <input type="file" name="file_upload[]" class="form-control" accept="application/pdf, image/*">
                                        @error('file_upload')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button type="button" id="add-file-upload" class="btn btn-primary mt-2">Add More Files</button>
                    </div> --}}


                    <hr class="my-3">

                    <table class="table primary-box-shadow mt-3">
                        <thead class="thead-light">
                            <tr class="table-secondary">
                                <th class="text-center border-top-left" width="5%">No</th>
                                <th class="text-center" width="25%">Category</th>
                                <th class="text-center" width="35%">Unit</th>
                                <th class="text-center" width="25%">Conversion Rate</th>
                                <th class="text-center border-top-right" width="10%">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($item->item_unit as $item_unit)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td>{{ $item_unit->conversion_rate == 1 ? "Main Unit" : "Unit" }}</td>
                                    <td>{{ $item_unit->unit->name }}</td>
                                    <td>
                                        {{ $item_unit->conversion_rate }}
                                        <div class="text-secondary">
                                            @if(!$loop->first)<em>{{ "1" ." ". $item_unit->unit->name ." = ". $item_unit->conversion_rate ." ". $item->item_unit[0]->unit->name }}</em>@endif
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        @if($loop->first)
                                            <button type="button" class="btn btn-sm btn-outline-danger" disabled>Delete</button>
                                        @else
                                            <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $item_unit->id }}">Delete</button>
                                        @endif
                                    </td>
                                </tr>

                                <div class="modal fade" id="deleteModal{{ $item_unit->id }}" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel{{ $item_unit->id }}" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="deleteModalLabel{{ $item_unit->id }}">Confirm Delete</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                Are you sure you want to delete {{ $item_unit->name }}?
                                            </div>
                                            <div class="modal-footer">
                                                <form action="{{ route('delete-item-unit') }}" method="POST">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-danger">Delete</button>
                                                    <input type="hidden" name="id" value="{{ $item_unit->id }}">
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="mt-2">
                        <button type="button" class="btn btn-sm btn-outline-success" data-bs-toggle="modal"
                                data-bs-target="#insert_unit">
                            Add Unit
                        </button>
                    </div>

                    <hr class="my-3">

                    <div class="mt-3">
                        <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal"
                                data-bs-target="#add_unit">
                            Create New Unit
                        </button>
                    </div>

                    <div class="form-group mt-5">
                        <strong>Notes K3 <span class="text-danger">*</span></strong>
                        <textarea type="text" name="notes_k3" autocomplete="off"
                            class="form-control" placeholder="Notes K3" maxlength="200" required>{{ $item->notes_k3 }}</textarea>
                    </div>

                    <div class="form-group">
                        <strong>Waktu tunggu (hari)<span class="text-danger">*</span></strong>
                        <input type="text" name="lead_time" id="lead_time" autocomplete="off"
                            class="form-control @error('lead_time') is-invalid @enderror" placeholder="Lead Time"
                            value="{{ $item->lead_time }}">
                        @error('lead_time')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary ml-3"><i class="fa-solid fa-floppy-disk pe-2"></i>Save</button>
                </div>
            </form>

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

            <div class="modal fade" id="insert_unit" tabindex="-1" aria-labelledby="insert_unit_label" aria-hidden="true">
                <div class="modal-dialog">
                    <form id="insert_unit_form" action="{{ route('item-unit') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="insert_unit_label">Add Unit</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body my-3">
                                <div class="col-xs-12 col-sm-12 col-md-12 mt-4">
                                    <div class="form-group">
                                        <strong>Unit<span class="text-danger">*</span></strong>
                                        <select name="insert_unit_id"
                                            class="js-example-basic-single form-select
                                            @error('unit')
                                                is-invalid
                                            @enderror">
                                            <option value="" hidden>Pilih Unit</option>
                                            @foreach ($units as $unit)
                                                <option value="{{ $unit->id }}" {{ old('insert_unit_id') == $unit->id ? 'selected' : '' }}>
                                                    {{ $unit->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('insert_unit_id')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <input type="hidden" name="item_id" value="{{ $item->id }}">

                                    <div class="col-xs-12 col-sm-12 col-md-12 mt-4">
                                        <div class="form-group">
                                            <strong>Conversion Rate<span class="text-danger">*</span></strong>
                                            <input type="text" name="conversion_rate"
                                                class="form-control"
                                                placeholder="Conversion Rate">
                                        </div>
                                        @error('conversion_rate')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
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
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
        const projects = @json($projectsArray);
        const existingProjects = @json($existingProjects);

        const input = document.querySelector("#tagify-input");
        const tagify = new Tagify(input, {
            whitelist: projects,
            dropdown: {
                maxItems: 200,           
                classname: "projects-list",
                enabled: 0,             
                closeOnSelect: false   
            },
        });

        tagify.addTags(existingProjects);
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

        $('#add_unit_form').on('submit', function(e) {

            // Prevent the form from submitting
            e.preventDefault();

            // Submit the form using AJAX
            $.ajax({
                url: "{{ route('unit') }}",
                method: 'PATCH',
                data: $(this).serialize(),
                success: function(response) {

                    var optionValue = response.option_value;
                    var optionText = response.option_text;
                    var $parentSelect = $('#unit');
                    $parentSelect.append($('<option>', {
                        value: optionValue,
                        text: optionText,
                        selected: true
                    }));

                    // Clear the form fields
                    $('#add_unit_form')[0].reset();

                    // Close the modal
                    $(add_unit).modal('hide');
                },
                error: function(xhr, status, error) {

                    // Display the validation errors
                    $('#add_unit_form').find('.invalid-feedback').remove();
                    $.each(xhr.responseJSON.errors, function(field, errors) {
                        $('#add_unit_form').find('[name="' + field + '"]').addClass(
                                'is-invalid')
                            .after('<div class="invalid-feedback">' + errors.join('<br>') +
                                '</div>');
                    });

                    // Keep the modal open
                    $(add_unit).modal('show');
                }
            });
        });

        $('#add_unit').on('hidden.bs.modal', function() {

            // Clear the form fields
            $('#add_unit_form')[0].reset();

            // Remove the error message elements from the form fields
            $('#add_unit_form .is-invalid').removeClass('is-invalid');
            $('#add_unit_form .invalid-feedback').remove();
        });

        $('#insert_unit_form').on('submit', function(e) {

            // Prevent the form from submitting
            e.preventDefault();

            // Submit the form using AJAX
            $.ajax({
                url: "{{ route('item-unit') }}",
                method: 'PATCH',
                data: $(this).serialize(),
                success: function(response) {
                    $('#insert_unit_form')[0].reset();

                    // Close the modal
                    $(insert_unit).modal('hide');

                    // Reload the page
                    window.location.href = "{{ route('items.edit', $item->id) }}";
                },
                error: function(xhr, status, error) {

                    // Display the validation errors
                    $('#insert_unit_form').find('.invalid-feedback').remove();
                    $('#insert_unit_form .is-invalid').removeClass('is-invalid');
                    $.each(xhr.responseJSON.errors, function(field, errors) {
                        $('#insert_unit_form').find('[name="' + field + '"]').addClass(
                                'is-invalid')
                            .after('<div class="invalid-feedback">' + errors.join('<br>') +
                                '</div>');
                    });

                    // Keep the modal open
                    $(insert_unit).modal('show');
                }
            });
        });

        $('#insert_unit').on('hidden.bs.modal', function() {

            // Clear the form fields
            $('#insert_unit_form')[0].reset();

            // Remove the error message elements from the form fields
            $('#insert_unit_form .is-invalid').removeClass('is-invalid');
            $('#insert_unit_form .invalid-feedback').remove();
        });
    </script>
@endsection
