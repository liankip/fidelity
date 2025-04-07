@php use App\Models\Sku; @endphp
<div>
    <div class="mt-2">
        <div class="row">
            <div class="col-lg-12 margin-tb">
                <div class="pull-left">
                    <h2 class="primary-color-sne">Sales</h2>
                </div>

                @foreach (['danger', 'warning', 'success', 'info'] as $key)
                    @if (Session::has($key))
                        <div class="alert alert-{{ $key }} alert-dismissible fade show mb-1 mt-1" role="alert">
                            {{ Session::get($key) }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                            </button>
                        </div>
                    @endif
                @endforeach

                <div class="pull-right mb-2 mt-5 d-flex justify-content-between">
                    <div class="d-flex gap-3">
                        <div class="mr-2">
                            <a class="btn btn-success" href="{{ route('sales.insert') }}">
                                <i class="fas fa-plus pe-2"></i>
                                Create Sales
                            </a>
                        </div>
                    </div>
                </div>

                <ul class="nav nav-tabs mb-3">
                    <li class="nav-item">
                        <button class="nav-link active" id="all-sales-order-tab" data-bs-toggle="tab"
                            data-bs-target="#all-sales-order" type="button">
                            All Sales Order
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link" id="order-by-customer-tab" data-bs-toggle="tab"
                            data-bs-target="#order-by-customer" type="button">
                            Order by Customer
                        </button>
                    </li>
                </ul>

                <div class="card mt-2 primary-box-shadow">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="w-100">
                                <form action="" method="get" class="d-flex">
                                    <div class="input-group">
                                        <input class="form-control" wire:model="search" type="search"
                                            placeholder="Search">
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="tab-content">
                            <div class="tab-pane fade show active" id="all-sales-order">
                                <table class="table mt-3 primary-box-shadow">
                                    <thead class="thead-light">
                                        <tr class="text-center align-items-center">

                                            <th class="border-top-left">No</th>
                                            <th>Customer</th>
                                            <th>SKU</th>
                                            <th>Quantity</th>
                                            <th>Modal</th>
                                            <th>Deadline</th>
                                            <th>Status</th>

                                            <th class="border-top-right">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($sales as $index => $s)
                                            <tr class="text-center align-items-center">

                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $s->customer->name }}</td>
                                                <td>
                                                    <ul class="list-group list-group-flush">
                                                        @foreach (json_decode($s->product) as $p)
                                                            @php
                                                                $name = Sku::find($p->product)->name;
                                                            @endphp
                                                            <li class="list-group-item border-0">
                                                                <span class="bullet-point"></span> {{ $name }}
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                </td>
                                                <td>
                                                    <ul class="list-group list-group-flush">
                                                        @if ($s->status === 'PENDING')
                                                            @foreach (json_decode($s->product) as $p)
                                                                @php
                                                                    $availability = collect(
                                                                        $s->availability,
                                                                    )->firstWhere('product_id', $p->product);
                                                                    $isAvailable = $availability['stock'] ?? 0 > 0;
                                                                @endphp
                                                                <li class="list-group-item border-0">
                                                                    {{ $p->qty }}

                                                                    <button type="button" class="btn p-0 border-0"
                                                                        data-bs-toggle="tooltip"
                                                                        data-bs-placement="right"
                                                                        title="Available stock: {{ $availability['stock'] ?? 0 }}">

                                                                        <i
                                                                            class="fas fa-info-circle {{ $isAvailable ? 'text-success' : 'text-danger' }}"></i>
                                                                    </button>
                                                                </li>
                                                            @endforeach
                                                        @endif
                                                    </ul>
                                                </td>
                                                <td>Rp.{{ number_format($s->total_modal) }}</td>
                                                <td>{{ \Carbon\Carbon::parse($s->finish_date)->format('d-m-Y') }}</td>
                                                <td>
                                                    @if ($s->status === 'PENDING')
                                                        <small class="badge badge-danger">Pending</small>
                                                    @elseif($s->status === 'COMPLETED')
                                                        <small class="badge badge-success">Completed</small>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($s->status === 'PENDING')
                                                        <div class="d-flex flex-column align-items-start mb-3">
                                                            <button class="btn btn-success mb-2 w-75"
                                                                @if (!$s->is_available) disabled @endif
                                                                wire:click="completeSales({{ $s->id }})">Complete</button>
                                                            <a href="{{ route('sales.edit', $s->id) }}"
                                                                class="btn btn-outline-primary w-75">Edit</a>
                                                            <a href="{{ route('work-order.insert', $s->id) }}"
                                                                class="btn btn-outline-info mt-2">Work Order</a>
                                                        </div>
                                                    @endif
                                                    @if ($s->status === 'COMPLETED')
                                                        <a class="btn btn-primary" target="_blank"
                                                            href="{{ route('print-surat-jalan-sales.index', $s->id) }}">Surat
                                                            Jalan</a>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td class="text-center" colspan="7">No Data Sales</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <div class="tab-pane fade" id="order-by-customer">
                                <table class="table mt-3 primary-box-shadow">
                                    <thead class="thead-light">
                                        <tr class="text-center align-items-center">

                                            <th class="border-top-left">No</th>
                                            <th>Customer</th>
                                            <th>Product</th>
                                            <th>Tata Cara Bayar</th>
                                            <th>Notes Pembeli</th>
                                            <th>Tanggal Selesai</th>
                                            <th class="border-top-right">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($sales as $index => $s)
                                            <tr class="text-center align-items-center">

                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $s->customer->name }}</td>
                                                <td>
                                                    <ul class="list-group list-group-flush">
                                                        @foreach (json_decode($s->product) as $p)
                                                            @php
                                                                $name = Sku::find($p->product)->name;
                                                            @endphp
                                                            <li class="list-group-item border-0">
                                                                <span class="bullet-point"></span> {{ $name }}
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                </td>
                                                <td>{{ $s->payment_method }}</td>
                                                <td>{{ $s->notes }}</td>
                                                <td>{{ \Carbon\Carbon::parse($s->finish_date)->format('d-m-Y') }}</td>
                                                <td>
                                                    <div class="d-flex flex-column align-items-start mb-3">
                                                        <button
                                                            class="btn btn-outline-success mb-2 w-75">History</button>
                                                        <a href="{{ route('sales.edit', $s->id) }}"
                                                            class="btn btn-outline-primary w-75">Edit</a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td class="text-center" colspan="7">No Data Sales</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
