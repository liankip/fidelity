@props(['types'])
<div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" id="emailModal"
    wire:ignore>
    <div class="modal-dialog">
        <div class="modal-content">
            <form wire:submit.prevent="submit" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h3>Add Email</h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body relative">
                    <div class="form-group">
                        <strong>Name</strong>
                        <input type="text" class="form-control" wire:model="name" name="name">
                        @error('name')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group mt-3">
                        <strong>Email</strong>
                        <input type="email" class="form-control" wire:model="email" name="email">
                        @error('email')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group mt-3">
                        <strong>Type</strong>
                        <select class="form-select" name="type" wire:model="type_id">
                            @foreach ($types as $type)
                                <option value="{{ $type->id }}">{{ $type->name }}</option>
                            @endforeach
                        </select>

                        @error('email')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:click="closeModal"
                        data-bs-dismiss="modal">Batal
                    </button>

                    <button type="submit" class="btn btn-success" data-bs-dismiss="modal">Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
