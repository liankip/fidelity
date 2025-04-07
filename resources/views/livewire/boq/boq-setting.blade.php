<div class="container mt-2">
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>{{ $project->name }} - Settings</h2>
                <hr>
            </div>
        </div>
    </div>
    <x-common.notification-alert />
    <div class="card mt-5">
        <div class="card-body p-5">
            <div class="row">
                <h3>
                    Purchase Order Approval
                </h3>
                <p class="col-sm-8 text-secondary">
                    Daftar user yang dapat melakukan approval purchase order pada project ini. <br>
                    <small class="text-muted">
                        *Jika tidak ada user yang ditambahkan, maka semua manager dapat melakukan approval.
                    </small>
                </p>

            </div>

            <div class="px-5">
                <div class="row mt-3">
                    <button class="w-auto mb-2 btn btn-sm btn-primary" data-bs-target="#addUser" data-bs-toggle="modal">
                        <i class="fas fa-plus"></i>
                        Add User
                    </button>
                    <table class="table table-borderless table-striped">
                        <thead>
                            <tr class="table-primary">
                                <th style="width: 30%">Name</th>
                                <th style="width: 30%">Email</th>
                                <th style="width: 30%">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($project->purchase_order_approver as $user)
                                <tr>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        <button class="btn btn-sm btn-danger" wire:click="remove({{ $user->id }})">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">
                                        Tidak ada user yang ditambahkan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <x-common.modal id="addUser" title="Add New Approver">
        <x-slot:modal-body>
            <x-common.select2-normal name="approver_id" label="User" placeholder="Select User"
                wire:model="approver_id">
                @foreach ($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                @endforeach
            </x-common.select2-normal>
        </x-slot:modal-body>
        <x-slot:modal-footer>
            <x-common.modal.button-cancel />
            <button type="button" class="btn btn-success" wire:click="save">Save</button>
        </x-slot:modal-footer>
    </x-common.modal>
</div>
