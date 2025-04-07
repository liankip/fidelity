@extends('layouts.app')

@section('content')
    <div class="container mt-2">
        <div class="row">
            <div class="col-lg-12 margin-tb">
                <div class="pull-left mb-2">
                    <a href="{{ route('prices.index') }}" class="third-color-sne"> <i
                        class="fa-solid fa-chevron-left fa-xs"></i> Back</a>
                    <h2 class="primary-color-sne">Add New Price</h2>
                </div>
            </div>
        </div>

        @foreach (['danger', 'warning', 'success', 'info'] as $key)
            @if(Session::has($key))
                <div class="alert alert-{{ $key }} alert-dismissible fade show mb-1 mt-1" role="alert">
                    {{ Session::get($key) }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                    </button>
                </div>
            @endif
        @endforeach

        <form action="{{ route('prices.store') }}" class="mt-5" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="card primary-box-shadow">
                <div class="card-body my-4">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Supplier<span class="text-danger">*</span></strong>
                                <select name="supplier_id" id="supplier_id" class="js-example-basic-single form-control @error('supplier_id') is-invalid @enderror">
                                    <option value="">Pilih Supplier</option>
                                    @foreach ($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                            {{ $supplier->name }} | {{ $supplier->term_of_payment }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('supplier_id')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-12 mt-4">
                            <div class="form-group">
                                <strong>Item<span class="text-danger">*</span></strong>
                                @if ($item)
                                    <input type="text" hidden name="item_id" value="{{ $item->id }}">
                                    <input class="form-control" type="text" value="{{ $item->name }}"
                                        aria-label="readonly input example" readonly>
                                @else
                                    <select name="item_id" id="item_id" class="js-example-basic-single form-control">
                                        <option value="" hidden>Pilih Item</option>
                                        @foreach ($items as $item)
                                            <option value="{{ $item->id }}" {{ old('item_id') == $item->id ? 'selected' : '' }}>
                                                {{ $item->name }} | {{ $item->type }}
                                            </option>
                                        @endforeach
                                    </select>
                                @endif

                                @error('item_id')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-12 mt-4">
                            <div class="form-group">
                                <strong>Unit<span class="text-danger">*</span></strong>
                                <select name="unit_id" id="unit_id" class="js-example-basic-single form-control">
                                    <option value="" hidden>Pilih Unit</option>
                                    @if($errors->any())
                                        @foreach(App\Models\ItemUnit::where('item_id', old('item_id'))->get() as $unit)
                                            <option value="{{ $unit->unit_id }}" {{ old('unit_id') == $unit->unit_id ? 'selected' : '' }}>
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
                                <strong>Price<span class="text-danger">*</span></strong>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="text" id="priceold" name="priceold" class="form-control" placeholder="Price in Rp">
                                    <span class="input-group-text">.00</span>
                                </div>
                                @error('price')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-12 mt-4">
                            <strong>Tax<span class="text-danger">*</span></strong>
                            <select class="form-select" id="choise" name=choise aria-label="Default select example">
                                <option value="1">Include Tax</option>
                                <option value="2">Exclude Tax</option>
                                <option value="3">Non PPN</option>
                            </select>
                            @error('choise')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-12 mt-4">
                            <div class="form-group">
                                <strong>Price Result<span class="text-danger">*</strong>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="text" id="priceshow" readonly name="price" class="form-control"
                                        placeholder="Price in Rp">
                                    <span class="input-group-text">.00</span>
                                </div>
                                {{-- @error('price')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror --}}
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-12 mt-4">
                            <div class="px-3 bg-secondary p-3 rounded">
                                <div class="text-warning">Silahkan abaikan form ini jika price tidak terpengaruh oleh kurs
                                </div>
                                <strong class="text-white">Kurs rupiah terhadap dolar</strong>
                                <input type="number" class="form-control" name="kurs" placeholder="ex: 15609 (Abaikan jika price tidak berkaitan dengan kurs)" id="">
                            </div>
                        </div>

                        <input style="display: none" type="number" id="price" readonly name="price"
                            class="form-control" placeholder="Price in Rp">

                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <input type="hidden" name="created_by" value="{{ Auth::id() }}" class="form-control"
                                    placeholder="created by" readonly="readonly">
                                @error('created_by')
                                    <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary ml-3"><i class="fa-solid fa-floppy-disk pe-2"></i>Save</button>
                </div>
            </div>
        </form>
        <script>
            $(document).ready(function() {
                $("#itemselected").select2();

                $('#supplier_id').select2({
                    theme: 'bootstrap-5',
                    "language": {
                        "noResults": function() {
                            return "No Results Found <a href='/suppliers/create' class='btn btn-success' target='_blank'>Tambah Supplier</a>";
                        }
                    },
                    escapeMarkup: function(markup) {
                        return markup;
                    }
                });

                $('#item_id').select2({
                    theme: 'bootstrap-5'
                });

                $('#unit_id').select2({
                    theme: 'bootstrap-5'
                });

                var rupiah = document.getElementById("priceold");
                var rupiah2 = document.getElementById("priceshow");
                rupiah.addEventListener("keyup", function(e) {
                    // tambahkan 'Rp.' pada saat form di ketik
                    // gunakan fungsi formatRupiah() untuk mengubah angka yang di ketik menjadi format angka

                    rupiah.value = formatRupiah(this.value);
                });

                /* Fungsi formatRupiah */
                function formatRupiah(angka, prefix) {
                    let number_string = angka.replace(/[^,\d]/g, "").toString(),
                        split = number_string.split(","),
                        sisa = split[0].length % 3,
                        rupiah = split[0].substr(0, sisa),
                        ribuan = split[0].substr(sisa).match(/\d{3}/gi);

                    // tambahkan titik jika yang di input sudah menjadi angka ribuan
                    if (ribuan) {
                        separator = sisa ? "." : "";
                        rupiah += separator + ribuan.join(".");
                    }

                    rupiah = split[1] != undefined ? rupiah + "," + split[1] : rupiah;
                    return prefix == undefined ? rupiah : rupiah ? "Rp. " + rupiah : "";
                }

                function formatRupiahshow(angka, prefix) {
                    // console.log(angka);
                    let number_string = angka.replace(/[^,\d]/g, "").toString(),
                        split = number_string.split(","),
                        sisa = split[0].length % 3,
                        rupiah2 = split[0].substr(0, sisa),
                        ribuan1 = split[0].substr(sisa).match(/\d{3}/gi);

                    // tambahkan titik jika yang di input sudah menjadi angka ribuan
                    if (ribuan1) {
                        separator = sisa ? "." : "";
                        rupiah2 += separator + ribuan1.join(".");
                    }

                    rupiah2 = split[1] != undefined ? rupiah2 + "," + split[1] : rupiah2;
                    return prefix == undefined ? rupiah2 : rupiah ? "Rp. " + rupiah2 : "";
                }


                function updateTextView(_obj) {
                    var num = getNumber(_obj.val());
                    if (num == 0) {
                        _obj.val('');
                    } else {
                        _obj.val(num.toLocaleString());
                    }
                }

                function getNumber(_str) {
                    var arr = _str.split('');
                    var out = new Array();
                    for (var cnt = 0; cnt < arr.length; cnt++) {
                        if (isNaN(arr[cnt]) == false) {
                            out.push(arr[cnt]);
                        }
                    }
                    return Number(out.join(''));
                }


                $('#priceold').keyup(function() {
                    if ($("#choise").val() == 1) {
                        let priceold = $('#priceold').val().replaceAll('.', '');
                        let price = priceold - (priceold / 111 * 11);
                        let newprice = Math.round(price).toString();
                        // console.log(newprice);
                        let pricestr = price
                        $("#price").val(Math.round(price));
                        $("#priceshow").val((formatRupiahshow(newprice)));
                    } else if ($("#choise").val() == 2) {
                        let priceold = $('#priceold').val().replaceAll('.', '');
                        $("#price").val(priceold);
                        $("#priceshow").val(formatRupiahshow(priceold));

                    } else if ($("#choise").val() == 3) {
                        let priceold = $('#priceold').val().replaceAll('.', '');
                        $("#price").val(priceold);
                        $("#priceshow").val(formatRupiahshow(priceold));
                    }

                });

                $("#choise").change(function() {
                    if ($("#choise").val() == 1) {
                        let priceold = $('#priceold').val().replaceAll('.', '');
                        let price = priceold - (priceold / 111 * 11);
                        let newprice = Math.round(price).toString();
                        // console.log(newprice);
                        let pricestr = price
                        $("#price").val(Math.round(price));
                        $("#priceshow").val((formatRupiahshow(newprice)));
                    } else if ($("#choise").val() == 2) {
                        let priceold = $('#priceold').val().replaceAll('.', '');
                        $("#price").val(priceold);
                        $("#priceshow").val(formatRupiahshow(priceold));

                    } else if ($("#choise").val() == 3) {
                        let priceold = $('#priceold').val().replaceAll('.', '');
                        $("#price").val(priceold);
                        $("#priceshow").val(formatRupiahshow(priceold));
                    }
                });


                // for currency format

                // $(document).ready(function() {
                //     $('#priceold').on('keyup', function() {
                //         updateTextView($(this));
                //     });
                // });

            });

            $(document).ready(function() {
                $('#item_id').on('change', function() {
                    var item_id = $(this).val();
                    if (item_id) {
                        $.ajax({
                            url: '/get-units',
                            type: 'GET',
                            data: {item_id: item_id},
                            dataType: 'json',
                            success: function(data) {
                                $('#unit_id').empty();
                                $('#unit_id').append('<option value="">Pilih Unit</option>');
                                $.each(data, function(key, value) {
                                    $('#unit_id').append('<option value="' + value.unit_id + '">' + value.unit.name + '</option>');
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
    @endsection
