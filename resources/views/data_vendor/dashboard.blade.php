<x-vendors.app>
    <div class="mt-3">
        <h4>
            Welcome, {{ auth()->user()->name }}
        </h4>

        <div class="card mt-3">
            <div class="card-body">
                <p>
                    Terima kasih telah mendaftar sebagai vendor di {{ config('app.company_full_name') }}. Silahkan untuk
                    menambahkan barang yang akan dijual.
                </p>
                <p>
                    Anda dapat menambahkan barang dengan mengklik tombol <strong>Tambah Barang</strong> di bawah ini.
                </p>
                <a href="{{route('vendors.items')}}" class="btn btn-outline-primary">
                    Tambah Barang
                </a>
            </div>

        </div>
    </div>
</x-vendors.app>


