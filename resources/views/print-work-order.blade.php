<html>

<head>
    <meta charset="utf-8">
    <title>Print Work Orders</title>
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
            border: solid 3px #000000; 
            border-collapse: collapse;
        }

        th, td {
            padding: 8px;
        }

        th{
            font-weight: bold;
        }

        .header-table-row{
            border: 3px solid #000000;
            table-layout: fixed;
        }

        .header-table-row td{ 
            font-weight: bold;
            font-size: 8pt;
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

        .item-table th{
            border : 2px solid #000000;
            border-top: none;
            padding: 5px;
            text-align: center;
            border-collapse: collapse;
        }
        

        .data-row{
            border: 1px dashed #000000;
        }
        .data-row td{
            border-right: 2px solid #000000;
            border-collapse: collapse;
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

    </style>
</head>

<body>

    <main>
        <table class="header-table">
            <tr>
                <td><img src="https://storage.googleapis.com/fidelity-assets/logo/SNE_Logo.png" alt="SNE Logo" width="170" style="border-right: none"></td>
                <td colspan="11" style="text-align: right; font-size: 18px; font-weight: bold; vertical-align: middle"><strong>WO INTERNAL</strong></td>
            </tr>
            <tr class="header-table-row">
                <td colspan="5">Untuk: </td>
                <td colspan="5">Customer: </td>
                <td rowspan="2" style="border: 3px solid #000000; text-align: center; vertical-align: top" colspan="2">PIC WS</td>
            </tr>
            <tr class="header-table-row">
                <td colspan="5">
                    <p style="display: flex; gap:20% ;;">
                        <span>Tanggal:</span>
                        <span>{{ $workOrder->created_at->format('d-m-Y') }}</span>
                    </p>
                </td>
                <td colspan="5">
                    <p style="display: flex; gap:20% ;;">
                        <span>No WO:</span>
                        <span>{{ $workOrder->number }}</span>
                    </p>
                </td>
            </tr>
            
        </table>
        <table style="border-top: none" class="item-table">
            <thead>
                <th width="5%">No</th>
                <th width="50%">Uraian Pekerjaan</th>
                <th width="10%">Qty</th>
                <th width="10%">Satuan</th>
                <th width="30%">Tanggal Selesai</th>
            </thead>

            <tbody>
                @foreach ($workOrderData as $data)
                    <tr style="text-align: center" class="data-row">
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $data['name'] }}</td>
                        <td>{{ $data['quantity'] }}</td>
                        <td></td>
                        <td></td>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="5" style="border: 2px solid #000000">PO Customer</td>
                </tr>
                <tr>
                    <td rowspan="5" style="border: 1px solid #000000" height="200px" colspan="5">
                        <p style="display: flex; justify-content:space-between; padding-right:30%">
                            <span>Catatan Pemesanan:</span>
                            <span>Dibuat Oleh:</span>
                        </p>
                    </td>
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
