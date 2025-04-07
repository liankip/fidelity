@extends('layouts.app')

@section('content')
<div class="container mt-2">
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left mb-2">
                <a href="{{ route('prices.index') }}" class="third-color-sne"> <i
                    class="fa-solid fa-chevron-left fa-xs"></i> Back</a>
                <h2 class="primary-color-sne">Edit Price</h2>
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

    <form action="{{ route('prices.update', $price->id) }}" method="POST" class="mt-5" enctype="multipart/form-data">
        <div class="card primary-box-shadow">

                <div class="card-body my-4">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Supplier<span class="text-danger">*</span></strong>
                                <input type="text" name="supplier_id" class="form-control" value="{{$price->supplier->name}}" readonly>

                                @error('supplier_id')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-12 mt-4">
                            <div class="form-group">
                                <strong>Item<span class="text-danger">*</span></strong>
                                <input type="text" name="item_id" class="form-control" value="{{ $price->item->name }}" readonly>

                                @error('item_id')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-12 mt-4">
                            <div class="form-group">
                                <strong>Unit<span class="text-danger">*</span></strong>
                                <input type="text" name="unit_id" class="form-control" value="{{ $price->unit->name }}" readonly>

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
                                    <input type="text" id="priceold" name="priceold" class="form-control" value="{{ $price_old }}">
                                    <span class="input-group-text">.00</span>
                                </div>
                                @error('price')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>


                        <div class="col-xs-12 col-sm-12 col-md-12 mt-4">
                            <div class="form-group">
                                <strong>Tax<span class="text-danger">*</span></strong>
                                <select class="form-select" id="choise" name="choise">
                                    @if ($priceold->tax_status == 0)
                                        <option value="1">Include Tax</option>
                                        <option selected value="2">Exclude Tax</option>
                                        <option value="3">Non PPN</option>
                                    @elseif ($priceold->tax_status == 1)
                                        <option selected value="1">Include Tax</option>
                                        <option value="2">Exclude Tax</option>
                                        <option value="3">Non PPN</option>
                                    @elseif ($priceold->tax_status == 2)
                                        <option value="1">Include Tax</option>
                                        <option value="2">Exclude Tax</option>
                                        <option selected value="3">Non PPN</option>
                                    @endif

                                </select>
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-12 mt-4">
                            <div class="form-group">
                                <strong>Price Result<span class="text-danger">*</span></strong>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="text" id="priceshow" value="{{ $priceold->price }}" readonly
                                        name="price" class="form-control" placeholder="Price in Rp">
                                    <span class="input-group-text">.00</span>
                                </div>
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-12 mt-4">
                            <div class="px-3 bg-secondary p-3 rounded">
                                <div class="text-warning">Silahkan abaikan form ini jika price tidak terpengaruh oleh kurs
                                </div>
                                {{-- <div class="form-check">
                                    <input class="form-check-input" name="depend_usd" type="checkbox" value="1" id="flexCheckDefault">
                                    <label class="form-check-label text-white" for="flexCheckDefault" >
                                        Terpengaruh atas dolar(<span class="text-warning">price akan bisa di ubah
                                            berdasarkan
                                            kurs yang baru jika di checlish</span>)
                                    </label>
                                </div> --}}
                                <strong class="text-white">Kurs rupiah terhadap dolar</strong>
                                <input type="number" class="form-control" name="kurs"
                                    value="{{ $priceold->old_idr_by_usd }}"
                                    placeholder="ex: 15609 (Abaikan jika price tidak berkaitan dengan kurs)" id="">
                            </div>

                        </div>
                        <input style="display: none" type="number" id="price" value="{{ $priceold->price }}" readonly
                            name="price" class="form-control" placeholder="Price in Rp">

                        @if ($priceold->old_idr_by_usd)
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    {{-- <strong>Updated By:</strong> --}}
                                    <input type="hidden" name="updated_by" value="{{ Auth::id() }}"
                                        class="form-control" placeholder="updated_by">
                                    @error('updated_by')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        @endif
                        {{-- <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-check form-switch">
                                <input class="form-check-input" name="exclude_tax" type="checkbox" role="switch"
                                    value="1" id="flexSwitchCheckDefault">
                                <label class="form-check-label" for="flexSwitchCheckDefault">Exclude tax</label>
                            </div>

                            <div class="form-check form-switch">
                                <input class="form-check-input" name="non_ppn" type="checkbox" value="1" role="switch"
                                    id="flexSwitchCheckDefault1">
                                <label class="form-check-label" for="flexSwitchCheckDefault1">Non PPN</label>
                            </div>
                        </div> --}}


                    </div>
                    <button type="submit" class="btn btn-primary ml-3 mt-3"><i class="fa-solid fa-floppy-disk pe-2"></i>Save</button>
                </div>
            </form>
        </div>

    </div>
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
        });

        $("#priceshow").val(formatRupiahshow(Math.round($("#priceshow").val()).toString()));

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

        $('#test1').click(function() {
            // alert("Checkbox state (method 1) = " + $('#test1').prop('checked'));
            // if ($('#test1').prop('checked')) {
            //     $("#price").val(parseInt($('#priceold').val().replaceAll(',', '')));
            //     $("#priceshow").val(parseInt($('#priceold').val().replaceAll(',', '')));
            //     updateTextView($("#priceshow"));
            // } else {
            //     $("#price").val(parseInt($('#priceold').val().replaceAll(',', '')) - parseInt($(
            //         '#priceold').val().replaceAll(',', '')) * 0.11);
            //     $("#priceshow").val(parseInt($('#priceold').val().replaceAll(',', '')) - parseInt($(
            //         '#priceold').val().replaceAll(',', '')) * 0.11);
            //     updateTextView($("#priceshow"));

            // }
            if ($('#test1').prop('checked')) {
                let priceold = $('#priceold').val().replaceAll('.', '');
                $("#price").val(priceold);
                $("#priceshow").val(priceold);
                // updateTextView($("#priceshow"));

            } else {
                let priceold = $('#priceold').val().replaceAll('.', '');

                let price = priceold - (priceold / 111 * 11);
                let newprice = Math.round(price).toString();
                // console.log(newprice);
                let pricestr = price
                $("#price").val(price);
                $("#priceshow").val((formatRupiahshow(newprice)));

                // $("#priceshow").val(parseFloat($('#priceold').val().replaceAll(',', '')) - parseFloat($(
                //     '#priceold').val().replace All(',', '')) * 0.11);

                // updateTextView($("#priceshow"));
            }
        });

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


            // if ($('#test1').prop('checked')) {
            //     let priceold = $('#priceold').val().replaceAll('.', '');
            //     $("#price").val(priceold);
            //     $("#priceshow").val(priceold);


            // } else {
            //     let priceold = $('#priceold').val().replaceAll('.', '');

            //     let price = priceold - (priceold * 0.11);
            //     let newprice = Math.round(price).toString();
            //     // console.log(newprice);
            //     let pricestr = price
            //     $("#price").val(Math.round(price));
            //     $("#priceshow").val((formatRupiahshow(newprice)));


            // }
        });

        $("#choise").change(function() {
            if ($("#choise").val() == 1 && $('#priceold').val()) {
                let priceold = $('#priceold').val().replaceAll('.', '');
                let price = priceold - (priceold / 111 * 11);
                let newprice = Math.round(price).toString();
                // console.log(newprice);
                let pricestr = price
                $("#price").val(Math.round(price));
                $("#priceshow").val((formatRupiahshow(newprice)));

            } else if ($("#choise").val() == 2 && $('#priceold').val()) {
                let priceold = $('#priceold').val().replaceAll('.', '');
                $("#price").val(priceold);
                $("#priceshow").val(formatRupiahshow(priceold));

            } else if ($("#choise").val() == 3 && $('#priceold').val()) {
                let priceold = $('#priceold').val().replaceAll('.', '');
                $("#price").val(priceold);
                $("#priceshow").val(formatRupiahshow(priceold));
            } else {

            }
        });
    </script>
@endsection
