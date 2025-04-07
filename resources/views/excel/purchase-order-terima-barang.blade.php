<!DOCTYPE html>
<html>
<head>
</head>
<body>
<table>
    <thead>
    <tr>
        <th>No</th>
        <th>No PO</th>
        <th>Tanggal Upload Foto</th>
        <th>Tanggal Barang Masuk</th>
        <th>Barang</th>
        <th>Unit</th>
        <th>Satuan</th>
        <th>Supplier</th>
    </tr>
    </thead>
    <tbody>
    @php $no = 1; @endphp
    @foreach ($purchase_orders as $poGroup)
        @php
            $isFirstRow = true;
            $rowspan = count($poGroup['podetail']);
        @endphp
        @foreach ($poGroup['podetail'] as $detail)
            <tr>
                <td>{{ $no++ }}</td>

                @if ($isFirstRow)
                    <td rowspan="{{ $rowspan }}">{{ $poGroup['po_no'] }}</td>
                @endif

                <td>
                    @php
                        $hasFotoBarang = false;
                        foreach ($poGroup['submition'] as $submition) {
                            if ($submition->item_id == $detail->item_id && !empty($submition->foto_barang)) {
                                echo \Carbon\Carbon::parse($submition->updated_at)->format('Y-m-d');
                                $hasFotoBarang = true;
                                break;
                            }
                        }
                    @endphp

                    @if (!$hasFotoBarang)
                        <span class="not-arrived">Barang belum masuk</span>
                    @endif
                </td>
                <td>
                    @php
                        foreach ($poGroup['submition'] as $submition) {
                            if ($submition->item_id == $detail->item_id && !empty($submition->actual_date)) {
                                echo \Carbon\Carbon::parse($submition->actual_date)->format('Y-m-d');
                                break;
                            }
                        }
                    @endphp
                </td>
                <td>{{ $detail->item->name }}</td>
                <td>{{ $detail->qty }}</td>
                <td>{{ $detail->unit }}</td>

                @if ($isFirstRow)
                    <td rowspan="{{ $rowspan }}">{{ $poGroup['supplier']->name }}</td>
                @endif
            </tr>
            @php $isFirstRow = false; @endphp
        @endforeach
    @endforeach
    </tbody>
</table>
</body>
</html>
