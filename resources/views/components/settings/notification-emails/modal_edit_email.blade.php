@props(['types'])
<div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" id="editEmailModal"
    wire:ignore>
    <div class="modal-dialog">
        <div class="modal-content">
            <form wire:submit.prevent="submitEdit" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h3>Edit Email</h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body relative">
                    <div class="form-group">
                        <strong>Name</strong>
                        <input type="text" class="form-control" wire:model="name" name="name" required>
                        @error('name')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group mt-3">
                        <strong>Email</strong>
                        <input type="email" class="form-control" wire:model="email" name="email" required>
                        @error('email')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group mt-3">
                        <strong>Types</strong>
                        @foreach ($types as $type)
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="type"
                                    wire:model="checked_types.{{ $type->id }}" value="{{ $type->id }}">
                                {{ $type->name }}

                            </div>
                        @endforeach

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal
                    </button>

                    <button type="button" class="btn btn-success" data-bs-dismiss="modal" wire:click="update">Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
