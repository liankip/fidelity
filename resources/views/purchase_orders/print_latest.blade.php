<html>

<head>
    <meta charset="utf-8">
    <title>PO</title>
    <link rel="stylesheet" href="style.css">
    <link rel="license" href="https://www.opensource.org/licenses/mit-license/">
    <script src="script.js"></script>
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
        }

        table {
            border-collapse: separate;
            border-spacing: 2px;
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
            border-radius: 0.25em;
            border-style: solid;
        }

        th {
            background: #EEE;
            border-color: #BBB;
        }

        td {
            border-color: #DDD;
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
            margin: 0;
        }
    </style>
</head>

<body>
    <header style="font-size: 19px; font-weight: bold">
        <center>
            ERP {{ env('COMPANY') }}
        </center>
    </header>
    <article>
        {{-- <h1>Recipient</h1> --}}



        <div style="display: flex;justify-content: space-between; margin-top: 35px; margin-bottom: 20px">

            <td colspan="2">
                {{-- @foreach ($our_company as $val_our_company) --}}
                <div style="margin-bottom: 3px">{{ $our_company->name }}</div>

                <div>Date : {{ date('d-m-Y') }}</div>
                {{-- @endforeach --}}

        </div>

        <table class="inventory">
            <thead>
                <tr>
                    <th style="width: 5%">No</th>
                    <th style="width: 20%"><span>PO No.</span></th>
                    <th style="width: 15%"><span>PR No.</span></th>
                    <th style="width: 20%"><span>Project</span></th>
                    <th style="width: 10%"><span>Status</span></th>
                    <th style="width: 15%"><span>Date</span></th>
                    <th style="width: 15%">Total</span></th>
                </tr>
            </thead>
            <tbody>
                @php
                    $amount = 0;
                @endphp

                @foreach ($po_data as $key => $po)
                    <tr>
                        <td style="text-align: center">{{ $key + 1 }}</td>
                        <td>{{ $po->po_no }}</td>
                        <td>{{ $po->pr_no ?? '-' }}</td>
                        <td>{{ $po->pr->project->name ?? '-' }}</td>
                        <td>{{ $po->status }}</td>
                        <td>{{ $po->date_approved }}</td>
                        <td style="text-align: end">
                            <div style="display: flex;justify-content: space-between">
                                <div>Rp.</div>
                                @php
                                    $price = App\Helpers\GetAmount::get($po)['total']
                                @endphp
                                <div>
                                    {{ str_replace(',00', '', number_format($price, 2, ',', '.')) }}
                                </div>
                            </div>
                        </td>
                    </tr>
                    @php
                        $amount += $price;
                    @endphp
                @endforeach
                <tr>
                    <td colspan="6" style="font-weight: bold;width: 85%">Total</td>
                    <td style="font-weight: bold; width: 15%;">
                        <div style="display: flex;justify-content: space-between">
                            <div>Rp.</div>
                            <div>{{ str_replace(',00', '', number_format($amount, 2, ',', '.')) }}</div>
                        </div>
                    </td>
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
