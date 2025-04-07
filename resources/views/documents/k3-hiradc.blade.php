<html>

<head>
    <meta charset="utf-8">
    <title>K3-hiradc</title>
    <link rel="license" href="https://www.opensource.org/licenses/mit-license/">
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <style>
        body {
            background: white;
            color: black !important;
        }

        .table {
            color: black;
        }

        table,
        th,
        tr,
        td {
            border: 1px solid black;
        }

        .table th,
        .table td {
            border-top: 1px solid black;
        }

        .dataTable-table:not(.table-borderless) thead th,
        .table:not(.table-borderless) thead th {
            border-bottom: 1px solid black !important;
        }

        .table thead th {
            vertical-align: middle;
        }

        ul {
            list-style: none;
        }

        ul li {
            margin-bottom: 20px;
        }
    </style>
</head>

<body class="container-fluid p-2">
    <table class="table table-bordered" style="width: 100%">
        <thead>
            <tr>
                <th colspan="2" rowspan="3">
                    <img src="{{asset('images/sne.png')}}" width="150" alt="">
                </th>
                <th colspan="7" rowspan="3">IDENTIFIKASI BAHAYA K3 DAN PENGENDALIANNYA</th>
                <th rowspan="3">
                    <span>No. Dokumen</span><br>
                    <span>Tanggal Efektif</span><br>
                    <span>No. Revisi</span><br>
                    <span>Reviewed Date</span><br>
                    <span>Next Reviewed</span><br>
                </th>
                <th colspan="3" rowspan="3">
                    <span>: {{ $hiradc->document_number }}</span><br>
                    <span>: {{ $hiradc->effective_date }}</span><br>
                    <span>: {{ $hiradc->revision_number }}</span><br>
                    <span>: {{ $hiradc->reviewed_date }}</span><br>
                    <span>: {{ $hiradc->next_reviewed }}</span><br>
                </th>
                <th class="text-center" style="height: 10">Dibuat :</th>
                <th class="text-center">Diperiksa :</th>
                <th class="text-center">Disetujui :</th>
            </tr>
            <tr>
                <th style="height: 90"></th>
                <th></th>
                <th></th>
            </tr>
            <tr>
                <th></th>
                <th></th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td colspan="2">
                    <span>Dept</span><br>
                    <span>Unit Kerja</span><br>
                    <span>Area</span><br>
                </td>
                <td colspan="14">
                    <span>: {{ $hiradc->dept }}</span><br>
                    <span>: {{ $hiradc->work_unit }}</span><br>
                    <span>: {{ $hiradc->area }}</span><br>
                </td>
            </tr>
            <tr style="background: green;font-weight: 700">
                <td rowspan="2">No</td>
                <td rowspan="2">Aktivitas Kerja</td>
                <td rowspan="2">Identifikasi Bahaya</td>
                <td rowspan="2">Situasi (R/NR/E)</td>
                <td rowspan="2">Aspek (H/S/E)</td>
                <td rowspan="2">Potensi Dampak/ Akibat yang Ditimbulkan</td>
                <td colspan="3">Penilaian resiko</td>
                <td rowspan="2">Pengendalian Saat Ini</td>
                <td colspan="3">Resiko Setelah Pengendalian</td>
                <td class="tg-0lax" rowspan="2">Peraturan Terkait</td>
                <td class="tg-0lax" rowspan="2">Tingkat Resiko</td>
                <td class="tg-0lax" rowspan="2">Pengendalian Lebih Lanjut yang Disyaratkan</td>
            </tr>
            <tr style="background: green;font-weight: 700">
                <td>K</td>
                <td>P</td>
                <td>TNR</td>
                <td>K</td>
                <td>P</td>
                <td>TNR</td>
            </tr>
            @php
                $outerKey = 0;
            @endphp
            @foreach ($lists as $key => $list)
                @if ($list->sub_name)
                    <tr style="background: yellow;font-weight: 700">
                        <td colspan="16">{{ $list->sub_name }}</td>
                    </tr>
                    @php
                        $outerKey = 0; // Reset the counter when sub_name is encountered
                    @endphp
                @endif
                @php
                    $data = json_decode($list->data);
                @endphp
                @foreach ($data as $index => $item)
                    @if ($index === 0)
                        <tr>
                            <td style="vertical-align: middle;" rowspan="{{ count($data) }}">{{ $outerKey + 1 }}</td>
                            <td style="vertical-align: middle;" rowspan="{{ count($data) }}">{{ $list->activity }}
                            </td>
                    @endif
                    <td>{{ $item->threat }}</td>
                    <td>{{ $item->situation }}</td>
                    <td>{{ $item->aspect }}</td>
                    <td>{{ $item->impact }}</td>
                    <td>{{ $item->risk_k }}</td>
                    <td>{{ $item->risk_p }}</td>
                    <td>{{ $item->risk_tnr }}</td>
                    <td>{{ $item->current_control }}</td>
                    <td>{{ $item->risk_k_after }}</td>
                    <td>{{ $item->risk_p_after }}</td>
                    <td>{{ $item->risk_tnr_after }}</td>
                    <td>{{ $item->related_rules }}</td>
                    <td
                        style="background:{{ $item->risk_level == 'Acceptable' ? 'green' : 'yellow' }};font-weight: 700">
                        {{ $item->risk_level }}
                    </td>
                    <td>
                        @if ($item->further_control !== null)
                            {{ $item->further_control }}
                        @endif
                    </td>
                    @if ($index === 0)
                        </tr>
                    @endif
                @endforeach

                @php
                    $outerKey++;
                @endphp
            @endforeach

        </tbody>
    </table>
    <script>
        // window.onload = function() {
        //     window.print();
        // }
    </script>
</body>

</html>
