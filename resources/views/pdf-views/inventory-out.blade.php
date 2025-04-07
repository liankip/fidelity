<!DOCTYPE html>
<html lang="en">

<head>
     <meta charset="UTF-8">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <meta http-equiv="X-UA-Compatible" content="ie=edge">
     <title>Inventory Out</title>
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

          img {
               display: block;
               margin: 0 auto;
               max-width: 100px;
               max-height: 100px;
          }

          #imageLogo{
               width: 100px;
               height: 50px;
          }
     </style>
</head>

<body>
     <div style="margin-bottom: 10px;">
          <img src="{{ public_path('images/sne.png') }}" alt="Logo" id="imageLogo">
     </div>
     @php
          \Carbon\Carbon::setLocale('id');
     @endphp
     <h3>Barang Keluar {{ \Carbon\Carbon::parse($date)->translatedFormat('j F Y') }}</h3>
     <br>
     <table>
          <tbody>
              <tr>
                  <th class="text-center" style="border-color: #c8c8c8; width: 5%">No</th>
                  <th class="text-center" style="border-color: #c8c8c8;">Nama Project</th>
                  <th class="text-center" style="border-color: #c8c8c8;">Nama Item</th>
                  <th class="text-center" style="border-color: #c8c8c8;">Stok Tersedia</th>
                  <th class="text-center" style="border-color: #c8c8c8;">Stok Keluar</th>
                  <th class="text-center" style="border-color: #c8c8c8;">Stok Sisa</th>
                  <th class="text-center" style="border-color: #c8c8c8;">Catatan</th>
              </tr>
              @foreach ($inventoryData as $projectId => $items)
                  @php
                      $project = \App\Models\Project::find($projectId);
                      $projectName = $project ? $project->name : 'Unknown Project';
                  @endphp
                  @foreach ($items as $index => $item)
                      <tr>
                           @if ($loop->first)
                           <td class="text-center" style="border-color: #c8c8c8;" rowspan="{{ $items->count() }}">{{ $loop->parent->iteration }}</td>
                              <td class="text-center" style="border-color: #c8c8c8;" rowspan="{{ $items->count() }}">{{ $projectName }}</td>
                          @endif
                          <td class="text-center" style="border-color: #c8c8c8;">{{ $item->itemName }}</td>
                          <td class="text-center" style="border-color: #c8c8c8;">{{ number_format($item->totalstock) }}</td>
                          <td class="text-center" style="border-color: #c8c8c8;">
                              @if ($item->todayOut !== null && $item->todayOut > 0)
                                  {{ $item->todayOut }}
                              @else
                                  -
                              @endif
                          </td>
                          <td class="text-center" style="border-color: #c8c8c8;">{{ $item->remainingStock }}</td>
                          <td class="text-center" style="border-color: #c8c8c8;">{{ $item->note }}</td>
                      </tr>
                  @endforeach
              @endforeach
          </tbody>
      </table>
      
</table>
</body>

</html>
