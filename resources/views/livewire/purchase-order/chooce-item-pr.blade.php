<div>
    <div class="mt-2 container">
        <div class="row">
            <div class="col-lg-12 margin-tb">
                <div class="pull-left">
                    <h2 class="primary-color-sne">Choice item will process to purchase order</h2>
                </div>
            </div>
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

        <div class="card primary-box-shadow mt-5">
            <div class="card-body">
                @if (auth()->user()->hasGeneralAccess())
                    <div class="d-flex my-2 justify-content-between">
                        {{--                    @if (count($prdetail) === 0) --}}
                        {{--                        <a class="btn btn-success" href="{{ route('itempr.index') }}">Tambah Barang</a> --}}
                        {{--                    @endif --}}

                        <button class="btn btn-success" wire:click='continue'>Continue</button>
                    </div>
                @endif
                <div>
                    <table class="table ">
                        <thead class="text-center thead-light">
                            <th class="text-center border-top-left" style="width: 5%"><input class="form-check-input"
                                    style="width: 20px; height: 20px" type="checkbox" wire:model='checkall'
                                    wire:click='allcheck'></th>
                            <th style="width: 5%">No</th>
                            <th style="width: 40%">Item Name</th>
                            <th style="width: 40%">PR Number</th>
                            <th style="width: 10%">Quantity</th>
                            <th style="width: 10%">Unit</th>
                            <th style="width: 10%" class="border-top-right">Notes</th>
                        </thead>

                        @forelse ($prdetail as $key => $val)
                            <tr>
                                <td class="text-center ">
                                    <input class="form-check-input" style="width: 20px; height: 20px" type="checkbox"
                                        wire:model='prdetail.{{ $key }}.checked'>
                                </td>
                                <td class="text-center">
                                    <input hidden type="text" name="item_id[]" class="form-control"
                                        placeholder="item_id" value="{{ $val->item_id }}">
                                    {{ $key + 1 }}
                                </td>

                                <td>
                                    {{ $val->item->name }}
                                </td>

                                <td>
                                    <a
                                        @if ($val->purchaseRequest->partof !== null && $val->purchaseRequest->partof !== 'capex') href="{{ route('task-monitoring.index', ['taskId' => $val->purchaseRequest->task->id]) }}" @endif>
                                        {{ $val->purchaseRequest->pr_no }}
                                    </a>
                                </td>

                                <input hidden type="text" name="type[]" class="form-control" placeholder="type"
                                    value="{{ $val->type }}">

                                <td class="text-center">
                                    @if ($prdetail[$key]->reduce_qty != null)
                                        {{ str_replace(',00', '', number_format($val->reduce_qty, 2, ',', '.')) }}
                                    @else
                                        {{ str_replace(',00', '', number_format($val->qty, 2, ',', '.')) }}
                                    @endif
                                </td>
                                <td>
                                    {{ $val->unit }}
                                </td>
                                <td>
                                    {{ $val->notes }}
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">No data available</td>
                            </tr>
                        @endforelse
                    </table>

                </div>
            </div>
        </div>

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css"
            integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w=="
            crossorigin="anonymous" referrerpolicy="no-referrer" />

    </div>

</div>
