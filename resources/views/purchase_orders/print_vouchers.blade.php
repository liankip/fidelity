<html>

<head>
    <meta charset="utf-8">
    <title>Voucher Pengeluaran {{ date('d F Y', strtotime($date)) }}</title>
    <style>
        /* reset */

        * {
            border: 0;
            box-sizing: content-box;
            color: inherit;
            font-family: inherit;
            font-size: inherit;
            font-style: inherit;
            font-weight: inherit;
            line-height: inherit;
            list-style: none;
            margin: 0;
            padding: 0;
            text-decoration: none;
            vertical-align: top;
        }

        /* content editable */

        *[contenteditable] {
            border-radius: 0.25em;
            min-width: 1em;
            outline: 0;
        }

        *[contenteditable] {
            cursor: pointer;
        }

        *[contenteditable]:hover,
        *[contenteditable]:focus,
        td:hover *[contenteditable],
        td:focus *[contenteditable],
        img.hover {
            background: #DEF;
            box-shadow: 0 0 1em 0.5em #DEF;
        }

        span[contenteditable] {
            display: inline-block;
        }

        /* heading */

        h1 {
            font: bold 100% sans-serif;
            letter-spacing: 0.5em;
            text-align: center;
            text-transform: uppercase;
        }

        /* table */

        table {
            font-size: 75%;
            table-layout: fixed;
            width: 100%;
            border: 1px solid black;
        }

        table {
            border-collapse: separate;
            border-spacing: 0;
        }

        th,
        td {
            border-width: 1px;
            padding: 0.5em;
            position: relative;
            text-align: left;
        }

        th,
        td {
            border-style: solid;
        }

        th {
            background: orange;
            border-color: black;
        }

        td {
            border-color: black;
        }

        /* page */

        html {
            font: 16px/1 'Open Sans', sans-serif;
            overflow: auto;
            padding: 0.5in;
        }

        html {
            background: #999;
            cursor: default;
        }

        body {
            box-sizing: border-box;
            height: 8, in;
            margin: 0 auto;
            overflow: hidden;
            padding: 0.5in;
            width: 11, 7in;
        }

        body {
            background: #FFF;
            border-radius: 1px;
            box-shadow: 0 0 1in -0.25in rgba(0, 0, 0, 0.5);
        }

        /* header */

        header {
            margin: 0 0 1em;
        }

        header:after {
            clear: both;
            content: "";
            display: table;
        }

        header h1 {
            background: #000;
            border-radius: 0.25em;
            color: #FFF;
            margin: 0 0 1em;
            padding: 0.5em 0;
        }

        header address {
            float: left;
            font-size: 75%;
            font-style: normal;
            line-height: 1.25;
            margin: 0 1em 1em 0;
        }

        header address p {
            margin: 0 0 0.25em;
        }

        header span,
        header img {
            display: block;
            float: right;
        }

        header span {
            margin: 0 0 1em 1em;
            max-height: 25%;
            max-width: 60%;
            position: relative;
        }

        header img {
            max-height: 100%;
            max-width: 100%;
        }

        header input {
            cursor: pointer;
            -ms-filter: "progid:DXImageTransform.Microsoft.Alpha(Opacity=0)";
            height: 100%;
            left: 0;
            opacity: 0;
            position: absolute;
            top: 0;
            width: 100%;
        }

        /* article */

        article,
        article address,
        table.meta,
        article:after {
            clear: both;
            content: "";
            display: table;
        }

        article h1 {
            clip: rect(0 0 0 0);
            position: absolute;
        }

        article address {
            float: left;
        }

        /* table meta & balance */

        table.meta,
        table.balance {
            float: right;
            width: 36%;
        }

        table.meta:after,
        table.balance:after {
            clear: both;
            content: "";
            display: table;
        }

        /* table meta */

        table.meta th {
            width: 40%;
        }

        table.meta td {
            width: 60%;
        }

        /* table items */

        table.inventory {
            clear: both;
            width: 100%;
        }

        table.inventory th {
            font-weight: bold;
            text-align: center;
        }


        /* table balance */

        table.balance th,
        table.balance td {
            width: 50%;
        }

        table.balance td {
            text-align: right;
        }

        /* aside */

        aside h1 {
            border: none;
            border-width: 0 0 1px;
            margin: 0 0 1em;
        }

        aside h1 {
            border-color: #999;
            border-bottom-style: solid;
        }

        /* javascript */

        .add,
        .cut {
            border-width: 1px;
            display: block;
            font-size: .8rem;
            padding: 0.25em 0.5em;
            float: left;
            text-align: center;
            width: 0.6em;
        }

        .add,
        .cut {
            background: #9AF;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
            background-image: -moz-linear-gradient(#00ADEE 5%, #0078A5 100%);
            background-image: -webkit-linear-gradient(#00ADEE 5%, #0078A5 100%);
            border-radius: 0.5em;
            border-color: #0076A3;
            color: #FFF;
            cursor: pointer;
            font-weight: bold;
            text-shadow: 0 -1px 2px rgba(0, 0, 0, 0.333);
        }

        .add {
            margin: -2.5em 0 0;
        }

        .add:hover {
            background: #00ADEE;
        }

        .cut {
            opacity: 0;
            position: absolute;
            top: 0;
            left: -1.5em;
        }

        .cut {
            -webkit-transition: opacity 100ms ease-in;
        }

        tr:hover .cut {
            opacity: 1;
        }

        @media print {
            @page {
                size: landscape;
            }

            * {
                -webkit-print-color-adjust: exact;
            }

            html {
                background: none;
                padding: 0;
            }

            body {
                box-shadow: none;
                margin: 0;
            }

            span:empty {
                display: none;
            }

            .add,
            .cut {
                display: none;
            }
        }

        @page {
            /* Set the margins for all pages */
            margin: 10% 0;
        }

        @page :first {
            margin-top: 0;
        }
    </style>
    <link rel="stylesheet" href="style.css">
    <link rel="license" href="https://www.opensource.org/licenses/mit-license/">
    <script src="script.js"></script>
</head>

<body>
<header style="font-size: 19px; font-weight: bold">
    <center>
        DAFTAR VOUCHER PENGELUARAN BANK {{ env('COMPANY') }} Periode {{ date('d F Y', strtotime($date)) }}
    </center>
</header>
<article>
    <table class="inventory">
        <thead>
        <tr>
            <th style="width: 5%">No</th>
            <th style="width: 20%"><span>No. Voucher</span></th>
            <th style="width: 15%"><span>Keterangan</span></th>
            <th style="width: 20%"><span>Bank Penerima</span></th>
            <th style="width: 10%"><span>Project</span></th>
            <th style="width: 15%"><span>Nama Item</span></th>
            <th style="width: 15%"><span>Permintaan & Penerima</span></th>
            <th style="width: 15%"><span>Status</span></th>
            <th style="width: 15%"><span>Total</span></th>
        </tr>
        </thead>
        <tbody>
        @php
            $total = 0;
        @endphp
        @foreach($vouchers as $voucher)
            @php
                $voucherDetails = $voucher->voucher_details;
                $purchaseOrder = $voucherDetails->first()->purchase_order;
                $voucherTotalAmount = $voucherDetails->pluck('purchase_order')->pluck('total_amount')->sum();
                $total += $voucherTotalAmount;
            @endphp
            <tr>
                <td style="vertical-align: middle;text-align: center;">{{ $loop->iteration  }}</td>
                <td style="vertical-align: middle;text-align: center">{{ $voucher->voucher_no  }}</td>
                <td style="vertical-align: middle;">
                    @foreach($voucherDetails as $detail)
                        <div style="margin-bottom: 14px">
                            <div style="margin-bottom: 5px">PO {{ $detail->purchase_order->po_no  }}</div>
                            <div style="margin-top: 10px">- {{ $detail->supplier->name }} <span>({{$detail->supplier->term_of_payment}})</span>
                            </div>
                        </div>
                    @endforeach
                </td>
                <td style="vertical-align: middle;text-align: center">
                    @foreach($voucherDetails->unique('supplier_id') as $detail)
                        @isset($detail->supplier->bank_name)
                            <p> {{$detail->supplier->bank_name}}</p>
                        @endisset

                        @isset($detail->supplier->norek)
                            <p> {{$detail->supplier->norek}}</p>
                        @endisset
                        <p>{{$detail->supplier->name}}</p>
                    @endforeach

                </td>
                <td style="vertical-align: middle;text-align: center;">
                    @foreach($voucherDetails->unique('project_id') as $detail)
                        <div style="margin-bottom: 14px">
                            {{ $detail->project->name }}
                        </div>
                    @endforeach
                </td>
                <td>
                    <ol>
                        @foreach($voucherDetails as $detail)
                            <li style="margin-bottom: 10px">
                                <b>PO {{ $detail->purchase_order->po_no }}</b>
                                <ul style="margin-bottom: 5px">
                                    @foreach($detail->purchase_order->podetail as $podetail)
                                        <li style="margin-bottom: 5px">
                                            <span>{{ $loop->iteration}}</span>.
                                            <span>{{$podetail->prdetail->item_name}}</span>
                                            <span>{{'@' . (int) $podetail->qty }}</span>
                                            <span>{{ $podetail->unit}}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </li>
                        @endforeach
                    </ol>
                </td>
                <td>
                    <ol>
                        @foreach($voucherDetails as $detail)
                            <li style="margin-bottom:20px">
                                <b>PO {{ $detail->purchase_order->po_no }}</b>
                                <div>
                                    Permintaan : {{ $detail->purchase_order->pr->requester}}
                                </div>
                                <div style="margin-top: 5px">
                                    Penerima :

                                    @if($detail->purchase_order->submition->count() > 0)
                                        {{ $detail->purchase_order->submition->first()->penerima }}
                                    @else
                                        {{ $detail->purchase_order->status_barang }}
                                    @endif
                                </div>
                            </li>
                        @endforeach
                    </ol>
                </td>
                <td>
                    <ol>
                        @foreach($voucherDetails as $detail)
                            <li style="margin-bottom:20px">
                                <b>PO {{ $detail->purchase_order->po_no }}</b>
                                <div style="text-align: center;margin-top: 5px">
                                    {{ $detail->purchase_order->status}}
                                </div>
                            </li>
                        @endforeach
                    </ol>
                </td>
                <td style="vertical-align: middle;text-align: center;">
                    {{ rupiah_format($voucherTotalAmount)}}
                </td>
            </tr>
        @endforeach
        <tr class="">
            <td colspan="6" class="" style="font-weight: bold;text-align: center">
                Amount
            </td>
            <td></td>
            <td></td>
            <td style="vertical-align: middle;text-align: center;">{{ rupiah_format($total) }}</td>
        </tr>
        </tbody>
    </table>
    {{-- <table class="inventory">
        <tr>
            <td colspan="6" style="font-weight: bold;width: 85%">Total</td>
            <td style="font-weight: bold; width: 15%;">
                <div style="display: flex;justify-content: space-between">
                    <div>Rp.</div>
                    <div>{{ str_replace(',00', '', number_format($amount, 2, ',', '.')) }}</div>
                </div>
            </td>
        </tr>
    </table> --}}

</article>
<script>
    // window.onload = function() {
    //     window.print();
    // }
</script>

</body>

</html>
