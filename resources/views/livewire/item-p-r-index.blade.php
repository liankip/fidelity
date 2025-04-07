<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <input type="text" wire:model="searchTerm" placeholder="Search Something ..." class="form-control mb-3"/>
        </div>
        <div class="col-7">
            <div class="row">
                @foreach ($items as $item)
                    <div class="col-sm-4 mt-2">
                        <div class="card">
                            <div class="card-header">{{ $item->item_code }} | {{ $item->name }}</div>

                            <div class="card-body">
                                <img src={{ url($item->image) }} alt="" width="190 px">
                                <br>
                                {{ $item->type }}
                                <br>
                                {{ $item->unit }}
                            </div>
                            <div class="card-footer d-flex">
                                <button wire:click="additem({{ $item->id }})" class="btn btn-success"
                                        style="font-size: 12px">Add to
                                    PR
                                </button>
                                @foreach ($itemsarray as $key => $value)
                                    @if ($item->id == $value['id'])
                                        <div class="ms-2 d-flex">
                                            <button class="btn bi" style="padding-top: 1px; padding-bottom: 1px">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                     role="button" wire:click="subtractitem({{ $value['id'] }})"
                                                     fill="currentColor" class="bi bi-dash-circle" viewBox="0 0 16 16">
                                                    <path
                                                        d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                                                    <path
                                                        d="M4 8a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7A.5.5 0 0 1 4 8z"/>
                                                </svg>
                                            </button>

                                            <div class=" align-middle">
                                                <span class="mt-5">
                                                    {{ $value['count'] }}
                                                </span>
                                            </div>
                                            <button class="btn bi" style="padding-top: 1px; padding-bottom: 1px">

                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                     role="button" wire:click="additem({{ $item->id }})"
                                                     role="button" fill="currentColor" class="bi bi-plus-circle"
                                                     viewBox="0 0 16 16">
                                                    <path
                                                        d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                                                    <path
                                                        d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                                                </svg>
                                            </button>

                                        </div>
                                    @endif
                                @endforeach

                            </div>
                        </div>
                    </div>
                @endforeach
                <div class="mt-4">
                    {{ $items->links() }}
                </div>
            </div>
        </div>
        <div class="col-5">
            <div class="row">
                <div class="col-lg-12 margin-tb">
                    <div class="pull-left">
                        <h2>{{ config('app.company', 'SNE') }} - ERP | Purchase Request List</h2>
                    </div>
                    <table class="table table-bordered">

                        <tr>
                            <td class="text-center" style="font-size: 12px">#</td>
                            <td style="font-size: 12px">Image</td>
                            <td style="font-size: 12px">Name</td>
                            <td style="font-size: 12px">Unit</td>
                            <td style="font-size: 12px">Qty</td>
                            <td style="font-size: 12px">Note</td>
                            <td style="font-size: 12px">Action</td>
                        </tr>
                        @if (count($itemsarray))
                            @foreach ($itemsarray as $key => $item)
                                <tr>
                                    <td style="font-size: 12px" class="text-center">{{ $key + 1 }}</td>
                                    <td><img src="{{ $item['image'] }}" width="40" alt=""></td>
                                    <td style="font-size: 12px">{{ $item['name'] }}</td>
                                    <td style="font-size: 12px">{{ $item['unit'] }}</td>
                                    <td>
                                        <input class="form-control" style="font-size: 12px"
                                               wire:model="itemsarray.{{ $key }}.count" wire:blur="updateqty({{$key}})"
                                               type="number" min="1">
                                    </td>
                                    <td style="min-width: 150px">
                                        <textarea style="font-size: 12px"
                                                  class="form-control"
                                                  wire:model="itemsarray.{{ $key }}.note"
                                                  wire:blur="updatenote({{$key}})" cols="8" rows="2"></textarea>
                                    </td>
                                    <td style="font-size: 12px">
                                        <button class="btn btn-danger" wire:click="removeitem({{$key}})">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                 fill="currentColor" class="bi bi-x-lg" viewBox="0 0 16 16">
                                                <path
                                                    d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8 2.146 2.854Z"/>
                                            </svg>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="7" class="text-center">No previously added items</td>
                            </tr>
                        @endif
                    </table>
                    <a class="btn btn-success" href="{{url('purchase_requests')}}"> Selesai </a>
                </div>
            </div>
        </div>
    </div>
</div>
