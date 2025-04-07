<div>
    <div class="container mt-2">

        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

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
                            <div wire:ignore class="col-sm-8">
                                <select wire:model.defer='projectmodel' name="project_id" id="project_id"
                                        class="js-example-basic-single form-select @error('projectmodel') is-invalid @enderror @if (!empty($projectmodel) && !$errors->has('projectmodel')) is-valid @endif">
                                    <option value="" hidden readonly>Pilih Project</option>
                                    @foreach ($projects as $project)
                                        <option value="{{ $project->id }}">
                                            {{ $project->name }}<span>: </span>{{ $project->company_name }}
                                        </option>
                                    @endforeach
                                </select>

                                @error('projectmodel')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            @push('javascript')
                                <script>
                                    $(document).ready(function () {
                                        $('#project_id').select2({
                                            theme: 'bootstrap-5',
                                        });

                                        $(document).on('change', '#project_id', function (e) {
                                        @this.set('projectmodel', e.target.value)
                                            ;
                                        });
                                    });

                                    document.addEventListener("livewire:load", () => {
                                        Livewire.hook('message.processed', (message, component) => {
                                            $('#project_id').select2({
                                                theme: 'bootstrap-5',
                                            });

                                            $(document).on('change', '#project_id', function (e) {
                                            @this.set('projectmodel', e.target.value)
                                                ;
                                            });
                                        });
                                    });
                                </script>
                            @endpush
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
                                <input type="text" name="requester" id="requester" wire:model.defer='requestermodel'
                                       class="form-control @error('requestermodel') is-invalid @enderror @if (!empty($requestermodel) && !$errors->has('requestermodel')) is-valid @endif"
                                       value="" placeholder="Nama" required>
                                @error('requestermodel')
                                <div class="text-danger mt-1 mb-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                    </div>
                </div>

                @if(!empty($projectmodel))
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
                                <div class="row">
                                    <div class="{{ $partOf == 'retail' ? 'col-sm-6' : 'col-sm-12' }}">
                                        @php
                                            $tasks = App\Models\BOQ::where('project_id', $projectmodel)->select('task_number')->groupBy('task_number')->get()
                                        @endphp

                                        <select wire:model.debounce.500ms='partOf' class="form-select">
                                            <option value="" hidden readonly>Pilih Task</option>
                                            @foreach($tasks as $task)
                                                <option
                                                    value="{{ $task->task_number == '' ? 'retail' : $task->task_number }}">{{ $task->task_number == '' ? 'Retail' : $task->task_number }}
                                                </option>
                                            @endforeach
                                        </select>

                                        @error('partOf')
                                        <div class="text-danger mt-1 mb-1">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    @if($partOf == 'retail')
                                        <div class="col-sm-6">
                                            <input type="text" wire:model.defer='customPartOf' class="form-control"
                                                   placeholder="Masukkan Work Section">
                                            <div class="form-text">
                                                <i>*Masukkan work section jika WBS tidak ada</i>
                                            </div>
                                            @error('customPartOf')
                                            <div class="text-danger mt-1 mb-1">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    @endif
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
                @endif

                <div class="col-xs-12 col-sm-12 col-md-12 mt-4">
                    <div class="row">
                        <div class="col-sm-4">
                            <label for="remark">
                                <strong>Notes:</strong>
                            </label>
                        </div>

                        <div class="col-sm-8">
                            <div class="col-sm">
                                <textarea id="remark" name="remark" wire:model.defer='notemodel' rows="4"
                                          class="form-control"></textarea>
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
                        <input type="hidden" name="created_by" value="{{ Auth::id() }}" class="form-control"
                               placeholder="created by">
                        @error('created_by')
                        <div class="text-danger mt-1 mb-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="card-footer">
                <button type="submit" wire:click="savepr" wire:loading.attr="disabled"
                        class="btn btn-primary ml-3">Submit
                </button>
                <a class="btn btn-danger" href="{{ route('purchase_requests.index') }}"> Back</a>
            </div>
        </div>
    </div>
</div>
@push('scripts')
    <script>
        $(document).ready(function () {
            $('#project_id').select2({
                theme: 'bootstrap-5',
            });

            @if (old('project_id'))
            $('#project_id').val('{{ old('project_id') }}').trigger('change');
            @endif
        });
    </script>
@endpush
@if ($showprojectadd)
    @include('components.modaladdproject')
@endif
