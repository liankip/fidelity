<!DOCTYPE html>
<html lang="en">

<head>
     <meta charset="UTF-8">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <meta http-equiv="X-UA-Compatible" content="ie=edge">
     <title>JSA Document</title>

     <style>
          body {
               margin: auto;
               padding: 0;
               box-sizing: border-box;
          }

          @page {
               size: A4;
               margin: 0;
          }

          .container {
               padding: 20px;
               box-sizing: border-box;
          }

          table {
               border-collapse: collapse;
               margin: auto;
          }

          th,
          td {
               border: 1px solid black;
               padding: 8px;
               text-align: left;
          }

          @media print {
               thead {
                    display: table-row-group
               }

               table {
                    page-break-inside: auto
               }

               tr {
                    page-break-inside: avoid;
                    page-break-after: auto
               }
          }
     </style>
</head>

<body>
     <div class="container">
          <table>
               <thead>
                    <th style="width: 150px;"><img src="/images/sne.png" alt="" width="100" height="30">
                    </th>
                    <th colspan="7">ANALISIS KESELAMATAN KERJA (JOB SAFETY ANALYSIS)/PROSEDUR JSA</th>
               </thead>
               <tbody>
                    <tr>
                         <td>Nomor dan Nama Pekerjaan</td>
                         <td>
                              <p style="font-size: 10pt;">{{ str_pad($dataJsa->job_no, 2, '0', STR_PAD_LEFT) }}.
                                   {{ $dataJsa->job_name }}</p>
                         </td>
                         <td>Tanggal</td>
                         <td colspan="2">{{ \Carbon\Carbon::parse($dataJsa->jsa_date)->translatedFormat('F Y') }}
                         </td>
                         <td colspan="3">No JSA : {{ $dataJsa->no_jsa }}</td>
                    </tr>
                    <tr>
                         <td>Nomor dan Nama Jabatan</td>
                         <td>{{ $dataJsa->position_no ?? '' }}-{{ $dataJsa->position_name ?? '' }}</td>
                         <td>Disusun Oleh</td>
                         <td style="font-weight: bold">{{ $dataJsa->user->name }}</td>
                         <td>Tanda Tangan</td>
                         <td></td>
                         <td>No Revisi</td>
                         <td>{{ $dataJsa->revision_num ?? 0 }}</td>
                    </tr>
                    <tr>
                         <td>Seksi / Departemen</td>
                         <td>{{ $dataJsa->section_department ?? '-' }}</td>
                         <td>Diperiksa Oleh</td>
                         <td>{{ $dataJsa->checked_by ?? '-' }}</td>
                         <td>Tanda Tangan</td>
                         <td></td>
                         <td>Direview</td>
                         <td>{{ $dataJsa->reviewed ?? '-' }}</td>
                    </tr>
                    <tr>
                         <td>Jabatan Superior</td>
                         <td>{{ $dataJsa->superior_position ?? '-' }}</td>
                         <td>Disetujui Oleh</td>
                         <td>{{ $dataJsa->approved_by ?? '-' }}</td>
                         <td>Tanda Tangan</td>
                         <td></td>
                         <td>Tanda Tangan</td>
                         <td></td>
                    </tr>
                    <tr>
                         <td colspan="3">
                              <h4>Alat pelindung diri yang harus dipakai:</h4>
                              <p>{!! nl2br(e($dataJsa->suggestion_notes)) ?? '-' !!}</p>
                         </td>
                         <td colspan="5">
                              <h4>Lokasi kerja : <span style="font-weight: 500;">{{ $dataJsa->job_location ?? '-' }}
                                   </span></h4>
                         </td>
                    </tr>
                    <tr>
                         <td colspan="2">
                              <h4 style="text-align: center;">Urutan Dasar langkah kerja</h4>
                         </td>
                         <td colspan="2">
                              <h4 style="text-align: center;">Risiko yang terkait</h4>
                         </td>
                         <td colspan="4">
                              <h4 style="text-align: center;">Tindakan atau Prosedur Pencegahan yang
                                   direkomendasikan</h4>
                         </td>
                    </tr>
                    <tr>
                         <td style="border: none;"></td>
                    </tr>

                    <tr>
                         <td colspan="2" style="text-align: center;">Uraikan pekerjaan tersebut menjadi
                              beberapa langkah kerja dasar</td>
                         <td colspan="2" style="text-align: center;">Identifikasi Risiko yang berhubungan
                              dengan tiap-tiap langkah kerja tersebut
                              terhadap kemungkinan terjadinya
                              kecelakaan</td>
                         <td colspan="4" style="text-align: center;">
                              Gunakan kedua kolom tadi sebagai pembimbing, tentukan
                              tindakan apa yang perlu diambil untuk menghilangkan atau
                              memperkecil Risiko yang dapat menimbulkan kecelakaan,
                              cidera atau penyakit akibat kerja
                         </td>
                    </tr>

                    @if (!empty($dataJsa->details_data))
                         @foreach (json_decode($dataJsa->details_data, true) as $detailIndex => $detail)
                              @php $risikoCount = count($detail['risiko']); @endphp
                              @foreach ($detail['risiko'] as $risikoIndex => $risiko)
                                   <tr>
                                        @if ($risikoIndex === 0)
                                             <td rowspan="{{ $risikoCount }}" colspan="2">
                                                  {{ $detailIndex + 1 }}. {{ $detail['urutan'] ?? '-' }}
                                             </td>
                                        @endif
                                        <td colspan="2">
                                             {{ $detailIndex + 1 }}.{{ $risikoIndex + 1 }}
                                             {{ $risiko['risiko_item'] ?? '-' }}
                                        </td>
                                        <td colspan="4">
                                             @foreach ($risiko['tindakan'] as $tindakanIndex => $tindakan)
                                                  <p>
                                                       {{ $detailIndex + 1 }}.{{ $risikoIndex + 1 }}.{{ $tindakanIndex + 1 }}
                                                       {{ $tindakan }}
                                                  </p>
                                             @endforeach
                                        </td>
                                   </tr>
                              @endforeach
                         @endforeach
                    @else
                         <td colspan="8">No details available</td>
                    @endif
               </tbody>
          </table>

     </div>
     <script>
        window.onload = function() {
            window.print(); 
        };
    </script>
</body>

</html>
