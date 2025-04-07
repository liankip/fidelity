<!DOCTYPE html>
<html lang="en">

<head>
     <meta charset="UTF-8">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <meta http-equiv="X-UA-Compatible" content="ie=edge">
     <title></title>

     <style>
          .project-table {
               width: 100%;
               border-collapse: collapse;
               margin-top: 20px;
          }
          .project-table th,
          .project-table td {
               padding: 10px;
               text-align: left;
               border: 1px solid #ddd;
          }

          .project-table th {
               background-color: #515454;
               color: white;
          }
     </style>
</head>

<body>
     <p>Email ini menginformasikan bahwa pengajuan payment submission tanggal <strong>{{ $paymentSubCreated }}</strong>
          telah di approve.
          <br>
          @foreach ($voucherData as $voucher)
               <br><br>
               <strong>Voucher No. {{ $voucher->voucher_no }} -
                    @if (isset($voucher->additional_informations))
                         (Non PO)
                    @else
                         (PO)
                    @endif
               </strong>
               @foreach ($voucher->voucher_details as $item)
                    <hr>
                    <p>Nomor PO : <a href="{{ env('APP_URL') . '/po-details/' . $item->purchase_order->id }}"
                              class="text-black fw-semibold">{{ $item->purchase_order->po_no }}</a></p>
                    <p>Nomor Rekening :
                    <ul>
                         @isset($item->purchase_order->supplier->bank_name)
                              <li>Bank: {{ $item->purchase_order->supplier->bank_name }}</li>
                         @else
                              <li>Bank: -</li>
                         @endisset

                         @isset($item->purchase_order->supplier->norek)
                              <li>No Rek: {{ $item->purchase_order->supplier->norek }}</li>
                              <li>{{ $item->purchase_order->supplier->name }}</li>
                         @else
                              <li>No Rek: -</li>
                         @endisset
                    </ul>
     </p>
     <p>Faktur Pajak :
          @if ($item->faktur_pajak === 1)
               <span class="badge bg-success">Ada</span>
          @elseif($item->faktur_pajak === 2)
               <span class="badge bg-danger">Tidak Ada</span>
          @elseif($item->faktur_pajak === 3)
               <span class="badge bg-secondary">Belum Ada</span>
          @endif
     </p>
     <p>Nominal : Rp. {{ number_format($item->amount_to_pay ?? 0) }}</p>

     <div style="margin-bottom: 15px;">
          <strong>Surat Jalan</strong>
          <table class="project-table">
               <thead>
                    <tr>
                         <th>Nomor DO</th>  
                         <th>Tanggal</th>
                         <th>Foto Surat Jalan</th>  
                    </tr>
               </thead>
               <tbody>
                    @foreach ($item->purchase_order->do as $do)
                         <tr>
                              <td>{{ $do->do_no }}</td>
                              <td>{{ \Carbon\Carbon::parse($do->created_at)->translatedFormat('j F Y') }}</td>
                              <td>
                                   @php
                                        $imageLink = Str::startsWith($do->do_pict, 'https') ? $do->do_pict : Storage::url($do->do_pict);
                                   @endphp
                                   <a href="{{ $imageLink }}">Lihat Foto Surat Jalan</a>
                              </td>
                         </tr>
                    @endforeach
               </tbody>
          </table>
     </div>

     <div style="margin-bottom: 15px;">
          <strong>Foto Barang</strong>
          <table class="project-table">
               <thead>
                    <tr>
                         <th>Nama Barang</th>
                         <th>Quantity</th>
                         <th>Tanggal</th>
                         <th>Foto Barang</th>
                    </tr>
               </thead>
          <tbody>
          @foreach ($item->purchase_order->submition as $submition)
               <tr>
                    <td>{{ $submition->item_name }}</td>
                    <td>{{ $submition->qty }}</td>
                    <td>{{ \Carbon\Carbon::parse($submition->created_at)->translatedFormat('j F Y') }}</td>
                    <td>
                         @php
                              $imageLink = Str::startsWith($submition->foto_barang, 'https') ? $submition->foto_barang : Storage::url($submition->foto_barang);
                         @endphp
                         <a href="{{ $imageLink }}">Lihat Foto Barang</a>
                    </td>
               </tr>
          @endforeach
     </tbody>
     </table>
     </div>

     <div>
          <strong>Invoice</strong>
          <table class="project-table">
               <thead>
                    <tr>
                         <th>Tanggal</th>
                         <th>Foto Invoice</th>
                    </tr>
               </thead>

               <tbody>
                    @foreach ($item->purchase_order->invoices as $invoice)
                         <tr>
                              <td>{{ \Carbon\Carbon::parse($invoice->created_at)->translatedFormat('j F Y') }}</td>
                              <td>
                                   @php
                                        $imageLink = Str::startsWith($invoice->foto_invoice, 'https') ? $invoice->foto_invoice : Storage::url($invoice->foto_invoice);
                                   @endphp
                                   <a href="{{ $imageLink }}">Lihat Foto Invoice</a>
                              </td>
                         </tr>
                    @endforeach
               </tbody>
          </table>
    </div>
     <hr>
     @endforeach
     @if (isset($voucher->additional_informations))
          @php
               $additionalInformations = json_decode($voucher->additional_informations, true);
          @endphp
          @foreach ($additionalInformations as $data)
               <hr>
               <p>Keterangan PO : <strong>{{ $data['keterangan'] }}</strong></p>
               <p>Nomor Rekening : {{ $data['bank_penerima'] }}</p>
               <p>Nominal : {{ rupiah_format($data['total']) }}</p>
               <hr>
          @endforeach
     @endif
     @endforeach
     <p>
          Akses payment submission dapat diakses melalui link berikut : <span><a
                    href="{{ env('APP_URL') . '/payment-submission' }}">Link
                    submission</a></span></p>
     </p>
     <p>
          Regards, <br>
          {{ config('app.app_name') }}
     </p>
</body>

</html>
