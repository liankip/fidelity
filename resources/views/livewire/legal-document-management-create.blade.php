<div>
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <div class="d-flex">
                    <a href="{{ route('legal-document-management.index') }}" class="btn btn-sm btn-secondary my-auto">
                        <i class="fa-solid fa-angle-left"></i>
                    </a>
                    <h2 class="my-auto">Legal Document Management</h2>
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
                                    <strong>Nomor dokumen</strong>
                                    <span class="text-danger">*</span>
                                    <div>
                                        <input type="text" wire:model="nomor_dokumen" class="form-control">
                                    </div>
                                    @error('nomor_dokumen')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <strong>Nama Dokumen</strong>
                                    <span class="text-danger">*</span>
                                    <div>
                                        <input type="text" wire:model="nama_dokumen" class="form-control">
                                    </div>
                                    @error('nama_dokumen')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <strong>Asal Instansi</strong>
                                    <div>
                                        <input type="text" wire:model="asal_instansi" class="form-control">
                                    </div>
                                    @error('asal_instansi')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <strong>Expired</strong>
                                    <div>
                                        <input type="date" wire:model="expired" class="form-control">
                                    </div>
                                    @error('expired')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <strong>Upload file (PDF)</strong>
                                    <span class="text-danger">*</span>
                                    <div class="d-flex gap-2">
                                        <input type="file" class="form-control" wire:model="file_upload" accept="application/pdf">
                                    </div>
                                    @error('file_upload')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                    <div wire:loading wire:target="file_upload">Uploading...</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button wire:click.prevent="create" wire:loading.remove wire:target="file_upload, create"
                        type="submit" class="btn btn-success">
                        Create +
                    </button>

                    <button wire:loading wire:target="file_upload, create" type="submit" class="btn btn-secondary"
                        disabled>
                        Saving...
                    </button>
                    <a class="btn btn-secondary" href="{{ route('legal-document-management.index') }}">
                        Cancel
                    </a>
                </form>
            </div>
        </div>
    </div>
</div>
