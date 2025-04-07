<!DOCTYPE html>
<html lang="en">

<head>
     <meta charset="UTF-8">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <meta http-equiv="X-UA-Compatible" content="ie=edge">
     <title>Print Service</title>
     <style>
          @page {
               size: A4 landscape;
               margin: 0;
          }

          body {
               font-family: Arial, sans-serif;
               margin: 0;
          }

          .print-container {
               width: 100vw;
               display: flex;
               flex-direction: column;
               align-items: center;
          }

          table {
               border-collapse: collapse;
               margin: auto;
               margin-top: 50px;
               max-width: 500px;
          }

          th,
          td {
               border: 1px solid black;
               padding: 8px;
               text-align: center;
          }

          @media print {
               body {
                    -webkit-print-color-adjust: exact;
               }


               thead {
                    display: table-row-group
               }

               table, #signature {
                    page-break-inside: auto
               }

               tr, #signature {
                    page-break-inside: avoid;
                    page-break-after: auto
               }
          
          }

     </style>
</head>

<body>
     <div class="print-container">
          <div style="margin-top: 50px; display: flex; height: fit-content;">
               <div>
                    <img src="/images/sne.png" alt="" width="150" height="50">
                    <p style="font-size: 11pt; font-weight: bold">PT. Satria Nusa Enjinering</p>
               </div>
               <div style="width: 500px; text-align: center">
                    <h3 style="text-decoration: underline">PT. SATRIA NUSA ENJINERING</h3>
                    <p style="margin-top: -15px; font-size: 10pt;">Jl. R.A Kartini II No. 11, Madras Hulu. Kecamatan
                         Medan Polonia, <br>Kota Medan, Sumatera Utara 20151 <br>No. Telp : +62 813 7147 0606, E-mail :
                         Sales@Satrianusa..group</p>
               </div>
          </div>
          <div style="width: 700px; height: 1px; background-color: black"></div>

          <table>
               <thead style="background-color: #fff7a5">
                    <tr>
                         <th rowspan="2">No</th>
                         <th rowspan="2">Nomor plat kendaraan</th>
                         <th rowspan="2">Jenis Service</th>
                         <th colspan="12">Checklist (Bulan)</th>
                    </tr>
                    <tr>
                        <th>Januari</th>
                        <th>Februari</th>
                        <th>Maret</th>
                         <th>April</th>
                         <th>Mei</th>
                         <th>Juni</th>
                         <th>Juli</th>
                         <th>Agustus</th>
                         <th>September</th>
                         <th>Oktober</th>
                         <th>November</th>
                         <th>Desember</th>
                    </tr>
               </thead>
               <tbody>
                    @foreach ($dataService as $index => $service)
                         <tr>
                              <td>{{ $index + 1 }}</td>
                              <td style="text-align: left">
                                <p style="font-weight: bold; font-size: 10pt;">{{ $service->vehicle_name }}</p>
                                <p style="font-size: 11pt;">{{ $service->vehicle_no }}</p>
                              </td>
                              <td style="font-size: 11pt; background-color: #52adfc;">{{ $service->service_type }}</td>
                              @foreach ($months as $month)
                                   @php
                                        $monthlyService = json_decode($service->monthly_service, true);
                                        $isChecked = isset($monthlyService[$month]) && $monthlyService[$month];
                                   @endphp
                                   <td style="background-color: {{ $isChecked ? '#fff7a5' : 'transparent' }}">
                                   </td>
                              @endforeach
                         </tr>
                    @endforeach

               </tbody>
          </table>

          <div style="display: flex; justify-content: space-between; width: 700px; margin-top:50px;" id="signature">
            <div style="text-align: center;">
                <p style="font-size: 11pt;">Dibuat Oleh,</p>
                <br><br><br><br><br>
                <p style="font-weight: bold; text-align:center; text-decoration: underline">(&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)</p>
            </div>

            <div style="text-align: center;">
                <p style="font-size: 11pt;">Disetujui Oleh,</p>
                <br><br><br><br><br>
                <p style="font-weight: bold; text-align:center; text-decoration: underline">(&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)</p>
            </div>
          </div>

     </div>

     <script>
        window.onload = function() {
             window.print(); 
        };
   </script>

</body>

</html>
