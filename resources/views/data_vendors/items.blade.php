@extends('components.vendors.app')

@section('content')
    <div>
        <div class="container mt-2">
            <div class="row">
                <div class="col-lg-12 margin-tb">
                    <x-common.notification-alert/>
                    <button class="btn btn-primary"
                            wire:click="$emitUp('openModal', ['name' => 'vendors.create-item-modal'])">
                        <i class="fas fa-plus"></i>
                        Tambah Barang
                    </button>
                    <div class="card mt-3">
                        <div class="card-body">
                            <table class="table" id="table">
                                <thead class="thead-light">
                                <tr>
                                    <th class="align-middle" style="width: 5%">#</th>
                                    <th class="align-middle">Nama Barang</th>
                                    <th class="align-middle">Merk</th>
                                    <th class="align-middle">Kategori Barang</th>
                                    <th class="align-middle">Jenis Barang</th>
                                    <th class="align-middle">Satuan</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <script>
                $(document).ready(function () {
                    const dTable = new DataTable('#table', {
                        ordering: false,
                    });
                });
            </script>
        </div>
    </div>

@endsection
