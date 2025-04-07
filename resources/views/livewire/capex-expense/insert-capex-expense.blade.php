<div class="mt-2">
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <a href="{{ route('capex-expense.index') }}" class="third-color-sne"> <i
                        class="fa-solid fa-chevron-left fa-xs"></i> Back</a>
                <h2 class="primary-color-sne">Create Capex Expense</h2>
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
        </div>
    </div>

    <div class="card mt-5 primary-box-shadow">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12 mt-2">
                    <div class="form-group">
                        <strong>Project Name <span class="text-danger">*</span></strong>
                        <input class="form-control" type="text" placeholder="Project Name" wire:model="project_name">
                        @error('project_name')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-12 mt-2">
                    <div class="form-group">
                        <strong>ROI <span class="text-danger">*</span></strong>
                        <select class="form-select" wire:model="roi">
                            <option value="">-- Pilih ROI --</option>
                            <option value="RND">RND</option>
                            <option value="NA">NA</option>
                            <option value="__YEARS">__ YEARS</option>
                        </select>

                        @error('roi')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror

                        @if ($roi === '__YEARS')
                            <input type="number" class="form-control mt-2" placeholder="Masukkan jumlah tahun"
                                wire:model="custom_roi">

                            @error('custom_roi')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        @endif
                    </div>
                </div>
                <div class="col-md-12 mt-2">
                    <div class="form-group">
                        <strong>Total Budget <span class="text-danger">*</span></strong>
                        <input class="form-control" type="text" placeholder="Total Budget" wire:model="total_budget">
                        @error('total_budget')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="text-end">
                <a href="{{ route('capex-expense.index') }}" class="btn btn-danger ml-3">Cancel</a>
                <button class="btn btn-primary ml-3" wire:click="insert"><i
                        class="fa-solid fa-floppy-disk pe-2"></i>Save</button>
            </div>
        </div>
    </div>
</div>
