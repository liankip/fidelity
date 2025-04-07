@extends('layouts.app')

@section('content')
    <div class="container mt-2 mb-5">
        <div class="row">
            <div class="col-lg-12 margin-tb">
                <div class="pull-left mb-2">
                    <h2>Edit BOQ Item</h2>
                    <h4 class="text-secondary"><strong>{{ $project->name }}</strong></h4>
                    <hr class="pb-5">
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

        <form action="{{ route('boq.update', [$item->id, $item->project_id]) }}" method="POST"
            enctype="multipart/form-data">
            @method('patch')
            @csrf
            <div class="card">
                <div class="card-body my-4">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="row">
                                <div class="col-md-4">
                                    <strong class="col-form-label">Item</strong>
                                </div>
                                <div class="col-md-8">
                                    <input type="text" class="form-control" placeholder="Item"
                                        value="{{ $item->item->name }}" disabled readonly>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12 mt-4">
                            <div class="row">
                                <div class="col-md-4">
                                    <strong class="col-form-label">Unit</strong>
                                </div>
                                <div class="col-md-8">
                                    @if ($itemInPR > 0)
                                        <input type="text" class="form-control" placeholder="Unit"
                                            value="{{ $item->unit->name }}" disabled readonly>
                                    @else
                                        <select name="unit_id" id="unit_id"
                                            class="form-select @error('unit_id') is-invalid @enderror">
                                            <option value="" hidden>Pilih Unit</option>

                                            @foreach ($item->item->item_unit as $unit)
                                                <option value="{{ $unit->unit_id }}"
                                                    {{ old('unit_id') == $unit->unit_id ? 'selected' : ($unit->unit_id == $item->unit_id ? 'selected' : '') }}>
                                                    {{ $unit->unit->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <div class="mt-2">
                                            <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal"
                                                    data-bs-target="#insert_unit">
                                                Add Unit
                                            </button>
                                        </div>
                                    @endif
                                    @if(session('exists'))
                                        <small class="text-danger">
                                            {{ session('exists') }}
                                        </small>
                                    @endif

                                    {{-- @error('unit_id')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror --}}

                                    {{-- <input type="text" class="form-control" placeholder="Unit"
                                        value="{{ $item->unit->name }}" disabled readonly> --}}
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12 mt-4">
                            <div class="row">
                                <div class="col-md-4">
                                    <strong class="col-form-label">Quantity<span class="text-danger">*</span></strong>
                                    <div>
                                        <em class="text-secondary">Wajib diisi</em>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div x-data="{ showMinInput: false, showPlusInput: false }">
                                        <div>
                                            <p>
                                                Jumlah barang saat ini : <strong>{{ (int) $item->qty }}</strong>
                                            </p>
                                            <div class="d-flex" class="mt-2">
                                                @if ($item->qty > $itemInPR)
                                                    <button type="button" class="btn btn-sm btn-danger mr-2" id="btn-min"
                                                        @click="showMinInput = true; showPlusInput = false;">
                                                        Kurangi Barang
                                                    </button>
                                                @endif

                                                <button type="button" class="btn btn-sm btn-primary" id="btn-plus"
                                                    @click="showPlusInput = true; showMinInput = false;">
                                                    Tambah Barang
                                                </button>
                                            </div>
                                        </div>

                                        <div x-show="showMinInput" class="mt-3">
                                            @php
                                                $max = $item->qty - $itemInPR;

                                                if ($itemInPR === 0) {
                                                    $max = $max - 1;
                                                }
                                            @endphp

                                            <x-common.input name="qty_min" type="number"
                                                label="Jumlah yang ingin dikurangi" min="0"
                                                max="{{ $max }}"
                                                placeholder="Masukkan jumlah barang yang akan dikurangi" />
                                            <div id="" class="form-text mb-3">
                                                Jumlah barang yang dapat dikurangi adalah
                                                <strong>{{ $max }}</strong>
                                            </div>
                                        </div>

                                        <div x-show="showPlusInput" class="mt-3">
                                            <x-common.input name="qty_plus" type="number" min="0"
                                                label="Jumlah yang ingin ditambah"
                                                placeholder="Masukkan jumlah barang yang akan ditambahkan" />
                                        </div>
                                    </div>

                                    @error('qty')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror

                                    <div class="alert alert-primary mt-2">
                                        <div>
                                            <strong>Note:</strong>
                                        </div>
                                        <div>
                                            Jumlah barang yang sudah dimasukkan kedalam PR adalah:
                                            <strong>{{ $itemInPR }} {{ $item->unit->name }}</strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-12 mt-4">
                        <div class="row">
                            <div class="col-md-4">
                                <strong class="col-form-label">Harga</strong>
                                <div>
                                    <em class="text-secondary">Wajib diisi<span class="text-danger">*</span></em>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <input type="number" name="price_estimation"
                                    value="{{ old('price_estimation') ? old('price_estimation') : number_format($item->price_estimation, 0, ',', '') }}"
                                    class="form-control @error('price_estimation') is-invalid @enderror"
                                    placeholder="Harga" />
                                @error('price_estimation')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                                <em class="text-secondary">Ini adalah Harga satuan</em>
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-12 mt-4">
                        <div class="row">
                            <div class="col-md-4">
                                <strong class="col-form-label">Ongkos Kirim</strong>
                                <div>
                                    <em class="text-secondary">Optional</em>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <input type="number" name="shipping_cost"
                                    value="{{ old('shipping_cost') ? old('shipping_cost') : number_format($item->shipping_cost, 0, ',', '') }}"
                                    class="form-control @error('shipping_cost') is-invalid @enderror"
                                    placeholder="Shipping Cost" />
                                @error('shipping_cost')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                                <em class="text-secondary">Ini adalah ongkos kirim</em>
                            </div>
                        </div>
                    </div>

                    {{--                        <div class="col-xs-12 col-sm-12 col-md-12 mt-4"> --}}
                    {{--                            <div class="row"> --}}
                    {{--                                <div class="col-md-4"> --}}
                    {{--                                    <strong class="col-form-label">Kota Asal Barang</strong> --}}
                    {{--                                    <div> --}}
                    {{--                                        <em class="text-secondary">Optional</em> --}}
                    {{--                                    </div> --}}
                    {{--                                </div> --}}
                    {{--                                <div class="col-md-8"> --}}
                    {{--                                    <input type="text" name="origin" value="{{ old('origin') ? old('origin') : $item->origin }}" class="form-control @error('origin') is-invalid @enderror" placeholder="Origin" /> --}}
                    {{--                                    @error('origin') --}}
                    {{--                                        <div class="text-danger">{{ $message }}</div> --}}
                    {{--                                    @enderror --}}
                    {{--                                </div> --}}
                    {{--                            </div> --}}
                    {{--                        </div> --}}

                    {{--                        <div class="col-xs-12 col-sm-12 col-md-12 mt-4"> --}}
                    {{--                            <div class="row"> --}}
                    {{--                                <div class="col-md-4"> --}}
                    {{--                                    <strong class="col-form-label">Kota Tujuan Barang</strong> --}}
                    {{--                                    <div> --}}
                    {{--                                        <em class="text-secondary">Optional</em> --}}
                    {{--                                    </div> --}}
                    {{--                                </div> --}}
                    {{--                                <div class="col-md-8"> --}}
                    {{--                                    <input type="text" name="destination" value="{{ old('destination') ? old('destination') : $item->destination }}" class="form-control @error('destination') is-invalid @enderror" placeholder="Destination" /> --}}
                    {{--                                    @error('destination') --}}
                    {{--                                        <div class="text-danger">{{ $message }}</div> --}}
                    {{--                                    @enderror --}}
                    {{--                                </div> --}}
                    {{--                            </div> --}}
                    {{--                        </div> --}}

                    <div class="col-xs-12 col-sm-12 col-md-12 mt-4">
                        <div class="row">
                            <div class="col-md-4">
                                <strong class="col-form-label">Note</strong>
                                <div>
                                    <em class="text-secondary">Optional</em>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <textarea name="note" class="form-control @error('note') is-invalid @enderror" placeholder="Note">{{ old('note') ? old('note') : $item->note }}</textarea>
                                <input type="hidden" name="direct_edit" value="{{ request()->get('direct-edit') }}">
                                @error('note')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary ml-3">Save</button>
                <a class="btn btn-danger" href="{{ route('boq.index', $project->id) }}">Back</a>
            </div>
    </div>
    </form>

    <div class="modal fade" id="insert_unit" tabindex="-1" aria-labelledby="insert_unit_label" aria-hidden="true">
        <div class="modal-dialog">
            <form id="insert_unit_form" action="{{ route('item-unit') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PATCH')
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="insert_unit_label">Add Unit</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body my-3">
                        <div class="col-xs-12 col-sm-12 col-md-12 mt-4">
                            <div class="form-group">
                                <strong>Unit<span class="text-danger">*</span></strong>
                                <select name="insert_unit_id"
                                    class="js-example-basic-single form-select
                                    @error('unit')
                                        is-invalid
                                    @enderror">
                                    <option value="" hidden>Pilih Unit</option>
                                    @foreach ($units as $unit)
                                        <option value="{{ $unit->id }}" {{ old('insert_unit_id') == $unit->id ? 'selected' : '' }}>
                                            {{ $unit->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('insert_unit_id')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <input type="hidden" name="item_id" value="{{ $item->item_id }}">

                            <div class="col-xs-12 col-sm-12 col-md-12 mt-4">
                                <div class="form-group">
                                    <strong>Conversion Rate<span class="text-danger">*</span></strong>
                                    <input type="text" name="conversion_rate"
                                        class="form-control"
                                        placeholder="Conversion Rate">
                                </div>
                                @error('conversion_rate')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Save</button>
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
