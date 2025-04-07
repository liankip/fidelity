@extends('layouts.app')


@section('content')
    <div class="container mt-2">
        <div class="row">
            <div class="col-lg-12 margin-tb">
                <div class="pull-left mb-2">
                    <h2>Add BOQ Item</h2>
                    <h4 class="text-secondary"><strong>{{ $project->name }}</strong></h4>
                    <hr class="">
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

        <div class="mt-5">
            <div class="d-flex justify-content-between ">
                <a class="btn btn-success mb-3" href="{{ route('boq.upload', $project->id) }}">
                    <i class="fas fa-upload"></i>
                    Upload Excel
                </a>
            </div>

            <form action="{{ route('boq.store', $project->id) }}" method="POST" enctype="multipart/form-data">
                @method('patch')
                @csrf
                <div class="card">
                    <div class="card-body my-4">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <strong>Item<span class="text-danger">*</span></strong>
                                    <select name="item_id" id="item_id"
                                        class="js-example-basic-single form-control @error('item_id') is-invalid @enderror">
                                        <option value="" hidden>Pilih Item</option>
                                    </select>
                                    @error('item_id')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-xs-12 col-sm-12 col-md-12 mt-4">
                                <div class="form-group">
                                    <strong>Unit<span class="text-danger">*</span></strong>
                                    <select name="unit_id" id="unit_id"
                                        class="js-example-basic-single form-control @error('unit_id') is-invalid @enderror">
                                        <option value="" hidden>Pilih Unit</option>
                                        @if ($errors->any())
                                            @foreach (App\Models\ItemUnit::where('item_id', old('item_id'))->get() as $unit)
                                                <option value="{{ $unit->unit_id }}"
                                                    {{ old('unit_id') == $unit->unit_id ? 'selected' : '' }}>
                                                    {{ $unit->unit->name }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>

                                    @error('unit_id')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-xs-12 col-sm-12 col-md-12 mt-4">
                                <div class="form-group">
                                    <strong>Quantity<span class="text-danger">*</span></strong>
                                    <input type="number" name="qty"
                                        class="form-control @error('qty') is-invalid @enderror" placeholder="Quantity"
                                        value="{{ old('qty') ? old('qty') : 1 }}">
                                    @error('qty')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-xs-12 col-sm-12 col-md-12 mt-4">
                                <div class="form-group">
                                    <strong>Harga<span class="text-danger">*</span></strong>
                                    <input type="number" name="price_estimation"
                                        class="form-control @error('price_estimation') is-invalid @enderror"
                                        placeholder="Price"
                                        value="{{ old('price_estimation') ? old('price_estimation') : 0 }}" required>
                                    @error('price_estimation')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                    <em class="text-secondary">Ini adalah harga untuk 1 item</em>

                                </div>
                            </div>

                            <div class="col-xs-12 col-sm-12 col-md-12 mt-4">
                                <div class="form-group">
                                    <strong>Ongkos Kirim</strong>
                                    <input type="number" name="shipping_cost"
                                        class="form-control @error('shipping_cost') is-invalid @enderror"
                                        placeholder="Shipping Cost"
                                        value="{{ old('shipping_cost') ? old('shipping_cost') : 0 }}">
                                    @error('shipping_cost')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12 mt-4">
                                <div class="form-group">
                                    <strong>Note</strong>
                                    <textarea name="note" class="form-control @error('note') is-invalid @enderror" placeholder="Note">{{ old('note') ? old('note') : '' }}</textarea>
                                    @error('note')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary ml-3">Submit</button>
                        <a class="btn btn-danger" href="{{ route('boq.index', $project->id) }}">Back</a>
                    </div>
                </div>
            </form>

            <script>
                $(document).ready(function() {
                    $("#itemselected").select2();

                    $('#item_id').select2({
                        theme: 'bootstrap-5',
                        "language": {
                            "noResults": function() {
                                return "No Results Found <a href='/items/create' class='btn btn-success' target='_blank'>Tambah item</a>";
                            }
                        },
                        escapeMarkup: function(markup) {
                            return markup;
                        },
                        ajax: {
                            url: '/api/getitemsselect2/{{ $project->id }}',
                            dataType: 'json',
                            delay: 250,
                            processResults: function(data) {
                                return {
                                    results: $.map(data, function(item) {
                                        return {
                                            id: item.id,
                                            text: item.name,
                                            price: item.item_price.length > 0 ? item.item_price[0]
                                                .price : "-",
                                        };
                                    })
                                };
                            },
                            cache: true
                        },
                        templateResult: function(data) {
                            let $result = $("<span></span>");

                            $result.text(data.text);
                            $result.append("<br><small class='text-secondary'>Harga: " + data.price +
                                "</small>");

                            return $result;
                        },
                    });

                    $('#unit_id').select2({
                        theme: 'bootstrap-5'
                    });

                    $('#item_id').on('select2:select', function(e) {
                        const data = e.params.data;

                        $('input[name="price_estimation"]').val(data.price);
                    });

                    $('#item_id').on('change', function() {
                        console.log($(this).dataset);
                        var item_id = $(this).val();
                        if (item_id) {
                            $.ajax({
                                url: '/get-units',
                                type: 'GET',
                                data: {
                                    item_id: item_id
                                },
                                dataType: 'json',
                                success: function(data) {
                                    $('#unit_id').empty();
                                    $('#unit_id').append('<option value="">Pilih Unit</option>');
                                    $.each(data, function(key, value) {
                                        $('#unit_id').append('<option value="' + value.unit_id +
                                            '">' + value.unit.name + '</option>');
                                    });
                                }
                            });
                        } else {
                            $('#unit_id').empty();
                            $('#unit_id').append('<option value="">Pilih Unit</option>');
                        }
                    });
                });
            </script>
        </div>

    @endsection
