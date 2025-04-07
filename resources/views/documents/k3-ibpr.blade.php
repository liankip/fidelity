<html>

<head>
    <meta charset="utf-8">
    <title>K3-ibpr</title>
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
            font-weight: bold !important;
        }

        ul {
            list-style: none;
        }

        ul li {
            margin-bottom: 20px;
        }

        .tg {
            border-collapse: collapse;
            border-spacing: 0;
        }

        .tg td {
            border-color: black;
            border-style: solid;
            border-width: 1px;
            font-family: Arial, sans-serif;
            font-size: 14px;
            overflow: hidden;
            padding: 10px 5px;
            word-break: normal;
        }

        .tg th {
            border-color: black;
            border-style: solid;
            border-width: 1px;
            font-family: Arial, sans-serif;
            font-size: 14px;
            font-weight: normal;
            overflow: hidden;
            padding: 10px 5px;
            word-break: normal;
        }

        .tg .tg-c3ow {
            border-color: inherit;
            text-align: center;
            vertical-align: middle;
            font-weight: bold;
        }

        .tg .tg-0pky {
            border-color: inherit;
            text-align: center;
            vertical-align: middle;
        }

        .green-bg {
            background: green;
            color: white
        }
    </style>
</head>

<body class="container-fluid p-2">

    <table class="tg">
        <thead>
            <tr class="green-bg">
                <th class="tg-0pky" colspan="2" rowspan="3">
                    <img src="{{ asset('images/sne.png') }}" width="150" alt="">
                </th>
                <th class="tg-c3ow" colspan="21" rowspan="3">
                    Identifikasi Bahaya, Penilaian Risiko dan Penentuan Kontrol <br>
                    HIRADC (Hazardous Identification, Risk Assesment, and Determining Control)
                </th>
                <th class="" rowspan="3" colspan="2">
                    <span>No. Dok</span><br>
                    <span>No. Rev</span><br>
                    <span>Tanggal Berlaku</span><br>
                    <span>Halaman</span><br>
                </th>
                <th class="" rowspan="3" colspan="2">
                    <span>{{ $ibpr->document_number }}</span><br>
                    <span>{{ $ibpr->revision_number }}</span><br>
                    <span>{{ $ibpr->effective_date }}</span><br>
                    <span>{{ $ibpr->page }}</span><br>
                </th>
            </tr>
            <tr>
            </tr>
            <tr>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="" colspan="2">
                    <span>Dept.</span><br>
                    <span>Unit Kerja</span><br>
                    <span>Area</span><br>
                </td>
                <td class="">
                    <span>{{ $ibpr->dept }}</span><br>
                    <span>{{ $ibpr->work_unit }}</span><br>
                    <span>{{ $ibpr->area }}</span><br>
                </td>
                <td class="" colspan="6"></td>
                <td class="" colspan=2>
                    <span>Reviewed Date</span><br>
                    <span>Next Reviewed Date</span><br>
                </td>
                <td class="" colspan="16">
                    <span>{{ $ibpr->reviewed_date }}</span><br>
                    <span>{{ $ibpr->next_reviewed }}</span><br>
                </td>
            </tr>
            <tr style="background: darkblue;color:white">
                <td class="tg-c3ow" colspan="6" rowspan="2">IDENTIFIKASI POTENSI BAHAYA/ ASPEK-DAMPAK LINGKUNGAN
                </td>
                <td class="tg-c3ow" colspan="9" rowspan="2">PENILAIAN RISIKO/ EVALUASI DAMPAK LINGKUNGAN</td>
                <td class="tg-c3ow" rowspan="4">Peraturan K3/Lingkungan Terkat</td>
                <td class="tg-c3ow" colspan="8">PENETAPAN PENGENDALIAN</td>
                <td class="tg-c3ow" colspan="3" rowspan="2">MONITORING &amp; REVIEW DAMPAK LINGKUNGAN</td>
            </tr>
            <tr style="background: darkblue;color:white">
                <td class="tg-c3ow" colspan="8">RENCANA IMPLEMENTASI PENGENDALIAN RISIKO/DAMPAK LINGKUNGAN</td>
            </tr>
            <tr style="background: aqua;color:black">
                <td class="tg-c3ow" rowspan="2">No</td>
                <td class="tg-c3ow" rowspan="2">Aktivitas/Proses</td>
                <td class="tg-c3ow" rowspan="2">Potensi Bahaya/Aspek</td>
                <td class="tg-c3ow" rowspan="2">S/H/E</td>
                <td class="tg-c3ow" rowspan="2">Risiko/Dampak</td>
                <td class="tg-c3ow" rowspan="2">Kondisi <br>(R/NR/N/A N EM)</td>
                <td class="tg-c3ow" colspan="3">Tingkat<br>Risiko awal</td>
                <td class="tg-c3ow" rowspan="2">Tingkat Risiko<br>Awal</td>
                <td class="tg-c3ow" rowspan="2">Pengendalian Saat Ini</td>
                <td class="tg-c3ow" colspan="3">Sisa Resiko</td>
                <td class="tg-c3ow" rowspan="2">Tingkat Sisa Resiko</td>
                <td class="tg-c3ow" rowspan="2">Eliminasi</td>
                <td class="tg-c3ow" rowspan="2">Substitusi</td>
                <td class="tg-c3ow" rowspan="2">Pengendalian<br>Teknis/<br>Rekayasa<br>Engineering</td>
                <td class="tg-c3ow" rowspan="2">Rambu/<br>Peringatan/<br>Pengendalian<br>Administratif</td>
                <td class="tg-c3ow" rowspan="2">Penggunaan <br>APD</td>
                <td class="tg-c3ow" rowspan="2">PIC</td>
                <td class="tg-c3ow" rowspan="2">Status<br>(Ya/Tidak)</td>
                <td class="tg-c3ow" rowspan="2">Target <br>Penyelesaian</td>
                <td class="tg-c3ow" rowspan="2">Efektif<br>Minimalkan<br>Risiko/Dampak<br>Lingkungan</td>
                <td class="tg-c3ow" rowspan="2">Menimbulkan<br>Risiko<br>Baru/Dampak<br>Lingkungan</td>
                <td class="tg-c3ow" rowspan="2">Tindakan<br>Monitoring</td>
            </tr>
            <tr style="background: aqua;">
                <td class="tg-c3ow">L</td>
                <td class="tg-c3ow">S</td>
                <td class="tg-c3ow">RFN</td>
                <td class="tg-c3ow">L</td>
                <td class="tg-c3ow">S</td>
                <td class="tg-c3ow">RFN</td>
            </tr>
            @foreach ($lists as $key => $list)
                @php
                    $data = json_decode($list->data);
                @endphp
                @foreach ($data as $index => $item)
                    @if ($index === 0)
                        <tr>
                            <td style="vertical-align: middle;" rowspan="{{ count($data) }}">{{ $key + 1 }}
                            </td>
                            <td style="vertical-align: middle;" rowspan="{{ count($data) }}">{{ $list->activity }}
                            </td>
                    @endif
                    <td class="tg-0pky">
                        {{ $item->threat }}
                    </td>
                    <td class="tg-0pky">{{ $item->situation }}</td>
                    <td class="tg-0pky">{{ $item->risk }}</td>
                    <td class="tg-0pky">{{ $item->condition }}</td>
                    <td class="tg-0pky">{{ $item->risk_l }}</td>
                    <td class="tg-0pky">{{ $item->risk_s }}</td>
                    <td class="tg-0pky">{{ $item->risk_rfn }}</td>
                    @if ($item->risk_level == 'Trivial')
                        <td class="tg-0pky" style="background: blue">
                            {{ $item->risk_level }}
                        </td>
                    @elseif($item->risk_level == 'Moderate')
                        <td class="tg-0pky" style="background: yellow">
                            {{ $item->risk_level }}
                        </td>
                    @elseif($item->risk_level == 'Acceptable')
                        <td class="tg-0pky" style="background: green">
                            {{ $item->risk_level }}
                        </td>
                    @elseif($item->risk_level == 'Substantial')
                        <td class="tg-0pky" style="background: purple">
                            {{ $item->risk_level }}
                        </td>
                    @endif
                    <td class="tg-0pky">{{ $item->current_control }}</td>
                    <td class="tg-0pky">{{ $item->risk_l_left }}</td>
                    <td class="tg-0pky">{{ $item->risk_s_left }}</td>
                    <td class="tg-0pky">{{ $item->risk_rfn_left }}</td>
                    @if ($item->risk_level_left == 'Trivial')
                        <td class="tg-0pky" style="background: blue">
                            {{ $item->risk_level_left }}
                        </td>
                    @elseif($item->risk_level_left == 'Moderate')
                        <td class="tg-0pky" style="background: yellow">
                            {{ $item->risk_level_left }}
                        </td>
                    @elseif($item->risk_level_left == 'Acceptable')
                        <td class="tg-0pky" style="background: green">
                            {{ $item->risk_level_left }}
                        </td>
                    @elseif($item->risk_level_left == 'Substantial')
                        <td class="tg-0pky" style="background: purple">
                            {{ $item->risk_level_left }}
                        </td>
                    @endif
                    <td class="tg-0pky">{{ $item->related_rules }}</td>
                    <td class="tg-0pky">
                        {{ isset($item->elimination) && $item->elimination !== '' ? $item->elimination : '-' }}</td>
                    <td class="tg-0pky">
                        {{ isset($item->substitution) && $item->substitution !== '' ? $item->substitution : '-' }}</td>
                    <td class="tg-0pky">
                        {{ isset($item->technical_control) && $item->technical_control !== '' ? $item->technical_control : '-' }}
                    </td>
                    <td class="tg-0pky">
                        {{ isset($item->warning_control) && $item->warning_control !== '' ? $item->warning_control : '-' }}
                    </td>
                    <td class="tg-0pky">
                        {{ isset($item->apd_usage) && $item->apd_usage !== '' ? $item->apd_usage : '-' }}</td>
                    <td class="tg-0pky">{{ isset($item->pic) && $item->pic !== '' ? $item->pic : '-' }}</td>
                    <td class="tg-0pky">{{ isset($item->status) && $item->status !== '' ? $item->status : '-' }}</td>
                    <td class="tg-0pky">
                        {{ isset($item->target_achievement) && $item->target_achievement !== '' ? $item->target_achievement : '-' }}
                    </td>
                    <td class="tg-0pky">
                        {{ isset($item->min_effective) && $item->min_effective !== '' ? $item->min_effective : '-' }}
                    </td>
                    <td class="tg-0pky">{{ isset($item->new_risk) && $item->new_risk !== '' ? $item->new_risk : '-' }}
                    </td>
                    <td class="tg-0pky">
                        {{ isset($item->monitoring) && $item->monitoring !== '' ? $item->monitoring : '-' }}</td>
                    </tr>
                @endforeach
            @endforeach
        </tbody>
    </table>
    </table>
    <script>
        // window.onload = function() {
        //     window.print();
        // }
    </script>
</body>

</html>
