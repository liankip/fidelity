<html>

<head>
    <meta charset="utf-8">
    <title>Document Purchase Order</title>
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
        table.inventory {
            margin: 0 0 1em;
        }

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

        table.inventory td:nth-child(1) {
            width: 26%;
        }

        table.inventory td:nth-child(2) {
            width: 38%;
        }

        table.inventory td:nth-child(3) {
            text-align: right;
            width: 12%;
        }

        table.inventory td:nth-child(4) {
            text-align: right;
            width: 12%;
        }

        table.inventory td:nth-child(5) {
            text-align: right;
            width: 12%;
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
    <script>
        /* Shivving (IE8 is not supported, but at least it won't look as awful)
                                                                                                                                                                        /* ========================================================================== */

        (function(document) {
            var
                head = document.head = document.getElementsByTagName('head')[0] || document.documentElement,
                elements =
                'article aside audio bdi canvas data datalist details figcaption figure footer header hgroup mark meter nav output picture progress section summary time video x'
                .split(' '),
                elementsLength = elements.length,
                elementsIndex = 0,
                element;

            while (elementsIndex < elementsLength) {
                element = document.createElement(elements[++elementsIndex]);
            }

            element.innerHTML = 'x<style>' +
                'article,aside,details,figcaption,figure,footer,header,hgroup,nav,section{display:block}' +
                'audio[controls],canvas,video{display:inline-block}' +
                '[hidden],audio{display:none}' +
                'mark{background:#FF0;color:#000}' +
                '</style>';

            return head.insertBefore(element.lastChild, head.firstChild);
        })(document);

        /* Prototyping
        /* ========================================================================== */

        (function(window, ElementPrototype, ArrayPrototype, polyfill) {
            function NodeList() {
                [polyfill]
            }
            NodeList.prototype.length = ArrayPrototype.length;

            ElementPrototype.matchesSelector = ElementPrototype.matchesSelector ||
                ElementPrototype.mozMatchesSelector ||
                ElementPrototype.msMatchesSelector ||
                ElementPrototype.oMatchesSelector ||
                ElementPrototype.webkitMatchesSelector ||
                function matchesSelector(selector) {
                    return ArrayPrototype.indexOf.call(this.parentNode.querySelectorAll(selector), this) > -1;
                };

            ElementPrototype.ancestorQuerySelectorAll = ElementPrototype.ancestorQuerySelectorAll ||
                ElementPrototype.mozAncestorQuerySelectorAll ||
                ElementPrototype.msAncestorQuerySelectorAll ||
                ElementPrototype.oAncestorQuerySelectorAll ||
                ElementPrototype.webkitAncestorQuerySelectorAll ||
                function ancestorQuerySelectorAll(selector) {
                    for (var cite = this, newNodeList = new NodeList; cite = cite.parentElement;) {
                        if (cite.matchesSelector(selector)) ArrayPrototype.push.call(newNodeList, cite);
                    }

                    return newNodeList;
                };

            ElementPrototype.ancestorQuerySelector = ElementPrototype.ancestorQuerySelector ||
                ElementPrototype.mozAncestorQuerySelector ||
                ElementPrototype.msAncestorQuerySelector ||
                ElementPrototype.oAncestorQuerySelector ||
                ElementPrototype.webkitAncestorQuerySelector ||
                function ancestorQuerySelector(selector) {
                    return this.ancestorQuerySelectorAll(selector)[0] || null;
                };
        })(this, Element.prototype, Array.prototype);

        /* Helper Functions
        /* ========================================================================== */

        function generateTableRow() {
            var emptyColumn = document.createElement('tr');

            emptyColumn.innerHTML = '<td><a class="cut">-</a><span contenteditable></span></td>' +
                '<td><span contenteditable></span></td>' +
                '<td><span data-prefix>$</span><span contenteditable>0.00</span></td>' +
                '<td><span contenteditable>0</span></td>' +
                '<td><span data-prefix>$</span><span>0.00</span></td>';

            return emptyColumn;
        }

        function parseFloatHTML(element) {
            return parseFloat(element.innerHTML.replace(/[^\d\.\-]+/g, '')) || 0;
        }

        function parsePrice(number) {
            return number.toFixed(2).replace(/(\d)(?=(\d\d\d)+([^\d]|$))/g, '$1,');
        }

        /* Update Number
        /* ========================================================================== */

        function updateNumber(e) {
            var
                activeElement = document.activeElement,
                value = parseFloat(activeElement.innerHTML),
                wasPrice = activeElement.innerHTML == parsePrice(parseFloatHTML(activeElement));

            if (!isNaN(value) && (e.keyCode == 38 || e.keyCode == 40 || e.wheelDeltaY)) {
                e.preventDefault();

                value += e.keyCode == 38 ? 1 : e.keyCode == 40 ? -1 : Math.round(e.wheelDelta * 0.025);
                value = Math.max(value, 0);

                activeElement.innerHTML = wasPrice ? parsePrice(value) : value;
            }

            updateInvoice();
        }

        /* Update Invoice
        /* ========================================================================== */
        function generateBarCode() {
            var nric = $('#text').val();
            var url = 'https://api.qrserver.com/v1/create-qr-code/?data=' + nric + '&amp;size=250x250';
            $('#barcode').attr('src', url);
        }

        function updateInvoice() {
            var total = 0;
            var cells, price, total, a, i;

            // update inventory cells
            // ======================

            for (var a = document.querySelectorAll('table.inventory tbody tr'), i = 0; a[i]; ++i) {
                // get inventory row cells
                cells = a[i].querySelectorAll('span:last-child');

                // set price as cell[2] * cell[3]
                price = parseFloatHTML(cells[2]) * parseFloatHTML(cells[3]);

                // add price to total
                total += price;

                // set row total
                cells[4].innerHTML = price;
            }

            // update balance cells
            // ====================

            // get balance cells
            cells = document.querySelectorAll('table.balance td:last-child span:last-child');

            // set total
            cells[0].innerHTML = total;

            // set balance and meta balance
            cells[2].innerHTML = document.querySelector('table.meta tr:last-child td:last-child span:last-child')
                .innerHTML = parsePrice(total - parseFloatHTML(cells[1]));

            // update prefix formatting
            // ========================

            var prefix = document.querySelector('#prefix').innerHTML;
            for (a = document.querySelectorAll('[data-prefix]'), i = 0; a[i]; ++i) a[i].innerHTML = prefix;

            // update price formatting
            // =======================

            for (a = document.querySelectorAll('span[data-prefix] + span'), i = 0; a[i]; ++i)
                if (document.activeElement != a[i]) a[i].innerHTML = parsePrice(parseFloatHTML(a[i]));
        }

        /* On Content Load
        /* ========================================================================== */

        function onContentLoad() {
            updateInvoice();

            var
                input = document.querySelector('input'),
                image = document.querySelector('img');

            function onClick(e) {
                var element = e.target.querySelector('[contenteditable]'),
                    row;

                element && e.target != document.documentElement && e.target != document.body && element.focus();

                if (e.target.matchesSelector('.add')) {
                    document.querySelector('table.inventory tbody').appendChild(generateTableRow());
                } else if (e.target.className == 'cut') {
                    row = e.target.ancestorQuerySelector('tr');

                    row.parentNode.removeChild(row);
                }

                updateInvoice();
            }

            function onEnterCancel(e) {
                e.preventDefault();

                image.classList.add('hover');
            }

            function onLeaveCancel(e) {
                e.preventDefault();

                image.classList.remove('hover');
            }

            function onFileInput(e) {
                image.classList.remove('hover');

                var
                    reader = new FileReader(),
                    files = e.dataTransfer ? e.dataTransfer.files : e.target.files,
                    i = 0;

                reader.onload = onFileLoad;

                while (files[i]) reader.readAsDataURL(files[i++]);
            }

            function onFileLoad(e) {
                var data = e.target.result;

                image.src = data;
            }

            if (window.addEventListener) {
                document.addEventListener('click', onClick);

                document.addEventListener('mousewheel', updateNumber);
                document.addEventListener('keydown', updateNumber);

                document.addEventListener('keydown', updateInvoice);
                document.addEventListener('keyup', updateInvoice);

                input.addEventListener('focus', onEnterCancel);
                input.addEventListener('mouseover', onEnterCancel);
                input.addEventListener('dragover', onEnterCancel);
                input.addEventListener('dragenter', onEnterCancel);

                input.addEventListener('blur', onLeaveCancel);
                input.addEventListener('dragleave', onLeaveCancel);
                input.addEventListener('mouseout', onLeaveCancel);

                input.addEventListener('drop', onFileInput);
                input.addEventListener('change', onFileInput);
            }
        }

        window.addEventListener && document.addEventListener('DOMContentLoaded', onContentLoad);
    </script>
</head>

<body>
    <header>
        @if ($get_prtype->pr_type == 'Barang')
            <h1>Purchase Order</h1>
        @endif
        @if ($get_prtype->pr_type != 'Barang')
            <h1>Surat Perintah Kerja</h1>
        @endif

    </header>
    <article>
        {{-- <h1>Recipient</h1> --}}
        <div style="display: flex; justify-content: space-between; margin-bottom: 8px">
            <div style="font-size: 12px; margin-top: 6px; font-weight: bold;width: 50%">
                <p style="margin-bottom: 5px">{{ $po_data->supplier->name }}</p>
                <p style="margin-bottom: 5px">{{ $po_data->supplier->address }} </p>
                <p>{{ $po_data->supplier->city }}, {{ $po_data->supplier->province }}</p>
            </div>

            <div style="display: flex;justify-content: space-between; width: 50%">
                <div></div>
                <div style="width: 90%">
                    <table style="width: 100%">
                        <tr>
                            <td colspan="2">
                                {{-- @foreach ($our_company as $val_our_company) --}}
                                <div style="margin-bottom: 3px">{{ $our_company->name }}</div>

                                <div style="margin-bottom: 3px; line-height: 18px">
                                    <p>{{ $our_company->address }}</p>
                                </div>

                                <div>NPWP : {{ $our_company->npwpd }}</div>
                                {{-- @endforeach --}}
                            </td>
                        </tr>
                    </table>
                    <table class="" style="width: 100%">
                        <tr>
                            <th style="width: 30%">Project Name</th>
                            <td>{{ $po_data->project ? $po_data->project->name : 'Stok Persediaan Gudang' }}</td>
                        </tr>
                        <tr>
                            <th style="width: 30%">No.</th>
                            <td>
                                <p>{{ $po_data->po_no }}</p>
                            </td>
                        </tr>
                        <tr>
                            <th>Date</th>
                            <td>{{ $newDate }}</td>
                        </tr>

                        <tr>
                            <th>Payment Term</th>
                            <td>{{ $po_data->term_of_payment }}</td>
                        </tr>

                        <tr>
                            <th>Pengiriman</th>
                            <td>Dijemput</td>
                        </tr>

                    </table>
                </div>
            </div>
        </div>


        <table class="inventory">
            <thead>
                <tr style="width: 100%">
                    <th style="width: 5%">No</th>
                    <th style="width: 15%"><span>WBS</span></th>
                    <th style="width: 35%"><span>Item Name</span></th>
                    <th style="width: 10%">Qty/Unit</span></th>
                    <th style="width: 25%">Unit Price</span></th>
                    <th style="width: 25%">Price</span></th>
                </tr>
            </thead>
            <tbody>
                @php
                    $podetailno = 0;
                    $amount = 0;
                @endphp

                @foreach ($po_detail as $keydtail => $val_po_data_detail)
                    @php
                        $podetailno += 1;
                    @endphp
                    <tr style="width: 100%">

                        <td style="text-align: center; width: 5%">{{ $podetailno }}</td>
                        {{-- <td style="text-align: center; width: 30%">@dump($val_po_data_detail->po->pr->partof)</td> --}}
                        <td style="text-align: center; width: 15%">
                            {{ $val_po_data_detail->prdetail->purchaseRequest->partof ?? '-' }}</td>
                        <td style="text-align:left">{{ $val_po_data_detail->item->name }}
                            @if($val_po_data_detail->supplier_description != null)
                                <br><br>
                                <p style="text-align:left">
                                    <small><b>Product desc:</b> {{ $val_po_data_detail->supplier_description }}</small>
                                </p>
                            @else
                            <br><br>
                            <p style="text-align:left">
                                <small><b>Product desc:</b> {{ $val_po_data_detail->prdetail ? $val_po_data_detail->prdetail->item_name : $val_po_data_detail->item->name }}</small>
                            </p>
                            @endif
                            @if ($val_po_data_detail->prdetail?->item?->notes_k3)
                                <br><br>
                                <p style="font-size: 7pt; color: red">
                                    <span>*</span><small>{{ $val_po_data_detail->prdetail->item->notes_k3 }}</small>
                                </p>
                            @endif
                        </td>
                        {{-- <td>{{ $val_po_data_detail->item->type }}</td> --}}
                        <td>
                            {{-- {{ number_format($val_po_data_detail->qty, 2, ',', '.') }} --}}
                            {{ str_replace(',00', '', number_format($val_po_data_detail->qty, 2, ',', '.')) }}
                            {{ $val_po_data_detail->prdetail ? $val_po_data_detail->prdetail->unit : $val_po_data_detail->unit }}</td>
                        <td>
                            <div style="display: flex;justify-content: space-between">
                                <span data-prefix>Rp. </span>
                                <div>
                                    {{-- {{ number_format($val_po_data_detail->price, 0, ',', '.') }} --}}
                                    {{ str_replace(',00', '', number_format($val_po_data_detail->price, 2, ',', '.')) }}
                                </div>
                            </div>
                        </td>
                        <td>
                            <div style="display: flex;justify-content: space-between">
                                <span data-prefix>Rp.
                                </span>
                                <div>
                                    {{-- {{ number_format($jumlah = round($val_po_data_detail->price * $val_po_data_detail->qty), 0, ',', '.') }} --}}
                                    {{ str_replace(',00', '', number_format($val_po_data_detail->amount, 2, ',', '.')) }}
                                </div>
                            </div>
                        </td>
                    </tr>
                    @php
                        $amount += $val_po_data_detail->amount;
                    @endphp
                @endforeach
                {{-- <tr>

                        <td>1</td>
						<td>Bag 1</td>

						<td>1</td>
                        <td><span data-prefix>Rp. </span>213</td>
						<td><span data-prefix>Rp. </span>213</td>
					</tr> --}}
            </tbody>
        </table>
        {{-- Jasa Pengiriman : {{ $ds->name }}<br>
        Total Tarif : {{ number_format($po->tarif_ds,2) }} <br> --}}
        {{-- <div style="font-size: 12px; margin-top: 6px; font-weight: bold;width: 50%">
            <p style="margin-bottom: 5px">Note</p>
            @if ($po_data->notes !== null)
                @php
                    $notes = json_decode($po_data->notes);
                @endphp
                @foreach ($notes as $note)
                    <p style="margin-bottom: 5px">{{ $users[$note->user_id] ?? 'Unknown User' }}:
                        {{ $note->notes }}</p>
                @endforeach
            @else
            -
            @endif
        </div> --}}

    </article>
    <aside>
        <h1></h1>
        <table class="balance">
            {{-- @foreach ($po_detail as $val_po_data_detail) --}}
            <tr>
                <th>Amount</th>
                <td>
                    <div style="display: flex;justify-content: space-between">
                        <span data-prefix>Rp. </span>
                        <div>{{ number_format($amount, 0, ',', '.') }}</div>
                    </div>
                </td>
            </tr>
            {{-- <tr>
                <th>Tax in %</th>
                <td>{{ number_format($total_tax,2) }} <span data-prefix>%</span></td>
            </tr> --}}
            <tr>
                <th>Tax </th>
                <td>
                    <div style="display: flex;justify-content: space-between">
                        <span data-prefix>Rp. </span>
                        <div>
                            @if ($po_data->tax_custom)
                                {{ str_replace(',00', '', number_format($tax = $po_data->tax_custom, 2, ',', '.')) }}
                            @else
                                {{ number_format($tax = round(($amount * $total_tax) / 100), 0, ',', '.') }}
                            @endif
                        </div>
                    </div>
                </td>
            </tr>
            {{-- <tr>
                <th>Ongkos Kirim</th>
                <td><span data-prefix>Rp. </span>{{ number_format($po->tarif_ds,2) }} <span data-prefix></span></td>
            </tr> --}}
            <tr style="font-weight: bold">
                <th>TOTAL</th>
                <td>
                    <div style="display: flex;justify-content: space-between">
                        <span data-prefix>Rp. </span>
                        <div>{{ number_format($amount + $tax, 0, ',', '.') }}</div>
                    </div>
                </td>
            </tr>
            {{-- @endforeach --}}

        </table>
        <p style="font-size: 12px">
            Pemesan :
        </p>
        <br>
        <input id="text" type="text" value="Satria Nusa Engineering" style="Width:0%"
            onblur='generateBarCode();' />

        <img id='barcode'
            src="https://api.qrserver.com/v1/create-qr-code/?data=https://satrianusa.group/&amp;size=100x100"
            alt="" title="SNE" width="75" height="75" />
        <div>
            <br>
            <p style="font-size: 12px">{{ $our_company->name }} <br> Telp. {{ $our_company->phone }}</p>
        </div>
    </aside>
    <script>
        window.onload = function() {
            window.print();
        }
    </script>
</body>

</html>
