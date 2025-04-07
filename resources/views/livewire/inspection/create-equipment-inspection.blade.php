<div>
    <h1>Equipment Checklist Inspection Form</h1>

    <div class="bg-white p-4">
        <form method="POST" wire:submit.prevent="handleSubmit">
            @csrf
            <div class="mb-3">
                <label for="unit" class="form-label">
                    <strong>
                        Unit<span class="text-danger">*</span>
                    </strong>
                </label>
                <input type="text" class="form-control" id="unit" name="unit" wire:model.defer="unit">
                @error('unit')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="work" class="form-label">
                    <strong>
                        Work<span class="text-danger">*</span>
                    </strong>
                </label>
                <input type="text" class="form-control" id="work" name="work" wire:model.defer="work">
                @error('work')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="work" class="form-label">
                    <strong>
                        Equipment<span class="text-danger">*</span>
                    </strong>
                </label>
            
                @foreach($equipment as $key => $value)
                    <div class="mb-2 d-flex">
                        <input type="text" class="form-control" id="equipment_{{ $key }}" name="equipment[]" wire:model.defer="equipment.{{ $key }}">
                        @error('equipment.'.$key)
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                        @if ($key > 0)
                            <button wire:click.prevent="removeField({{ $key }})" class="btn btn-danger">X</button>
                        @endif
                    </div>
                @endforeach
            
                <button wire:click.prevent="addEquipmentField" class="btn btn-success">+</button>
            </div>

            <div class="mb-3">
                <label for="name" class="form-label">
                    <strong>
                        Inspection Officer<span class="text-danger">*</span>
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
                <label for="note" class="form-label">
                    <strong>
                        Note
                    </strong>
                </label>
                <textarea class="form-control" id="note" name="note" wire:model.defer="note">
                </textarea>
                @error('note')
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
