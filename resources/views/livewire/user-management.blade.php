<div>
    <h4>User Management</h4>
    <div class="mt-5" x-data="{ activeTab: 0 }">
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button :class="{ 'nav-link': true, 'active': activeTab == 0 }" id="users-tab" data-bs-toggle="tab"
                    data-bs-target="#users" type="button" role="tab" aria-controls="users" aria-selected="true"
                    @click="activeTab=0">Users
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button :class="{ 'nav-link': true, 'active': activeTab == 1 }" id="disabled-user-tab"
                    data-bs-toggle="tab" data-bs-target="#disabled-user" type="button" role="tab"
                    aria-controls="disabled-user" aria-selected="true" @click="activeTab=1">Disabled
                    Users
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button :class="{ 'nav-link': true, 'active': activeTab == 2 }" id="roles-tab" data-bs-toggle="tab"
                    data-bs-target="#roles" type="button" role="tab" aria-controls="roles" aria-selected="false"
                    @click="activeTab=2">Roles
                </button>
            </li>
        </ul>
        @foreach (['danger', 'warning', 'success', 'info'] as $key)
            @if (Session::has($key))
                <div class="alert alert-{{ $key }} alert-dismissible fade show mb-1 mt-1" role="alert">
                    {{ Session::get($key) }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                    </button>
                </div>
            @endif
        @endforeach
        <div class="tab-content" id="userManagement">
            <div x-show="activeTab==0" class="tab-pane fade show active" id="users" role="tabpanel"
                aria-labelledby="disabled-user-tab">
                <div class="d-flex justify-content-end">
                    <button class="btn btn-success mt-5" wire:click="openCreateUserModal">
                        Create User
                    </button>
                </div>
                <div class="card mt-2">
                    <div class="card-body p-5">
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" wire:model="search" name="search"
                                placeholder="Search" value="" aria-label="Recipient's username"
                                aria-describedby="button-addon2">
                        </div>
                        <div class="row mt-3">
                            <table class="table table-borderless table-striped">
                                <thead>
                                    <tr class="table-primary">
                                        <th style="">Name</th>
                                        <th style="">Email</th>
                                        <th style="">Roles</th>
                                        <th class="text-center" style="width: 35%">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($users as $user)
                                        <tr>
                                            <td>{{ $user->name }}</td>
                                            <td>{{ $user->email }}</td>
                                            <td>
                                                @foreach ($user->getRoleNames() as $role)
                                                    <span
                                                        class="badge {{ \App\Helpers\UserRoles::getColorBadge($role) }}">{{ $role }}</span>
                                                @endforeach

                                            </td>
                                            <td class="text-center d-flex gap-1">
                                                @if (auth()->user()->id !== $user->id)
                                                    <button class="btn"
                                                        wire:click="openRoleModal({{ $user->id }})">
                                                        <i class="fas fa-gear"></i>
                                                        Modify Roles
                                                    </button>
                                                    <button class="btn"
                                                        wire:click="openPermissionModal({{ $user->id }})">
                                                        <i class="fas fa-eye"></i>
                                                        View Permissions
                                                    </button>
                                                    @if ($user->is_disabled)
                                                        <button class="btn "
                                                            wire:click="activateUser({{ $user->id }})">
                                                            <i class="fas fa-check"></i>
                                                            Activate
                                                        </button>
                                                    @else
                                                        <button class="btn"
                                                            wire:click="deactivateUser({{ $user->id }})">
                                                            <i class="fas fa-trash"></i>
                                                            Deactivate
                                                        </button>
                                                    @endif
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div x-show="activeTab==1" class="tab-pane fade" id="disabled-user" role="tabpanel"
                aria-labelledby="disabled-user-tab">
                <div class="card mt-5">
                    <div class="card-body p-5">
                        <div class="row mt-3">
                            <table class="table table-borderless table-striped">
                                <thead>
                                    <tr class="table-primary">
                                        <th style="">Name</th>
                                        <th style="">Email</th>
                                        <th style="">Roles</th>
                                        <th class="text-center" style="width: 35%">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($disabledUsers as $user)
                                        <tr>
                                            <td>{{ $user->name }}</td>
                                            <td>{{ $user->email }}</td>
                                            <td>
                                                @foreach ($user->getRoleNames() as $role)
                                                    <span
                                                        class="badge {{ \App\Helpers\UserRoles::getColorBadge($role) }}">{{ $role }}</span>
                                                @endforeach

                                            </td>
                                            <td class="text-center mx-auto">
                                                @if (auth()->user()->id !== $user->id)
                                                    <button class="btn "
                                                        wire:click="activateUser({{ $user->id }})">
                                                        Activate
                                                    </button>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div x-show="activeTab==2" class="tab-pane fade" id="roles" role="tabpanel"
                aria-labelledby="roles-tab">
                <div class="card mt-5">
                    <div class="card-body p-5">
                        <div class="row mt-3">
                            <table class="table table-borderless table-striped text-center">
                                <thead>
                                    <tr class="table-primary">
                                        <td></td>
                                        @foreach ($roles as $role)
                                            <th>{{ $role->name }}</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($permissions as $permission)
                                        <tr>
                                            <td>
                                                {{ $permission->name }}
                                            </td>
                                            @foreach ($roles as $role)
                                                <td>
                                                    <input type="checkbox"
                                                        {{ $role->hasPermissionTo($permission->id) ? 'checked' : '' }}
                                                        class="role-checkbox" data-roleId="{{ $role->id }}"
                                                        data-permissionId="{{ $permission->id }}">
                                                </td>
                                            @endforeach
                                        </tr>
                                    @endforeach

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('.role-checkbox').on('change', function() {
                let roleId = $(this).attr('data-roleId');
                let permissionId = $(this).attr('data-permissionId');

                if (this.checked) {
                    Livewire.emit('attachPermissionRole', roleId, permissionId);
                } else {
                    Livewire.emit('detachPermissionRole', roleId, permissionId);
                }
            });
        });
    </script>
</div>
