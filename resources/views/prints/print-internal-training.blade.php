<!DOCTYPE html>
<html lang="en">

<head>
     <meta charset="UTF-8">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <meta http-equiv="X-UA-Compatible" content="ie=edge">
     <title>Print Internal Training</title>
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
               height: 100vh;
               display: flex;
               flex-direction: column;
               
               align-items: center;
          }

          table {
               border-collapse: collapse;
               margin: auto;
          }

          th,
          td {
               border: 1px solid black;
               padding: 8px;
               text-align: center;
          }

          .header-row {
               font-weight: bold;
               text-align: center;
          }

          @media print {
               body {
                    -webkit-print-color-adjust: exact;
               }


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
     <div class="print-container">
          <div>
               <table style="width: 1000px; margin-top: 25px;">
                    <tr class="header-row">
                         <td rowspan="4">JADWAL PELATIHAN INTERNAL</td>
                    </tr>
                    <tr>
                         <td style="text-align: left">No. Doc</td>
                         <td></td>
                    </tr>
                    <tr>
                         <td style="text-align: left">Revisi</td>
                         <td></td>
                    </tr>
                    <tr>
                         <td style="text-align: left">Halaman</td>
                         <td class="page-number" style="text-align: left"></td>
                    </tr>
               </table>
               <small>Tahun: 2024</small>
          </div>

          <div style="margin-top: 25px">
               <table>
                    <thead style="background-color: rgba(94, 177, 237, 0.118)">
                         <tr>
                              <th rowspan="2">No</th>
                              <th rowspan="2">Aspek / bahaya / masalah</th>
                              <th rowspan="2">Dampak & Risiko</th>
                              <th rowspan="2">Rencana Program</th>
                              <th colspan="2">Rencana & Realisasi Program</th>
                              <th rowspan="2">Keterangan</th>
                         </tr>
                         <tr>
                              <th>Rencana</th>
                              <th>Realisasi</th>
                         </tr>
                    </thead>

                    <tbody>
                         @foreach ($dataTraining as $data)
                              <tr>
                                   <td>{{ $loop->iteration }}</td>
                                   <td>{{ $data->aspect_name }}</td>
                                   <td>{{ $data->risk_effect }}</td>
                                   <td>{{ $data->program_plan }}</td>
                                   <td>{{ $data->plan }}</td>
                                   <td>{{ $data->realization }}</td>
                                   <td>{{ $data->notes }}</td>
                              </tr>
                         @endforeach
                    </tbody>
               </table>
          </div>

          

          <div style="margin-top: 25px">
               <table style="width: 500px">
                    <tr>
                         <td>Di buat oleh :</td>
                         <td>Di setujui oleh :</td>
                    </tr>
                    <tr style="height: 100px">
                         <td></td>
                         <td></td>
                    </tr>
                    <tr>
                         <td style="text-align: left">Nama : {{$userData->name}}</td>
                         <td style="text-align: left">Nama :</td>
                    </tr>
                    <tr>
                         <td style="text-align: left">Jabatan : {{$userData->position}}</td>
                         <td style="text-align: left">Jabatan :</td>
                    </tr>
               </table>
          </div>
     </div>

     <script>
          window.onload = function() {
               var totalPages = window.document.querySelectorAll('.page-number').length;
               var pageNumbers = document.querySelectorAll('.page-number');
               for (var i = 0; i < pageNumbers.length; i++) {
                    pageNumbers[i].textContent = (i + 1) + ' dari ' + totalPages;
               }
               window.print(); 
          };
     </script>
</body>

</html>
