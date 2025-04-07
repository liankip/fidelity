<div>
    <h2 class="primary-color-sne">User Management</h2>
    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-dismissible fade show mb-2" role="alert">
            {{ $message }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    <div class="card mt-5 primary-box-sne">
        <div class="card-body p-5">
            <a href="{{ route('hrd.alluserForm') }}" class="btn btn-success mb-3"><i class="fa-solid fa-plus"></i> Create</a>
            <div class="input-group mb-3">
                <input type="text" class="form-control" wire:model="search" name="search" placeholder="Search"
                    value="" aria-label="Recipient's username" aria-describedby="button-addon2">
            </div>

            <div class="mt-3">
                <table class="table w-100">
                    <thead class="thead-light">
                        <tr class="table-primary">
                            <th class="text-center border-top-left" style="width: 5%">No</th>
                            <th style="width: 25%">Nama Lengkap</th>
                            <th style="width: 10%">Tier</th>
                            <th style="width: 15%">Jabatan</th>
                            <th style="width: 10%">Pendidikan</th>
                            <th style="width: 10%">Status</th>
                            <th class="text-center" style="width: 15%">Jenis Kelamin</th>
                            <th class="text-center border-top-right" style="width: 10%">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $key => $user)
                            <tr>
                                <td class="text-center" style="vertical-align: middle;">{{ $key + 1 }}</td>
                                <td style="vertical-align: middle;">
                                    <div>{{ $user->name }}</div>
                                    <div class="font-bold text-sm">NIK: {{ $user->nik ? $user->nik : '-' }}</div>
                                </td>
                                <td style="vertical-align: middle;">
                                    @if ($user->tier == 1)
                                        <div style="width: 30px;" class="shadow-sm badge bg-danger text-center">1</div>
                                    @elseif ($user->tier == 2)
                                        <div style="width: 30px;" class="shadow-sm badge bg-primary text-center">2</div>
                                    @elseif ($user->tier == 3)
                                        <div style="width: 30px;" class="shadow-sm badge bg-secondary text-center">3
                                        </div>
                                    @elseif ($user->tier == 4)
                                        <div style="width: 30px;" class="shadow-sm badge bg-secondary text-center">4
                                        </div>
                                    @elseif ($user->tier == 5)
                                        <div style="width: 30px;" class="badge text-bg-light text-center">5
                                        </div>
                                    @endif
                                </td>
                                <td style="vertical-align: middle;">{{ $user->position }}</td>
                                <td style="vertical-align: middle;">{{ $user->education }}</td>
                                <td style="vertical-align: middle;">
                                    <span class="text-sm font-bold">{{ $user->status }}</span>
                                </td>
                                <td class="text-center" style="vertical-align: middle;">
                                    <div class="font-bold d-flex justify-content-center">
                                        @if ($user->gender == 'LAKI-LAKI')
                                            <div>
                                                <i class="fa-solid fa-mars text-primary"></i>
                                            </div>
                                        @elseif($user->gender == 'PEREMPUAN')
                                            <div>
                                                <i class="fa-solid fa-venus text-danger"></i>
                                            </div>
                                        @endif
                                    </div>
                                </td>
                                <td class="text-center" style="vertical-align: middle;">
                                    <a class="btn btn-sm btn-success"
                                        href="{{ route('hrd.editUser', ['id' => $user->id]) }}">
                                        Edit
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="d-flex justify-content-end">
                    {{$users->links()}}
                </div>
            </div>
        </div>
    </div>
</div>
