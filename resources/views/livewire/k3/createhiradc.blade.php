<div>
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <div class="d-flex">
                    <a href="{{ route('k3.hiradc') }}" class="btn btn-sm btn-secondary my-auto">
                        <i class="fa-solid fa-angle-left"></i>
                    </a>
                    <h2 class="my-auto">HIRADC</h2>
                </div>
                <hr>
            </div>
        </div>
        <div class="card mt-2">
            <div class="card-body">
                <form method="POST">
                    @csrf
                    <div class="row">
                        <div class="col">
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <strong>Nama Dokumen</strong>
                                    <span class="text-danger">*</span>
                                    <div>
                                        <input type="text" wire:model="name" class="form-control">
                                    </div>
                                    @error('name')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <strong>Dept</strong>
                                    <span class="text-danger">*</span>
                                    <div>
                                        <input type="text" wire:model="dept" class="form-control">
                                    </div>
                                    @error('dept')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <strong>Unit Kerja</strong>
                                    <span class="text-danger">*</span>
                                    <div>
                                        <input type="text" wire:model="work_unit" class="form-control">
                                    </div>
                                    @error('work_unit')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <strong>Area</strong>
                                    <span class="text-danger">*</span>
                                    <div>
                                        <input type="text" wire:model="area" class="form-control">
                                    </div>
                                    @error('area')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <strong>Nomor dokument</strong>
                                    <span class="text-danger">*</span>
                                    <div>
                                        <input type="text" wire:model="document_number" class="form-control">
                                    </div>
                                    @error('document_number')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <strong>Tanggal efektif</strong>
                                    <div>
                                        <input type="date" wire:model="effective_date" class="form-control">
                                    </div>
                                    @error('effective_date')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <strong>Nomor revisi</strong>
                                    <div>
                                        <input type="text" wire:model="revision_number" class="form-control">
                                    </div>
                                    @error('revision_number')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <strong>Reviewed date</strong>
                                    <div>
                                        <input type="date" wire:model="reviewed_date" class="form-control">
                                    </div>
                                    @error('reviewed_date')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <strong>Next reviewd</strong>
                                    <div>
                                        <input type="date" wire:model="next_reviewed" class="form-control">
                                    </div>
                                    @error('next_reviewed')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <strong>Upload file (PDF)</strong>
                                    <div class="d-flex gap-2">
                                        <input type="file" class="form-control" wire:model="fileUpload" accept="application/pdf">
                                    </div>
                                    <div wire:loading wire:target="fileUpload">Uploading...</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button wire:click.prevent="store" wire:loading.remove type="submit" class="btn btn-success">Create
                        +</button>
                    <button wire:loading wire:target='store' type="submit" class="btn btn-secondary"
                        disabled>Saving...</button>
                    <a class="btn btn-secondary" href="{{ route('k3.hiradc') }}">
                        Cancel
                    </a>
                </form>
            </div>
        </div>
    </div>
</div>
