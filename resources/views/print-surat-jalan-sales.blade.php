<html>

<head>
    <meta charset="utf-8">
    <title>Surat Jalan Sales</title>
    <link rel="stylesheet" href="style.css">
    <link rel="license" href="https://www.opensource.org/licenses/mit-license/">
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
        }

        th,
        td {
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
            text-align: center;
            align-content: center
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
            min-height: 11in;
            margin: 0 auto;
            overflow: hidden;
            padding: 0.5in;
            width: 8.5in;
        }

        body {
            background: #FFF;
            border-radius: 1px;
            box-shadow: 0 0 1in -0.25in rgba(0, 0, 0, 0.5);
        }

        /* header */

        header {
            margin: 0 0 1em;
            display: flex;
            align-items: center;
        }

        header h1 {
            text-align: center;
            width: 100%;
        }


        @media print {
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

        @page: first {
            /* Set the margins for all pages */
            margin: 0;
            margin-bottom: 20%
                /* Adjust the margin size as needed */
        }

        @page {
            margin: 0;
            margin-top: 10%;
        }

        #customerTable td {
            border-width: 1px;
        }
    </style>
</head>

<body>

    <header>
        <img src="https://storage.googleapis.com/fidelity-assets/logo/SNE_Logo.png" alt="SNE Logo" width="100">
        <h1>PT Satria Nusa Enjinering</h1>
    </header>

    <main>
        <h1 style="text-align: center; margin-top: 5%; font-weight: 600; text-decoration: underline">Surat Jalan</h1>

        {{-- Customer Information Table --}}
        <table style="margin-top: 5%">
            <tr>
                <td>Kepada Yth,</td>
                <td style="text-align: right">No. Surat Jalan: <span>________________________</span></td>
            </tr>

            <tr>
                <td style="text-transform: capitalize">{{ $salesData->customer->name }}</td>
            </tr>

            <tr>
                <td style="text-transform: capitalize">{{ $salesData->customer->shipping_address }}</td>
                <td style="text-align: right">Dibawa oleh: <span>________________________</span></td>
            </tr>

            <tr>
                <td style="text-transform: capitalize">{{ $salesData->customer->pic_name }} :
                    {{ $salesData->customer->pic_phone }}</td>
                @if ($salesData->notes !== null)
                    <td style="text-align: right">Note : {{ $salesData->notes }}</td>
                @endif
            </tr>
        </table>

        {{-- Product Table --}}
        <table style="margin-top: 5%" id="customerTable">
            <thead>
                <tr>
                    <th rowspan="2" width="5%">No</th>
                    <th rowspan="2">Item Name</th>
                    <th rowspan="2">Quantity</th>
                    <th colspan="2" rowspan="1" width="20%">Kondisi</th>
                </tr>
                <tr>
                    <th>Baik</th>
                    <th>Tidak</th>
                </tr>
            </thead>
            <tbody>
                @foreach (json_decode($salesData->product) as $product)
                    @php
                        $productName = $productData->find($product->product)->name;
                        $itemUnit = $itemData->where('name', $productName)->first()->unit;
                    @endphp
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $productName }}</td>
                        <td>{{ $product->qty }}</td>
                        <td style="text-align: center">
                            <input type="checkbox" style="width: 15px; height: 15px">
                        </td>
                        <td style="text-align: center">
                            <input type="checkbox" style="width: 15px; height: 15px">
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <p style="margin-top:2%; font-weight: 200; font-size: 8pt; text-color: #ffffff">Metode Pembayaran:
            {{ $salesData->payment_method }}</p>

        {{-- Signature Table --}}
        <table style="margin-top: 10%">
            <thead>
                <tr>
                    <td style="border: 1px solid black; text-align: center">Dikirim oleh</td>
                    <td style="border: 1px solid black; text-align: center">Diterima oleh</td>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="height: 100px; border: 1px solid black; vertical-align: bottom">

                        <h1>PT Satria Nusa Enjinering</h1>
                    </td>
                    <td style="border: 1px solid black"></td>
                </tr>
            </tbody>
        </table>

    </main>

    <script>
        window.onload = function() {
            window.print();
        }
    </script>
</body>

</html>
