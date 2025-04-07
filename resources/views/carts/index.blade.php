@extends('layouts.layer1')

@section('content')
    <div class="container mt-2">
        <div class="row">
            <div class="col-lg-12 margin-tb">
                <div class="pull-left">
                    <h2>{{ config('app.company', 'SNE') }} - ERP | Purchase Request List</h2>
                </div>
                <div class="pull-right mb-2">
                    {{-- <a class="btn btn-success" href="{{ route('itempr.index') }}"> Add New Item</a> --}}
                </div>
            </div>
        </div>
        @if ($message = Session::get('success'))
            <div class="alert alert-success">
                <p>{{ $message }}</p>
            </div>
        @endif
        <form action="{{ route('cart.saveAllCart') }}" method="POST">
            @csrf
            <div class="card">
                <div class="card-header">Purchase Request List | <a class="btn btn-success"
                        href="{{ route('itempr.index') }}"> Tambah Item </a>
                </div>
                <div class="card-body">
                    {{-- @if ($items->count() == 0)
                    <td colspan="5" class="text-center">
                        Your Document is Empty
                    </td>
                @else

                @endif --}}
                    @if ($items->count() >= 0)
                    <br>
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>PR No:</strong>
                                {{-- <input type="text" name="pr_id" class="form-control" placeholder="pr id"> --}}
                                <select name="pr_id" id="pr_id"
                                class="js-example-basic-single form-control">  @error('purchaserequest') is-invalid @enderror
                                    {{-- <option value="">Pilih PR</option> --}}
                                    @foreach ($purchaserequest as $purchaserequest)
                                        <option value="{{ $purchaserequest->id }}">{{ $purchaserequest->pr_no }}</option>
                                    @endforeach
                                </select>
                                @error('pr_id')
                                    <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <br>
                        Your Document is Empty
                    @endif
                    @if ($items->count() > 0)

                        <table class="table table-bordered">
                            <tr>
                                <th>ID</th>
                                <th>Image</th>
                                <th>Item Code</th>
                                <th>Item Name</th>
                                <th>Qty</th>
                                <th>Type</th>
                                <th>Unit</th>
                                <th width="280px">Action</th>
                            </tr>
                            {{-- dd($request->items); --}}
                            @forelse ($items as $item)
                                <tr>
                                    <td>{{ $item->id }}</td>

                                    <td><a href="#">
                                            <img src="{{ $item->attributes->image }}" class="w-20 rounded" alt="Thumbnail"
                                                width="225 px">
                                        </a></td>
                                    <td>{{ $item->attributes->item_code }}</td>
                                    <td>{{ $item->name }}</td>
                                    <td>
                                        <div class="h-10 w-28">
                                            <div class="relative flex flex-row w-full h-8">

                                                <form action="{{ route('cart.update') }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="id" value="{{ $item->id }}">
                                                    <input type="number" name="quantity" value="{{ $item->quantity }}"
                                                        class="w-6 text-center bg-gray-300" />
                                                    <button type="submit" class="px-2 pb-2 ml-2 btn-success">+</button>
                                                </form>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $item->attributes->type }}</td>
                                    <td>{{ $item->attributes->unit }}</td>
                                    <td>
                                        <form action="{{ route('cart.remove') }}" method="POST">
                                            @csrf
                                            <input type="hidden" value="{{ $item->id }}" name="id">
                                            <button class="btn btn-danger">Remove</button>
                                        </form>
                                    </td>


                                </tr>
                            @empty
                                Your Document is Empty
                            @endforelse
                        </table>
                    @endif
                    @if ($items->count() > 0)
                        @csrf

                        <form action="{{ route('cart.clear') }}" method="POST">
                            @csrf
                            <button class="btn btn-danger">Reset PR</button>
                        </form>
                    @endif


                </div>
                <div class="card-footer">
                    @if ($items->count() == 0)
                        <form action="{{ route('cart.clear') }}" method="POST">
                            @csrf
                            <button disabled class="btn grey">Reset PR</button>
                        </form>
                    @endif
                    @if ($items->count() > 0)
                        @csrf
                        <button type="submit" class="btn btn-success">Ajukan Dokumen</button>
                    @endif




                </div>
            </div>
            <br>
            {{-- <div class="card">
                <div class="card-header">Destination</div>
                <div class="card-body">
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>PR No:</strong>
                            <input readonly type="text" name="pr_no" class="form-control" placeholder="pr_no"
                                value="@foreach ($idxs as $idxs){{++$idxs->idx}}@endforeach/PR/{{ env('NO_PREFIX') }}/{{ $returnValueRoman }}/{{ $year }}">

                            @error('pr_no')
                                <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>
                                <label for="pr_type">PR Type :</label>
                            </strong>

                            <select id="pr_type" name="pr_type" class="form-control" placeholder="PR Type">
                                <option value="">Pilih Type PR</option>
                                <option value="Barang">Barang</option>
                                <option value="Jasa">Jasa</option>
                                <option value="Sewa Mesin">Sewa Mesin</option>

                            </select>

                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>
                                <label for="pr_type">Project :</label>
                            </strong>

                            <select name="project_id" id="project_id"
                                class="form-control  @error('project') is-invalid @enderror">
                                <option value="">Pilih Project</option>
                                @foreach ($projects as $project)
                                    <option value="{{ $project->id }}">{{ $project->name }}</option>
                                @endforeach
                            </select>


                        </div>
                    </div>



                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>
                                <label for="warehouse_id">Warehouse :</label>
                            </strong>

                            <select name="warehouse_id" id="warehouse_id"
                                class="form-control  @error('warehouse') is-invalid @enderror">
                                <option value="">Pilih Warehouse</option>
                                @foreach ($warehouses as $warehouse)
                                    <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                                @endforeach
                            </select>



                        </div>
                    </div>



                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>Notes:</strong>
                            <textarea id="notes" name="notes" rows="4" cols="50" name="notes" class="form-control"
                                placeholder="Notes">

                        </textarea>

                        </div>
                    </div>

                    {
                </div>
                <div class="card-footer">
                    @if ($items->count() == 0)
                        <a href="{{ url('#') }}">
                            <button disabled type="submit" class="btn grey">Ajukan Dokumen</button>
                        </a>
                    @endif

                    @if ($items->count() > 0)

                        @csrf
                        <button type="submit" class="btn btn-success">Ajukan Dokumen</button>

                    @endif
                </div>


            </div> --}}
        </form>
    </div>
    {{-- </form> --}}

    {{-- {!! $items->links() !!} --}}
@endsection
