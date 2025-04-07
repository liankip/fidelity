<div class="card mt-5">
    <div class="card-body p-5">
        <div class="row">
            <h3>Notification Emails</h3>
            <p class="col-sm-8 text-secondary">
                Daftar email yang akan menerima notifikasi.
            </p>
        </div>

        <div class="px-5">
            <div class="row mt-3">
                <button class="w-auto mb-2 btn btn-sm btn-primary" data-bs-target="#emailModal" data-bs-toggle="modal">
                    <i class="fas fa-plus"></i>
                    Add Email
                </button>
                <table class="table table-borderless table-striped">
                    <thead>
                        <tr class="table-primary">
                            <th style="width: 30%">Name</th>
                            <th style="width: 30%">Email</th>
                            <th style="width: 30%">Type</th>
                            <th style="width: 30%">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($emails as $data)
                            <tr>
                                <td>{{ $data->name }}</td>
                                <td>{{ $data->email }}</td>
                                <td>
                                    @foreach ($data->types as $type)
                                        <span class="badge bg-primary">{{ $type->name }}</span>
                                    @endforeach
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-warning" data-bs-target="#editEmailModal"
                                        data-bs-toggle="modal" wire:click="edit({{ $data->id }})">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger" wire:click="delete({{ $data->id }})">
                                        <i class="fas fa-trash"></i>
                                    </button>

                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">No data available</td>
                            </tr>
                        @endforelse

                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <x-settings.notification-emails.modal_add_email :types="$types" />
    <x-settings.notification-emails.modal_edit_email :types="$types" />
</div>
