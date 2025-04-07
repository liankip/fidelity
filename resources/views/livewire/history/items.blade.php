<div class="mt-2">
    <div class="row">
        <div class="col-lg-12 margin-tb">

            {{-- <div class="pull-right mb-2">
                    <a class="btn btn-success" href="{{ route('payments.create') }}"> Create payment</a>
                </div> --}}
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
    <a href="{{ route('items.index') }}" class="third-color-sne"> <i class="fa-solid fa-chevron-left fa-xs"></i> Back</a>
    <h2 class="primary-color-sne">Items
        @if ($itemdata)
            <b >{{ $itemdata->name }}</b>
        @endif
    </h2>

    <div class="mt-5">
        <button class="btn btn-success" wire:click="export">Export</button>
        <div class="card primary-box-shadow mt-2">
            <div class="card-body">
                <div class="input-group mb-3">
                    <input type="text" class="form-control" wire:model="search" name="search" placeholder="Search"
                        value="" aria-label="Recipient's username" aria-describedby="button-addon2">
                </div>
                <table class="table primary-box-shadow">
                    <thead class="thead-light">
                        <th class="text-center border-top-left">No</th>
                        <th class="text-center">PO No</th>
                        <th class="text-center">PR No</th>
                        <th class="text-center">Project</th>
                        <th class="text-center">Supplier</th>
                        <th class="text-center">Qty</th>
                        <th class="text-center">Price</th>
                        <th class="text-center">Total</th>
                        <th class="text-center border-top-right">Date</th>
                        {{-- <th width="280px">Action</th> --}}
                    </thead>
                    @foreach ($datas as $key => $value)
                        <tr onmouseover="this.style.backgroundColor='#F4F6F6'"
                            onmouseout="this.style.backgroundColor='white'">
                            <td class="text-center">{{ $key + 1 }}</td>
                            <td>
                                <b>
                                    <a href="{{ route('po-detail', ['id' => $value->id]) }}"
                                        class="text-black">{{ $value->po_no }}</a>
                                </b>
                            </td>
                            <td><b>{{ $value->pr_no }}</b></td>
                            <td><a class="text-black" target="_blank"
                                    href="{{ route('history.project', ['id' => $value->project_id]) }}">{{ $value->project->name }}</a>
                            </td>
                            <td><a class="text-black"
                                    href="{{ route('history.supplier', ['id' => $value->supplier_id]) }}">{{ $value->supplier->name }}</a>
                            </td>

                            @foreach ($value->podetail as $ss)
                                @if ($ss->item_id == $itemdata->id)
                                    <td class="text-center">
                                        {{ str_replace(',00', '', number_format($ss->qty, 2, ',', '.')) }}
                                    </td>
                                    <td>
                                        <div class="d-flex justify-content-between">
                                            <div>Rp.</div>
                                            <div>{{ str_replace(',00', '', number_format($ss->price, 2, ',', '.')) }}
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex justify-content-between">
                                            <div>Rp.</div>
                                            <div>
                                                {{ str_replace(',00', '', number_format($ss->price * $ss->qty, 2, ',', '.')) }}
                                            </div>
                                        </div>
                                    </td>
                                    @php
                                        break;
                                    @endphp
                                @endif
                            @endforeach
                            <td class="text-end">
                                @if ($value->date_approved)
                                    {{ date('d-m-Y', strtotime($value->date_approved)) }}
                                @else
                                    {{ date('d-m-Y', strtotime($value->created_at)) }}
                                @endif
                            </td>

                        </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </div>
</div>
