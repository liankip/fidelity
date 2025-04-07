<div>
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <div class="d-flex">
                    <a href="{{ route('k3.workInduction') }}" class="btn btn-sm btn-secondary my-auto">
                        <i class="fa-solid fa-angle-left"></i>
                    </a>
                    <h2 class="my-auto">Safety Induction
                    </h2>
                </div>
                <hr>
            </div>
        </div>
        <div class="card mt-2">
            <div class="card-body">
                <form method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <strong>Nama Petugas</strong>
                                <span class="text-danger">*</span>
                                <div>
                                    <input type="text" wire:model="name" class="form-control">
                                </div>
                                @error('name')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <strong>Jabatan</strong>
                                <span class="text-danger">*</span>
                                <div>
                                    <input type="text" wire:model="jabatan" class="form-control">
                                </div>
                                @error('jabatan')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <strong>Upload file (PDF)</strong>
                                    <span class="text-danger">*</span>
                                    <div class="d-flex gap-2">
                                        <input type="file" class="form-control" wire:model="new_file"
                                            accept="application/pdf">
                                    </div>
                                    <div wire:loading wire:target="new_file">Uploading...</div>
                                    <a href="{{ asset('storage/' . $file_upload) }}"
                                        target="_blank"class="btn btn-sm btn-warning mt-1">Download Dokumen Saat Ini</a>
                                    @error('new_file')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <button wire:click.prevent="store({{$edit_id}})" wire:loading.remove type="submit" class="btn btn-success">Edit</button>
                    <button wire:loading wire:target='store' type="submit" class="btn btn-secondary"
                        disabled>Saving...</button>
                    <a class="btn btn-secondary" href="{{ route('k3.workInduction') }}">
                        Cancel
                    </a>
                </form>
            </div>
        </div>
    </div>
</div>
