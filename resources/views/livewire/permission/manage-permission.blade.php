<div>
    <form wire:submit.prevent="submit">
        <div class="modal-header">
            <h4>Permissions</h4>
            <button type="button" class="btn-close" wire:click="$emit('closeModal')"
                    aria-label="Close"></button>
        </div>
        <div class="modal-body ">
            <div class="d-flex gap-2 flex-wrap">
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>Permission</th>
                        <th>Access</th>
                    </tr>
                    </thead>
                    <tbody>
                    @php
                        $rolePermission = $selectedUser->getPermissionsViaRoles()
                            ->pluck('id')
                    @endphp
                    @foreach($allPermissions as $permission)
                        <tr>
                            <td>{{$permission->name}}</td>
                            <td>
                                <input wire:model="checkPermissions.{{$permission->id}}" type="checkbox"
                                    {{in_array($permission->id, $rolePermission->toArray()) ? 'disabled' : ''}}
                                >
                            </td>
                        </tr>
                    @endforeach

                    </tbody>
                </table>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" wire:click="$emit('closeModal')"
                    data-bs-dismiss="modal">Batal
            </button>

            <button type="submit" class="btn btn-success"
                    data-bs-dismiss="modal" wire:click="submit">Simpan
            </button>
        </div>
    </form>

</div>
