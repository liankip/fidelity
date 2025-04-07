<div>
    <button class="btn btn-success" type="button" wire:click="toggleModal">
        <i class="fas fa-plus"></i>
        Add group
    </button>
    @if($showModal)
        <div>
            <div class="bg-black opacity-25"
                 style="height: 100%;width: 100%;left: 0;top: 0;overflow: hidden;position: fixed;z-index: 999"></div>

            <div class="modal d-block" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"
                 id="myModal">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form wire:submit.prevent="submit" enctype="multipart/form-data">
                            @csrf
                            <div class="modal-header">
                                <h3>Add new group</h3>
                                <button type="button" class="btn-close" wire:click="toggleModal"
                                        aria-label="Close"></button>
                            </div>
                            <div class="modal-body relative">
                                <div class="form-group">
                                    <strong>Group Name</strong>
                                    <input type="text" class="form-control" wire:model="name" name="name" required>
                                    @error('name')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" wire:click="toggleModal"
                                        data-bs-dismiss="modal">Batal
                                </button>

                                <button type="submit" class="btn btn-success">Simpan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    @endif
</div>
