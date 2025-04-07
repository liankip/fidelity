<div class="col-md-6 col-lg-4 col-xl-4 order-0 mb-4 mt-4">
    <div class="card h-100">
        <div class="card-header d-flex align-items-center justify-content-between pb-0">
            <div class="card-title mb-0">
                <h5 class="m-0 me-2">Kurs Mata Uang</h5>
                <small class="text-muted">USD</small>
            </div>
            <div class="dropdown">
                <button class="btn p-0" type="button" id="orederStatistics" data-bs-toggle="dropdown" aria-haspopup="true"
                    aria-expanded="false">
                    <i class="bx bx-dots-vertical-rounded"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="orederStatistics">
                    <button class="dropdown-item" wire:click='Refresh'>Refresh</button>
                </div>
            </div>
        </div>

        <div class="card-body">
            <div>
                <table class="table table-striped-columns">
                    <thead>
                        <tr>
                            <td>Base Currency</td>
                            <td>Covert Currency</td>
                            <td>Value</td>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($kurs as $kur)
                            <tr>
                                <td>{{ $kur->base }}</td>
                                <td>{{ $kur->convert }}</td>
                                <td>{{ $kur->converted_value }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>
