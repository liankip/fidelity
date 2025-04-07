<div>
    <h1>MCU Form</h1>

    <div class="bg-white p-4">
        <form method="POST" wire:submit.prevent="handleSubmit">
            @csrf
            <div class="mb-3">
                <label for="name" class="form-label">Nama</label>
                <select class="form-control" id="name" wire:model.defer="name">
                    <option value="">-- Pilih Nama --</option>
                    @foreach ($dataUser as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
                @error('name')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="location" class="form-label">Tanggal</label>
                <input type="date" class="form-control" id="location" name="location" wire:model.defer="date">
                @error('date')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="row">
                <div class="col">
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>Upload file (PDF)</strong>
                            <span class="text-danger">*</span>
                            <div class="d-flex gap-2">
                                <input type="file" class="form-control" wire:model.defer="upload"
                                    accept="application/pdf">
                            </div>
                            <div wire:loading wire:target="upload">Uploading...</div>
                            @error('upload')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-primary" wire:loading.attr="disabled"
                    wire:target="upload">Submit</button>
            </div>
        </form>
    </div>
</div>
