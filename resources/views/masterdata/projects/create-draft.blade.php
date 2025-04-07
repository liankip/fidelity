<div class="">
    @extends('layouts.app')

    @section('content')
        <form action="{{ route('projects.create-draft.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="container mt-2">
                <div class="row">
                    <div class="col-lg-12 margin-tb">
                        <div class="pull-left">
                            <h2>Add New Draft Project</h2>
                            <hr>
                        </div>

                    </div>
                </div>

                <x-common.notification-alert />

                <div class="card mt-5">

                    <div class="card-body my-4">
                        <div class="row">
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
                            <div class="col-xs-12 col-sm-12 col-md-12 mt-4">
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
                            </div>

                            <div class="col-xs-12 col-sm-12 col-md-12 mt-4">
                                <div class="form-group">
                                    <strong>Proposed Date<span class="text-danger">*</span></strong>
                                    <input type="date" name="start_date"
                                        value="{{ old('start_date') ? old('start_date') : '' }}"
                                        class="form-control @error('start_date') is-invalid @enderror"
                                        placeholder="Start Date">
                                    @error('start_date')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="card mt-4">
                    <div class="card-body">
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Company / Client Name<span class="text-danger">*</span></strong>
                                <input type="text" name="company_name"
                                    value="{{ old('company_name') ? old('company_name') : '' }}"
                                    class="form-control @error('company_name') is-invalid @enderror"
                                    placeholder="Company Name">
                                @error('company_name')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        @livewire('project.create.p-i-c')
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
                                    @foreach($provinces as $province)
                                        <option value="{{ $province['name'] }}" data-id="{{ $province['id'] }}">{{ $province['name'] }}</option>
                                    @endforeach
                                </select>
                                @error('province')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
    
                        <div class="col-xs-12 col-sm-12 col-md-12 mt-4">
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
                        </div>

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
    
                $('#provinceDropdown').on('change', function() {
                    const selectedProvinceId = $(this).find('option:selected').data('id');
                    $.ajax({
                        url: 'https://www.emsifa.com/api-wilayah-indonesia/api/regencies/' + selectedProvinceId + '.json',
                        method: 'GET',
                        success: function(data) {
                            $('#cityDropdown').empty();
                            $('#cityDropdown').append('<option value="" disabled selected>Select City</option>');
                            data.forEach(function(city) {
                                $('#cityDropdown').append('<option value="' + city.name + '">' + city.name + '</option>');
                            });
                            $('#cityDropdown').trigger('change');
                        },
                        error: function() {
                            console.error('Error fetching cities.');
                        }
                    });
                });
            });
        </script>
    @endsection
</div>
