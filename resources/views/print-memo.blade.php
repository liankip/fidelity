<html>

<head>
    <meta charset="utf-8">
    <title>Purchase Request Memo</title>
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

        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid black;
            padding: 5px;
            text-align: left;
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

    <main>
        <table>
            <tr width="100%">
                <td colspan="7"><h1 style="text-align: center">MEMO</h1>
                </td>
                <td class="logo" rowspan="3" style="text-align: end; vertical-align: middle" width="20%"><img src="https://storage.googleapis.com/fidelity-assets/logo/SNE_Logo.png" alt="SNE Logo" width="160"></td>
            </tr>
            <tr>
                <td>Dari: {{ $prDetail->stock_from }}</td>
            </tr>
            <tr>
                <td>Tanggal: {{ $prDetail->created_at->format('d M Y') }}</td>
            </tr>
        </table>

        <table style="margin-top: 20px;">
            <thead style="font-weight: 600;">
                <tr>
                    <th width="5%" style="text-align: center">No</th>
                    <th style="text-align: center">Uraian Barang</th>
                    <th width="10%" style="text-align: center">Qty</th>
                    <th width="10%" style="text-align: center">Sat</th>
                    <th style="text-align: center">Keterangan</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="text-align: center">1</td>
                    <td style="text-align: center">{{ $prDetail->item_name }}</td>
                    <td style="text-align: center">{{ $prDetail->include_stock }}</td>
                    <td style="text-align: center">{{ $prDetail->unit }}</td>
                    <td style="text-align: center">{{ $prDetail->notes ?? '-' }}</td>
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
