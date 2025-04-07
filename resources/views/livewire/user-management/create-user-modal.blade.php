<div>
    <div class="modal-header">
        <h1 class="modal-title fs-5" id="createUserModalLabel">Create User</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <form wire:submit.prevent="store">
        @csrf
        <div class="modal-body">
            <div class="mb-3">
                <label for="name">Name</label>
                <input type="text" class="form-control" id="name" wire:model="name" required />
            </div>
            <div class="mb-3">
                <label for="username">Username</label>
                <input type="text" class="form-control" id="username" wire:model="username" required />
                @error('username')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" wire:model="email" required />
                @error('email')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="phone_number">Phone Number
                    <smal>(optional)</smal>
                </label>
                <input type="text" class="form-control" id="phone_number" wire:model="phone_number" />
            </div>
            <div class="mb-3">
                <label for="roles">Roles</label>
                <select class="form-select" id="roles" wire:model="role">
                    @foreach ($roles as $role)
                        <option value="{{ $role->id }}">{{ $role->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" wire:model="password" required />
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" wire:click="$emitUp('closeModal')"
                data-bs-dismiss="modal">Batal</button>

            <button type="submit" class="btn btn-success">Save</button>
        </div>
    </form>
</div>
