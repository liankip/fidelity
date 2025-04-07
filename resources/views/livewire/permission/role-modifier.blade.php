<div>
    <form wire:submit.prevent="submit">
        <div class="modal-header">
            <h4>Modify Roles - <b>{{ $selectedUser->name }}</b></h4>
            <button type="button" class="btn-close" wire:click="$emitUp('closeModal')"
                    aria-label="Close"></button>
        </div>
        <div class="modal-body relative">
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>Roles</th>
                    <th>Access</th>
                </tr>
                </thead>
                <tbody>
                @php
                    $userRole = $selectedUser->getRoleNames()->pluck('id')
                @endphp
                @foreach($roles as $role)
                    <tr>
                        <td>{{$role->name}}</td>
                        <td>
                            <input wire:model="roleChecked.{{$role->id}}" type="checkbox">
                        </td>
                    </tr>
                @endforeach

                </tbody>
            </table>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" wire:click="$emitUp('closeModal')"
                    data-bs-dismiss="modal">Batal
            </button>

            <button type="submit" class="btn btn-success" data-bs-dismiss="modal">Simpan
            </button>
        </div>
    </form>

</div>
