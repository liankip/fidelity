@extends('layouts.app')

@section('content')
    <form class="container mt-2" action="{{ route('projects.update', $project->id) }}" method="POST"
        enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="col-lg-12 margin-tb">
                <div class="pull-left">
                    <a href="{{ route('projects.index') }}" class="third-color-sne"> <i
                            class="fa-solid fa-chevron-left fa-xs"></i> Back</a>
                    <h2>Edit {{ $urlType !== null ? 'Retail' : 'Project' }}</h2>
                    @if ($projectStatus === 'Draft')
                        <button type="submit" class="btn btn-primary ml-3">Approve</button>
                        <a class="btn btn-danger" href="{{ route('projects.index') }}"
                            enctype="multipart/form-data">Back</a>
                    @endif

                    <input type="hidden" name="route_type" value="{{ $urlType }}">
                    @foreach (['danger', 'warning', 'success', 'info'] as $key)
                        @if (Session::has($key))
                            <div class="alert alert-{{ $key }} alert-dismissible fade show mb-1 mt-1"
                                role="alert">
                                {{ Session::get($key) }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                                </button>
                            </div>
                        @endif
                    @endforeach
                </div>

            </div>
        </div>

        <div class="card mt-5 primary-box-shadow">
            <div class="card-body my-4">

                <div class="row">
                    @if($customers !== null)
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Select Customer<span class="text-danger">*</span></strong>
                                <select name="customer_id" id="customer" class="form-control" required>
                                    <option value="">Select Customer </option>
                                    @foreach ($customers as $customer)
                                        <option value="{{ $customer->id }}" {{ $customer->id == $project->customer_id ?'selected' : '' }}>{{ $customer->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    @endif
                    <div class="col-xs-12 col-sm-12 col-md-12 mt-4">
                        <div class="form-group">
                            <strong>Project Name<span class="text-danger">*</span></strong>
                            <input type="text" name="name" value="{{ old('name') ? old('name') : $project->name }}"
                                class="form-control @error('name') is-invalid @enderror" placeholder="Project Name">
                            @error('name')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- <div class="col-xs-12 col-sm-12 col-md-12 mt-4">
                        <div class="form-group">
                            <strong>Project Type<span class="text-danger">*</span></strong>
                            <select name="project_type" id="project_type" class="form-control" required>
                                <option value="project" {{ $project->project_type === 'project' ? 'selected' : '' }}>Project
                                </option>
                                <option value="retail" {{ $project->project_type === 'retail' ? 'selected' : '' }}>Retail
                                </option>
                                <option value="manufaktur" {{ $project->project_type === 'manufaktur' ? 'selected' : '' }}>
                                    Manufaktur</option>
                            </select>
                            @error('project_type')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div> --}}




                    {{-- <div class="col-xs-12 col-sm-12 col-md-12 mt-4">
                        <div class="form-group">
                            <strong>
                                @if ($project->project_type === 'retail')
                                    Anggaran Belanja
                                @else
                                    Project Budget
                                @endif
                                <span class="text-danger">*</span>
                            </strong>
                            <input type="number" name="project_value"
                                value="{{ old('project_value') ? old('project_value') : number_format($project->value, 0, ',', '') }}"
                                class="form-control @error('project_value') is-invalid @enderror"
                                placeholder="Project Value">
                            @error('project_value')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div> --}}
                    <div class="col-xs-12 col-sm-12 col-md-12 mt-4">
                        <div class="form-group mb-2">
                            <strong>Project Group</strong>
                            <select name="group_id" id="groups"
                                class="js-example-basic-single form-control @error('groups') is-invalid @enderror">
                                <option value="" hidden>Pilih Group</option>
                                @foreach ($groups as $group)
                                    <option value="{{ $group->id }}"
                                        {{ $group->id == $project->project_group_id ? 'selected' : '' }}>
                                        {{ $group->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('user_id')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                            <div class="mt-2">
                                @livewire('project-group.add-group')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card mt-5 primary-box-shadow">
            <div class="card-body">
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Company Name<span class="text-danger">*</span></strong>
                        <input type="text" name="company_name"
                            value="{{ old('company_name') ? old('company_name') : $project->company_name }}"
                            class="form-control @error('company_name') is-invalid @enderror" placeholder="Company Name">
                        @error('company_name')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- <div class="col-xs-12 col-sm-12 col-md-12 mt-4">
                    <div class="form-group">
                        <strong>PIC<span class="text-danger">*</span></strong>
                        <input type="text" name="pic" value="{{ old('pic') ? old('pic') : $project->pic }}"
                            class="form-control @error('pic') is-invalid @enderror" placeholder="PIC">
                        @error('pic')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div> --}}
                <div class="card border">
                    <div class="card-body">
                        <strong>Struktur Organinsasi</strong>
                        <div class="col-xs-12 col-sm-12 col-md-12 mt-2">
                            <div class="form-group">
                                <strong>PM In Charge</strong>
                                <input type="text" name="pic" value="{{ old('pic') ? old('pic') : $project->pic }}"
                                    class="form-control @error('pic') is-invalid @enderror" placeholder="Name"
                                    id="pic">
                                @error('pic')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12 mt-2">
                            <div class="form-group">
                                <strong>SM In Charge</strong>
                                <input type="text" name="sm" value="{{ old('sm') ? old('sm') : $project->sm }}"
                                    class="form-control @error('sm') is-invalid @enderror" placeholder="Name">
                                @error('sm')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12 mt-2">
                            <div class="form-group">
                                <strong>Logistic In Charge</strong>
                                <input type="text" name="logistic"
                                    value="{{ old('logistic') ? old('logistic') : $project->logistic }}"
                                    class="form-control @error('logistic') is-invalid @enderror" placeholder="Name">
                                @error('logistic')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12 mt-2">
                            <div class="form-group">
                                <strong>EHS In Charge</strong>
                                <input type="text" name="ehs"
                                    value="{{ old('ehs') ? old('ehs') : $project->ehs }}"
                                    class="form-control @error('ehs') is-invalid @enderror" placeholder="Name">
                                @error('ehs')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12 mt-2">
                            <div class="form-group">
                                <strong>Director In Charge</strong>
                                <input type="text" name="director"
                                    value="{{ old('director') ? old('director') : $project->director }}"
                                    class="form-control @error('director') is-invalid @enderror" placeholder="Name">
                                @error('director')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    @php
                        $userArray = $userData
                            ->map(function ($user) {
                                return ['value' => $user->name, 'id' => $user->id, 'email' => $user->email];
                            })
                            ->toArray();
                    @endphp
                    <script>
                        const inputNames = ['sm', 'logistic', 'ehs', 'director'];

                        inputNames.forEach(name => {
                            const input = document.querySelector(`input[name=${name}]`);
                            new Tagify(input);
                        });

                        document.addEventListener("DOMContentLoaded", function() {
                            const picInput = document.getElementById('pic');

                            const sanitizedUserData = @json($userArray);

                            if (picInput) { // Ensure the input element exists
                                const tagify = new Tagify(picInput, {
                                    tagTextProp: 'data-id',
                                    dropdown: {
                                        enabled: 0,
                                        maxItems: 10,
                                        closeOnSelect: false,
                                        classname: 'users-list',
                                    },
                                    templates: {
                                        dropdownItem: function(tagData) {
                                            return `<div class="tagify__dropdown__item" data-id="${tagData.id}" data-value="${tagData.value}" data-email="${tagData.email}">
                                                        <strong>${tagData.value} - ${tagData.email}</strong>
                                                    </div>`;
                                        }
                                    },
                                    whitelist: sanitizedUserData.map(user => ({
                                        id: user.id,
                                        value: user.value,
                                        email: user.email
                                    })),
                                });

                                tagify.on('dropdown:select', function(e) {
                                    const selectedElement = e.detail.elm;
                                    const tagData = {
                                        id: selectedElement.getAttribute('data-id'),
                                        value: selectedElement.getAttribute('data-value'),
                                        email: selectedElement.getAttribute('data-email'),
                                    };

                                    tagify.addTags([tagData]);

                                });
                            } else {
                                console.error('Input element with id "pic" not found');
                            }
                        });
                    </script>
                </div>


                {{-- <div class="col-xs-12 col-sm-12 col-md-12 mt-4">
                    <div class="form-group">
                        <strong>Email</strong>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                            placeholder="Email" value="{{ old('email') ? old('email') : $project->email }}">
                        @error('email')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-12 mt-4">
                    <div class="form-group">
                        <strong>Phone<span class="text-danger">*</span></strong>
                        <input type="number" name="phone" class="form-control @error('phone') is-invalid @enderror"
                            placeholder="Phone" value="{{ old('phone') ? old('phone') : $project->phone }}">
                        @error('phone')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div> --}}

                <div class="col-xs-12 col-sm-12 col-md-12 mt-4">
                    <div class="form-group">
                        <strong>Address<span class="text-danger">*</span></strong>
                        <textarea type="text" name="address" class="form-control @error('address') is-invalid @enderror"
                            placeholder="Address">{{ old('address') ? old('address') : $project->address }}</textarea>
                        @error('address')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                @if ($projectStatus !== 'Draft')
                    <div class="col-xs-12 col-sm-12 col-md-12 mt-4">
                        <div class="form-group">
                            <strong>Province<span class="text-danger">*</span></strong>
                            <select name="province" id="provinceDropdown"
                                class="form-control @error('province') is-invalid @enderror" placeholder="Province">
                                <option value="" disabled selected>Select Province</option>
                                @foreach ($provinces as $province)
                                    <option value="{{ $province['name'] }}" data-id="{{ $province['id'] }}"
                                        {{ $project->province === $province['name'] ? 'selected' : '' }}>
                                        {{ $province['name'] }}
                                    </option>
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
                @endif

                @if ($projectStatus !== 'Draft')
                    <div class="form-group">
                        <strong>Status</strong>
                        <select name="status" class="form-select @error('status') is-invalid @enderror"
                            placeholder="Status">
                            <option value="Draft"
                                {{ old('status') == 'Draft' ? 'selected' : ($project->status == 'Draft' ? 'selected' : '') }}>
                                Draft</option>
                            <option value="On going"
                                {{ old('status') == 'On going' ? 'selected' : ($project->status == 'On going' ? 'selected' : '') }}>
                                On going</option>
                            <option value="Finished"
                                {{ old('status') == 'Finished' ? 'selected' : ($project->status == 'Finished' ? 'selected' : '') }}>
                                Finished</option>
                        </select>
                    </div>
                @endif
            </div>
        </div>
        @if ($projectStatus !== 'Draft')
            <div class="card-footer">
                <button type="submit" class="btn btn-primary ml-3">Save</button>
            </div>
        @endif
    </form>
    <script>
        $(document).ready(function() {
            fetchCity()
        })

        function fetchCity() {
            const selectedProvinceId = $('#provinceDropdown').find('option:selected').data('id');
            if (!selectedProvinceId) {
                return
            }
            $.ajax({
                url: 'https://www.emsifa.com/api-wilayah-indonesia/api/regencies/' + selectedProvinceId + '.json',
                method: 'GET',
                success: function(data) {
                    $('#cityDropdown').empty();
                    $('#cityDropdown').append('<option value="" disabled selected>Select City</option>');
                    data.forEach(function(city) {
                        $('#cityDropdown').append('<option value="' + city.name + '">' + city.name +
                            '</option>');
                    });

                    $('#cityDropdown').val('{{ $project->city }}');
                }
            });
        }
        $('#provinceDropdown, #cityDropdown, #customer').select2({
            theme: 'bootstrap-5'
        });

        $('#provinceDropdown').on('change', fetchCity)
    </script>
@endsection
