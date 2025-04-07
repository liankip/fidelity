<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <a class="btn btn-primary" href="{{ url()->previous() }}"> Back</a>
                </div>
                <div class="card-body">
                    <div>
                        @if (session()->has('message'))
                            <div class="alert alert-success">
                                {{ session('message') }}
                            </div>
                        @endif
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Name:</strong>
                                <input type="text" name="name" class="form-control" placeholder="Name"
                                    wire:model="name">
                                @error('name')
                                    <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Email:</strong>
                                <input disabled type="email" name="email" class="form-control" placeholder="Email"
                                    wire:model="email">
                                @error('email')
                                    <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Phone:</strong>
                                <input type="number" name="phone_number" class="form-control"
                                    placeholder="phone_number" wire:model="phone_number">
                                @error('phone_number')
                                    <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                {{-- <strong>Created By:</strong> --}}
                                <input type="hidden" name="created_by" placeholder="created by" readonly="readonly">
                                @error('created_by')
                                    <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>


                    </div>
                </div>
                <div class="card-footer">
                    <button wire:click="update()" class="btn btn-primary ml-3">Submit</button>

                    @if ($this->update_loading)
                        Processing...
                    @endif
                </div>
            </div>
        </div>
    </div>


    <div class="row justify-content-center mt-3">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Ganti Password</div>

                <div class="card-body">
                    <div>
                        @if (session()->has('message_change_password'))
                            <div class="alert alert-success">
                                {{ session('message_change_password') }}
                            </div>
                        @endif
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Password Lama:</strong>
                                <input type="password" name="old_password" class="form-control"
                                    placeholder="Password Lama" wire:model="old_password">
                                @error('old_password')
                                    <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Password Baru:</strong>
                                <input type="password" name="new_password" class="form-control"
                                    placeholder="Password Baru" wire:model="new_password">
                                @error('new_password')
                                    <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Konfirmasi Password Baru:</strong>
                                <input type="password" name="confirm_new_password" class="form-control"
                                    placeholder="Konfirmasi Password Baru" wire:model="confirm_new_password">
                                @error('confirm_new_password')
                                    <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>


                    </div>
                </div>

                <div class="card-footer">
                    <button wire:click="updatePassword()" class="btn btn-primary ml-3">Submit</button>

                    @if ($this->update_loading)
                        Processing...
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
