<div>
    <div class="mt-2">
        <div class="row">
            <div class="col-lg-12 margin-tb">
                <div class="pull-left">
                    <a href="{{ route('work-order.index') }}" class="third-color-sne"> <i
                            class="fa-solid fa-chevron-left fa-xs"></i> Back</a>
                    <h2 class="primary-color-sne">Monitoring {{ $workOrder->number }}</h2>
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

                <div class="card p-4 primary-box-shadow mt-3">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="d-flex gap-2 align-items-center">
                                @if($workOrderStatus === 'PENDING')
                                    <button class="btn btn-success" @if(!$allItemsReady || $workOrderStatus !== 'PENDING') disabled @endif wire:click="startWorkOrder">Start Work Order</button>
                                @endif

                                @if($workOrderStatus === 'STARTED')
                                    <button class="btn btn-danger" wire:click="finishWorkOrder">Finish Work Order</button>
                                @endif

                                @if($workOrderStatus === 'FINISHED')
                                    <h1 class="badge badge-success">Work Order has been Finished</h1>
                                @endif
                                <a class="d-flex align-items-center btn btn-primary btn-sm gap-2" href="{{ route('print-work-order.index', $workOrder->id) }}" target="_blank"><i class="fas fa-download"></i>Download Work Order</a>
                            </div>

                            {{-- @foreach (['STARTED', 'FINISHED'] as $status)
                                @if($workOrderStatus === $status)
                                    <br>
                                    <small class="text-success mt-1">*Work Order has been {{ Str::lower($status) }}</small>
                                @endif
                            @endforeach --}}
                            @if(!$allItemsReady && $workOrderStatus === 'PENDING')
                                <br>
                                <small class="text-danger mt-1">*Raw Materials not ready</small>   
                            @endif

                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-5">
                    <div class="d-flex align-items-center">
                        <label for="sortBy" class="form-label me-2 mb-0">Sort By</label>
                        <select wire:model="sortBy" id="sortBy" class="form-select me-3" style="width: auto;">
                            <option value="item_name">Item Name</option>
                            <option value="price">Price</option>
                            <option value="qty">Quantity</option>
                        </select>
                    </div>

                    <div class="d-flex">
                        <div style="position: relative;">
                            <input
                                wire:model.debounce.500ms="search"
                                type="search"
                                class="form-control"
                                placeholder="Search"
                                style="padding-right: 30px; border-radius: 20px; border: 1px solid #ccc;"
                            />
                            <i
                                class="fa fa-search"
                                style="position: absolute; top: 50%; right: 10px; transform: translateY(-50%); color: blue;"
                            ></i>
                        </div>

                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <div style="overflow-x: scroll;" class="table-responsive-container">
                            <table id="boqTable" class="table mt-3 primary-box-shadow">
                                <thead class="thead-light">
                                <tr class="table-secondary">
                                    <th class="text-center align-middle border-top-left">SKU</th>
                                    <th class="text-center align-middle">Item Name</th>
                                    <th class="text-center align-middle">Unit</th>
                                    <th class="text-center align-middle">Quantity</th>
                                    <th class="text-center align-middle">Price Estimation*</th>
                                    <th class="text-center align-middle">Shipping Cost Estimation*</th>
                                    <th class="text-center align-middle">Total Estimation**</th>
                                    <th class="text-center align-middle border-top-right">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($productDetails as $detail)
                                    @forelse($detail['boqDetails'] as $boq)
                                        <tr>
                                            @if ($loop->first)
                                                <td rowspan="{{ $detail['boqDetails']->count() }}">{{ $detail['product_name'] }}</td>
                                            @endif
                                                <td><strong class="text-success">{{ $boq['item_name'] }}</strong></td>
                                                <td class="text-center align-middle">{{ $boq['unit'] }}</td>
                                                <td class="text-center align-middle">{{ $boq['qty'] }}
                                                    @php
                                                        $badgeClass = $boq['qty'] > $boq['available_stock'] ? 'badge-danger' : 'badge-success';
                                                    @endphp
                                                    <br>
                                                        <small class="badge {{ $badgeClass }}">Available Stock: {{ $boq['available_stock'] }}</small>
                                                </td>

                                                <td class="text-center align-middle">Rp. {{ number_format($boq['price'], 0) }}</td>
                                                <td class="text-center align-middle">Rp. {{ number_format($boq['shipping'], 0) }}</td>
                                                <td class="text-center align-middle">Rp. {{ number_format($boq['total'], 0) }}</td>
                                                <td></td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center">No BOQ Data Found</td>
                                        </tr>
                                    @endforelse
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">No SKU Data Found</td>
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
