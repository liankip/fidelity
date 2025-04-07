<div>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
        show card
    </button>
    <div class="modal" id="exampleModal" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Modal title</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="container mt-2">
                        <div class="row">
                            <div class="col-lg-12 margin-tb">
                                <div class="pull-left">
                                    <h2>{{ config('app.company', 'SNE') }} - ERP | Purchase Request List</h2>
                                </div>
                                <table class="table table-bordered">
                                    <tr>
                                        <td class="text-center">#</td>
                                        <td>Image</td>
                                        <td>Name of Item</td>
                                        <td>Unit</td>
                                        <td>Count</td>
                                    </tr>

                                    {{-- @dd($itemsarray) --}}
                                    @if (count($itemsarray))

                                        @foreach ($itemsarray as $key => $item)
                                            <tr>
                                                <td class="text-center">{{$key + 1}}</td>
                                                <td><img src="{{$item["image"]}}" width="200" alt=""></td>
                                                <td>{{ $item['name'] }}</td>:
                                                <td><input class="form-control" wire:model.defer="itemsarray.{{$key}}.count" type="number"></td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="2" class="text-center">belum ada item yg di tambahkan</td>
                                        </tr>
                                    @endif

                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>

</div>
