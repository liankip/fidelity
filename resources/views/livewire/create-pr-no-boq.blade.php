<div>
    <div class="container mt-2">

        <div class="row">
            <div class="col-lg-12 margin-tb">
                <div class="pull-left">
                    <h2>Create PR</h2>
                    <hr>

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

        <div class="card mt-5">
            <div class="card-body">
                <div class="row my-4">
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="row">
                            <div class="col-sm-4">
                                <strong>
                                    <label for="pr_type" class="col-form-label">PR Type:<span
                                            class="text-danger">*</span></label>
                                </strong>
                                <div>
                                    <em class="text-secondary">Wajib diisi</em>
                                </div>
                            </div>

                            <div class="col-sm-8">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="pr_type" id="pr_type_1"
                                        wire:model.defer="prtypemodel" value="Barang">
                                    <label class="form-check-label" for="pr_type_1">
                                        Barang
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="pr_type" id="pr_type_2"
                                        wire:model.defer="prtypemodel" value="Jasa">
                                    <label class="form-check-label" for="pr_type_2">
                                        Jasa
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="pr_type" id="pr_type_3"
                                        wire:model.defer="prtypemodel" value="Sewa Mesin">
                                    <label class="form-check-label" for="pr_type_3">
                                        Sewa Mesin
                                    </label>
                                </div>

                                @error('prtypemodel')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-12 mt-4">
                        <div class="row">
                            <div class="col-sm-4">
                                <strong>
                                    <label for="project_id" class="col-form-label">Project:<span
                                            class="text-danger">*</span></label>
                                </strong>
                                <div>
                                    <em class="text-secondary">Wajib diisi</em>
                                </div>
                            </div>
                            <div class="col-sm-8">
                                <select wire:model.defer='projectmodel' name="project_id" id="project_id"
                                    class="js-example-basic-single form-select @error('projectmodel') is-invalid @enderror @if (!empty($projectmodel) && !$errors->has('projectmodel')) is-valid @endif font-monospace">
                                    <option value="" hidden>Pilih Project</option>

                                    @php
                                        $max_length = DB::table('projects')->max(DB::raw('LENGTH(name)'));
                                    @endphp

                                    @foreach ($projects as $project)
                                        <option value="{{ $project->id }}">

                                            @php
                                                $a = 0;
                                                $length = $max_length - strlen($project->name);
                                            @endphp

                                            {{ $project->name }}@for ($a == 0; $a < $length; $a++)
                                                &nbsp;
                                            @endfor : {{ $project->company_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('projectmodel')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                                <div class="mt-2">
                                    <button class="btn btn-success btn-sm" wire:click='showaddproject'>Tambah
                                        Project</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-12 mt-4">
                        <div class="row">
                            <div class="col-sm-4">
                                <strong>
                                    <label for="requester" class="col-form-label">Requester:<span
                                            class="text-danger">*</span></label>
                                </strong>
                                <div>
                                    <em class="text-secondary">Wajib diisi</em>
                                </div>
                            </div>

                            <div class="col-sm-8">
                                <div class="col-sm">
                                    <input type="text" name="requester" id="requester"
                                        wire:model.defer='requestermodel'
                                        class="form-control @error('requestermodel') is-invalid @enderror @if (!empty($requestermodel) && !$errors->has('requestermodel')) is-valid @endif"
                                        value="" placeholder="Yang Meminta" required>
                                    @error('requestermodel')
                                        <div class="text-danger mt-1 mb-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-sm mt-2">
                                    <input type="text" name="requesterPhone" id="requesterPhone"
                                        wire:model.defer='requesterPhone'
                                        class="form-control @error('requesterPhone') is-invalid @enderror @if (!empty($requesterPhone) && !$errors->has('requesterPhone')) is-valid @endif"
                                        value="" placeholder="Nomor Telp" required>
                                    @error('requesterPhone')
                                        <div class="text-danger mt-1 mb-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-12 mt-4">
                        <div class="row">
                            <div class="col-sm-4">
                                <strong>
                                    <label for="bagianmodel" class="col-form-label">Work Section:<span
                                            class="text-danger">*</span></label>
                                </strong>
                                <div>
                                    <em class="text-secondary">Wajib diisi</em>
                                </div>
                            </div>

                            <div class="col-sm-8">
                                <div class="col-sm">
                                    <input type="text" wire:model.defer='bagianmodel' name="partof"
                                        id="bagianmodel"
                                        class="form-control @error('bagianmodel') is-invalid @enderror @if (!empty($bagianmodel) && !$errors->has('bagianmodel')) is-valid @endif"
                                        value="" placeholder="Bagian Pekerjaan" required>
                                    @error('bagianmodel')
                                        <div class="text-danger mt-1 mb-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-sm mt-2">
                                    <input type="text" wire:model.defer='city' name="city" id="city"
                                        class="form-control @error('city') is-invalid @enderror @if (!empty($city) && !$errors->has('city')) is-valid @endif"
                                        value="" placeholder="Kota" required>
                                    <div id="" class="form-text">
                                        <i>*Kota tempat barang diperlukan</i>
                                    </div>
                                    @error('city')
                                        <div class="text-danger mt-1 mb-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-12 mt-4">
                        <div class="row">
                            <div class="col-sm-4">
                                <label for="remark">
                                    <strong>Notes:</strong>
                                </label>
                            </div>

                            <div class="col-sm-8">
                                <div class="col-sm">
                                    <textarea id="remark" name="remark" wire:model.defer='notemodel' rows="4" class="form-control"></textarea>
                                    @error('notemodel')
                                        <div class="text-danger mt-1 mb-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-12 mt-4">
                        <div class="form-group">
                            <input type="hidden" name="status" value="New" class="form-control"
                                placeholder="status">
                            <input type="hidden" name="created_by" value="{{ Auth::id() }}"
                                class="form-control" placeholder="created by">
                            @error('created_by')
                                <div class="text-danger mt-1 mb-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

            </div>
            <div class="card-footer">
                <button type="submit" wire:click="savepr" wire:loading.attr="disabled"
                    class="btn btn-primary ml-3">Submit</button>
                <a class="btn btn-danger" href="{{ route('purchase_requests.index') }}"> Back</a>
            </div>

        </div>
    </div>
    @if ($showprojectadd)
        @include('components.modaladdproject')
    @endif

    <script>
        $('#project_id').select2({
                    theme: 'bootstrap-5'
                });
    </script>
</div>
