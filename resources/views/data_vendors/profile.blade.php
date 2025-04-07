<x-vendors.app>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <x-common.notification-alert/>

                <div class="card">
                    <form method="POST" action="{{route('vendors.profile.update')}}">
                        @method('PATCH')
                        @csrf
                        <div class="card-body">
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="form-group">
                                        <x-common.input label="Nama" name="name" value="{{auth()->user()->name}}"/>
                                    </div>
                                </div>

                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="form-group">
                                        <x-common.input label="Email" name="email" type="email" disabled
                                                        value="{{auth()->user()->email}}"/>
                                    </div>
                                </div>

                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="form-group">
                                        <x-common.input label="No Telp" name="phone_number" type="tel"
                                                        value="{{auth()->user()->phone_number}}"/>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-end mt-3">
                                    <button type="submit" class="btn btn-primary">Simpan</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="row justify-content-center mt-3">
            <div class="col-md-8">
                <div class="card">
                    <h5 class="card-header">Ganti Password</h5>
                    <form method="POST" action="{{route('vendors.profile.update')}}">
                        @method('PATCH')
                        @csrf
                        <div class="card-body">
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="form-group">
                                        <x-common.input label="Password Lama" name="old_password" type="password"/>
                                    </div>
                                </div>

                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="form-group">
                                        <x-common.input label="Password Baru" name="new_password" type="password"/>
                                    </div>
                                </div>

                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="form-group">
                                        <x-common.input label="Konfirmasi Password Baru"
                                                        name="confirm_new_password"
                                                        type="password"/>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-end mt-3">
                                    <button type="submit" class="btn btn-primary">Simpan</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</x-vendors.app>
