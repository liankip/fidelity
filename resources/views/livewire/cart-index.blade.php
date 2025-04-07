@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            @if ($message = Session::get('success'))
                <div class="alert alert-success">
                <p>{{ $message }}</p>
                </div>
            @endif

            <div class="card col-md-8 mt-2">

                <div class="card-header">{{ __('Purchase Request List') }}</div>
                <div class="card-body">
                    {{-- List Cart --}}
                    <table class="w-full text-sm lg:text-base" cellspacing="0">
                        <thead>
                          <tr class="h-12 uppercase">
                            <th class="hidden md:table-cell"></th>
                            <th class="text-left">Name</th>
                            <th class="pl-5 text-left lg:text-right lg:pl-0">
                              <span class="lg:hidden" title="Quantity">Qtd</span>
                              <span class="hidden lg:inline">Quantity</span>
                            </th>
                            <th class="hidden text-right md:table-cell"> Type</th>
                            <th class="hidden text-right md:table-cell"> Action </th>
                          </tr>
                        </thead>
                        <tbody>
                            @foreach ($cartItems as $item)
                          <tr>
                            <td class="hidden pb-4 md:table-cell">
                              <a href="#">
                                <img src="{{ $item->attributes->image }}" class="w-20 rounded" alt="Thumbnail">
                              </a>
                            </td>
                            <td>
                              <a href="#">
                                <p class="mb-2 md:ml-4">{{ $item->name }}</p>

                              </a>
                            </td>
                            <td class="justify-center mt-6 md:justify-end md:flex">
                              <div class="h-10 w-28">
                                <div class="relative flex flex-row w-full h-8">

                                  <form action="{{ route('cart.update') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $item->id}}" >
                                  <input type="number" name="quantity" value="{{ $item->quantity }}"
                                  class="w-6 text-center bg-gray-300" />
                                  <button type="submit" class="px-2 pb-2 ml-2 text-white bg-blue-500">update</button>
                                  </form>
                                </div>
                              </div>
                            </td>
                            <td class="hidden text-right md:table-cell">
                              <span class="text-sm font-medium lg:text-base">
                                  ${{ $item->type }}
                              </span>
                            </td>
                            <td class="hidden text-right md:table-cell">
                              <form action="{{ route('cart.remove') }}" method="POST">
                                @csrf
                                <input type="hidden" value="{{ $item->id }}" name="id">
                                <button class="px-4 py-2 text-white bg-red-600">x</button>
                            </form>

                            </td>
                          </tr>
                          @endforeach

                        </tbody>
                      </table>
                </div>
                <div class="card-header">{{ __('Destination') }}

                </div>
                <div class="card-body">
                    {{-- Destination Body --}}
                    <div class="form-group">
                        <strong>Project ID:</strong>
                        <input type="text" name="project_id" class="form-control" placeholder="Project ID">
                        @error('project_id')
                        <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>Nama Project:</strong>
                            <input type="text" name="nama_project" class="form-control" placeholder="Nama Project">
                            @error('nama_project')
                            <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group">
                        <strong>Warehouse ID:</strong>
                        <input type="text" name="warehouse_id" class="form-control" placeholder="Warehouse ID">
                        @error('warehouse_id')
                        <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <strong>Nama Warehouse:</strong>
                        <input type="text" name="nama_warehouse" class="form-control" placeholder="Nama Warehouse">
                        @error('nama_warehouse')
                        <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <strong>Notes:</strong>
                        <input type="text" name="remark" class="form-control" placeholder="Notes">
                        @error('remark')
                        <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="card-footer">
                    {{-- <form action="{{ route('cart.clear') }}" method="POST">
                        @csrf
                        <button class="px-6 py-2 text-red-800 bg-red-300">Remove All Cart</button>
                    </form> --}}
                    <button class="btn btn-danger">Reset</button>
                    <button class="btn btn-success">Upload PR</button>
                </div>

            </div>
            {{-- <div class="card col-md-6 mt-2">

                <div class="card-header">{{ __('Destination') }}

                </div>
                <div class="card-body">
                    Destination Body
                </div>
                <div class="card-footer">
                    <button class="btn btn-danger">Reset Destination</button>
                </div>
            </div> --}}
        </div>
    </div>
</div>
@endsection
