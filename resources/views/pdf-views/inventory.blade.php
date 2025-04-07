<!DOCTYPE html>
<html lang="en">

<head>
     <meta charset="UTF-8">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <meta http-equiv="X-UA-Compatible" content="ie=edge">
     <title>Inventory</title>
     <style>
          body {
               position: relative;
               font-family: Arial, sans-serif;
          }

          h3 {
               background-color: black;
               color: white;
               text-align: center;
          }

          table {
               width: 100%;
               border-collapse: collapse;
               margin: 20px 0;
          }

          th,
          td {
               padding: 10px;
               text-align: center;
               border: 1px solid #ddd;
          }

          th {
               font-size: 12pt;
          }

          td {
               font-size: 10pt;
          }

          th {
               background-color: #f2f2f2;
          }

          /* img {
               display: block;
               margin: 0 auto;
               max-width: 100px;
               max-height: 100px;
          }

          #imageLogo{
               width: 100px;
               height: 50px;
          } */
     </style>
</head>

<body>
     {{-- <div style="margin-bottom: 10px;">
          <img src="{{ public_path('images/sne.png') }}" alt="Logo" id="imageLogo">
     </div> --}}
     @php
          \Carbon\Carbon::setLocale('id');
     @endphp
     <h3>Inventory {{ \Carbon\Carbon::parse(\Carbon\Carbon::now())->translatedFormat('j F Y') }}</h3>
     <br>
     <table>
          <tbody>
               <tr>
                    <th class="text-center" style="border-color: #c8c8c8; width: 5%">No</th>
                    <th class="text-center" style="border-color: #c8c8c8;">Nama Project</th>
                    <th class="text-center" style="border-color: #c8c8c8;">Nama Task</th>
                    <th class="text-center" style="border-color: #c8c8c8;">Nama Item</th>
                    <th class="text-center" style="border-color: #c8c8c8;">Stok</th>
                    <th class="text-center" style="border-color: #c8c8c8;">Lokasi</th>
                    <th class="text-center" style="border-color: #c8c8c8;">Stok Lapangan</th>
               </tr>

               @foreach ($inventoryData as $projectName => $items)
                    @foreach ($items as $index => $item)
                         <tr>
                              @if ($loop->first)
                                   <td class="text-center" rowspan="{{ $items->count() }}">
                                        {{ $loop->parent->iteration }}</td>
                                   <td class="text-center" rowspan="{{ $items->count() }}">{{ $projectName }}</td>
                              @endif

                              <td class="text-center">{{ substr($item->oldTask->task_number, -2) }}
                                   - {{ $item->oldTask->task }}</td>
                              <td class="text-center">{{ $item->item->name }}</td>
                              <td class="text-center">{{ $item->stock }}</td>
                              <td class="text-center">
                                   @if ($item->new_task_id)
                                        {{ substr($item->newTask->task_number, -2) . ' - ' . $item->newTask->task }} (Relokasi)
                                   @else
                                        -
                                   @endif
                              </td>
                              <td class="text-center">{{ $item->actual_qty }}</td>
                         </tr>
                    @endforeach
               @endforeach
          </tbody>
     </table>

     </table>
</body>

</html>
