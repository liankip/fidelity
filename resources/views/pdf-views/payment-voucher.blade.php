<!DOCTYPE html>
<html lang="en">

<head>
     <meta charset="UTF-8">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <meta http-equiv="X-UA-Compatible" content="ie=edge">
     <title></title>
     <style>
          .watermark {
               position: fixed;
               top: 50%;
               left: 50%;
               transform: translate(-50%, -50%);
               opacity: 0.1;
               z-index: 1000;
          }
     </style>
</head>

<body>
     {{-- @if ($terminStatus !== 'Belum Lunas') --}}
     <div class="watermark">
          <img src="{{ public_path('images/lunas-stamp.png') }}" alt="Stamp" width="500px" height="500px">
     </div>
     {{-- @endif --}}
     <div style="display:inline-block;">
          <h2 style="max-width: fit-content;">Purchase Order No. {{ $voucherData->purchase_order->po_no }}</h2>
          <div style="border: solid 1px; max-width: 50%; position: relative; max-height: fit-content;">
               <table style="border: solid 1px; width: 100%; border-collapse: collapse;">
                    <thead>
                         <tr>
                              <th colspan="2" style="border-bottom: solid 1px; text-align: center;">
                                   Status Faktur Pajak
                              </th>
                         </tr>
                    </thead>
                    <tbody>
                         <tr>
                              <td style="width: 50%; border: solid 1px; text-align: center;">
                                   @if ($taxStatus === 'Yes')
                                        Ya
                                   @elseif ($taxStatus === 'No')
                                        -
                                   @else
                                        -
                                   @endif
                              </td>
                              <td style="width: 50%; border: solid 1px; text-align: center;">
                                   @if ($taxStatus === 'Yes')
                                        -
                                   @elseif ($taxStatus === 'No')
                                        Tidak
                                   @else
                                        -
                                   @endif
                              </td>
                         </tr>
                    </tbody>
               </table>
          </div>
          @if ($taxStatus === 'No')
               <strong>Alasan Tidak Ada Faktur Pajak : <span>{{ $voucherData->voucherPayment->tax_notes }}</span></strong>
          @endif
          <h4>Keterangan : {{ $voucherData->voucherPayment->notes ?? '-' }}</h4>
     </div>

     @foreach ($poData as $purchaseOrderId => $voucherDetails)
          @php
               $purchaseOrder = $voucherDetails[0]['purchase_order'];
          @endphp

          @if (count($purchaseOrder['do']) > 0)
               <h3>Surat Jalan {{ $purchaseOrder['po_no'] }}</h3>
               @foreach ($purchaseOrder['do'] as $do)
                    <img src="{{ public_path($do['do_pict']) }}" alt="Surat Jalan"
                         style="max-width: 100%; height: auto;">
               @endforeach
          @endif

          @if (count($purchaseOrder['invoices']) > 0)
               <h3>Invoice</h3>
               @foreach ($purchaseOrder['invoices'] as $invoice)
                    <img src="{{ public_path($invoice['foto_invoice']) }}" alt="Invoice"
                         style="max-width: 50%; max-height: 50%;">
               @endforeach
          @endif

          @if (count($purchaseOrder['submition']) > 0)
               <h3>Foto Barang</h3>
               @foreach ($purchaseOrder['submition'] as $submition)
                    <img src="{{ public_path($submition['foto_barang']) }}" alt="Foto Barang"
                         style="max-width: 200px; max-height: 200px;">
               @endforeach
          @endif

          <h3>Bukti Pembayaran</h3>
          @foreach ($voucherDetails as $detail)
               @php
                    $paymentRelation = \App\Models\Payment::where('voucher_id', $detail['voucher_id'])->first();
               @endphp
               @if ($paymentRelation)
                    <img src="{{ storage_path('app/public/' . $paymentRelation->payment_pict) }}" alt="Bukti Pembayaran"
                         style="max-width: 50%; max-height: 50%;">
               @endif
          @endforeach
     @endforeach

</body>

</html>
