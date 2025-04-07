<div>
    <h4>User Management</h4>
    <div class="card mt-2">
        <div class="card-body p-5">
            <form method="POST">
                @csrf
                <div class="row">
                    <div class="col">
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>NIK</strong>
                                {{-- <span class="text-danger">*</span> --}}
                                <input type="number" wire:model="nik" class="form-control">
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Tier</strong>
                                {{-- <span class="text-danger">*</span> --}}
                                <input type="number" wire:model="tier" class="form-control">
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Name</strong>
                                <span class="text-danger">*</span>
                                <input type="text" wire:model="name" class="form-control">
                            </div>
                            @error('name')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Email</strong>
                                <span class="text-danger">*</span>
                                <input type="text" wire:model="email" class="form-control">
                            </div>
                            @error('email')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Password</strong>
                                <span class="text-danger">*</span>
                                <input type="text" wire:model="password" class="form-control">
                            </div>
                            @error('password')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Position</strong>
                                {{-- <span class="text-danger">*</span> --}}
                                <input type="text" wire:model="position" class="form-control">
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Education</strong>
                                {{-- <span class="text-danger">*</span> --}}
                                <input type="text" wire:model="education" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Status</strong>
                                {{-- <span class="text-danger">*</span> --}}
                                <select class="form-select" wire:model="status" aria-label="Default select example">
                                    <option selected>Pilih status pekerja</option>
                                    <option value="PKWT">PKWT</option>
                                    <option value="NON-PKWT">NON-PKWT</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Contact Number</strong>
                                {{-- <span class="text-danger">*</span> --}}
                                <input type="text" wire:model="contract" class="form-control">
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Gender</strong>
                                {{-- <span class="text-danger">*</span> --}}
                                <select class="form-select" wire:model="gender" aria-label="Default select example">
                                    <option selected>Pilih gender</option>
                                    <option value="LAKI-LAKI">Laki-laki</option>
                                    <option value="PEREMPUAN">Perempuan</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Tanggal Lahir</strong>
                                {{-- <span class="text-danger">*</span> --}}
                                <input type="date" wire:model="dob" class="form-control">
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Tanggal Diterima</strong>
                                {{-- <span class="text-danger">*</span> --}}
                                <input type="date" wire:model="acc_date" class="form-control">
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Alamat</strong>
                                {{-- <span class="text-danger">*</span> --}}
                                <input type="text" wire:model="address" class="form-control">
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Disability</strong>
                                {{-- <span class="text-danger">*</span> --}}
                                <input type="text" wire:model="disability" class="form-control">
                            </div>
                        </div>
                    </div>

                </div>
                <button wire:click.prevent="store" wire:loading.remove type="submit" class="btn btn-success">Create
                    +</button>
                <button wire:loading wire:target="store" type="button" class="btn btn-secondary" disabled>Loading</button>
                <a href="{{route('hrd.alluser')}}" class="btn btn-danger" wire:click="handleCreateForm">Cancel</a>
            </form>
        </div>
    </div>
</div>
