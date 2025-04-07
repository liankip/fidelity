<div>
    <h1>APD Form</h1>

    <div class="bg-white p-4">
        <form method="POST" wire:submit.prevent="handleSubmit">
            @csrf
            <div class="mb-3">
                <label for="name" class="form-label">
                    <strong>
                        Nama<span class="text-danger">*</span>
                    </strong>
                </label>
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
                <label for="date" class="form-label">
                    <strong>
                        Tanggal<span class="text-danger">*</span>
                    </strong>
                </label>
                <input type="date" class="form-control" id="date" name="date" wire:model.defer="date">
                @error('date')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">
                    <strong>
                        Description
                    </strong>
                </label>
                <textarea class="form-control" id="description" name="description" wire:model.defer="description">
                </textarea>
                @error('description')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="row">
                <div class="col">
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>Upload file (PDF)<span class="text-danger">*</span></strong>
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
