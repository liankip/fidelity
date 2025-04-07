<!DOCTYPE html>
<html lang="en">

<head>
     <meta charset="UTF-8">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <meta http-equiv="X-UA-Compatible" content="ie=edge">
     <title>Print Meeting</title>
     <style>
          @page {
               size: A4;
          }

          table {
               border-collapse: collapse;
          }

          th,
          td {
               border: 1px solid black;
               padding: 8px;
               text-align: left;
          }

          @media print {
               body {
                    -webkit-print-color-adjust: exact;
               }

               thead {
                    display: table-row-group
               }

               table {
                    page-break-inside: auto;
                    width: 100%;
                    
               }

               tr {
                    page-break-inside: avoid;
                    page-break-after: auto
               }
          }

          .print-container {
               display: flex;
               justify-content: center;
          }

          ol {
               margin-left: 0;
               padding-left: 15px;
          }
     </style>
</head>

<body>
     @php
          \Carbon\Carbon::setLocale('id');
     @endphp
     <img src="/images/sne.png" alt="SNE Logo" width="100">
     <div class="print-container">
          
          <div style="width: 70%; display:flex; justify-content: center; padding-top:100px;">
               <table>
                    <tbody>
                         <tr>
                              <td style="width: 200px; font-weight: bold; padding: 10px">Tanggal</td>
                              <td>{{ \Carbon\Carbon::parse($dataMeeting->meeting_date)->translatedFormat('j F Y') }}
                              </td>
                         </tr>
                         <tr>
                              <td style="width: 200px; font-weight: bold; padding: 10px">Lokasi</td>
                              <td>{{ $dataMeeting->meeting_location }}</td>
                         </tr>
                         <tr>
                              <td style="width: 200px; font-weight: bold; padding: 10px">Daftar Hadir</td>
                              <td>
                                   <div>
                                        <p><strong>Employee:</strong></p>
                                        <ol>
                                             @foreach (json_decode($dataMeeting->meeting_attendant)->employee as $employee)
                                                  <li>{{ $employee }}</li>
                                             @endforeach
                                        </ol>
                                   </div>
                                   @if (!empty(json_decode($dataMeeting->meeting_attendant)->guest))
                                        <div>
                                             <p><strong>Guest:</strong></p>
                                             <ol>
                                                  @foreach (json_decode($dataMeeting->meeting_attendant)->guest as $guest)
                                                       <li>{{ $guest }}</li>
                                                  @endforeach
                                             </ol>
                                        </div>
                                   @endif
                              </td>
                         </tr>
                         <tr>
                              <td style="width: 200px; font-weight: bold; padding: 10px">Notulen Rapat</td>
                              <td>
                                   <ol>
                                        @foreach (json_decode($dataMeeting->meeting_notulen) as $item)
                                             <li>{{ $item }}</li>
                                        @endforeach
                                   </ol>
                              </td>
                         </tr>
                         <tr>
                              <td style="width: 200px; font-weight: bold; padding: 10px">Notulensi</td>
                              <td>{{ $dataMeeting->notulensi }}</td>
                         </tr>
                    </tbody>
               </table>
          </div>
     </div>
     <script>
          window.onload = function() {
               window.print();
          };
     </script>
</body>

</html>
