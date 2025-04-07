<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title></title>
</head>

<body>
<p>Email ini menginformasikan bahwa PO dengan No. <strong>{{ $voucherDetail->purchase_order->po_no }}</strong>
    telah dibayar. Bukti terlampir di attachment email</p>
@if(!$taxStatus)
    <strong style="color: red; font-weight: bold">TAGIH FAKTUR PAJAK</strong>
@endif
<p>
    Regards, <br>
    {{ config('app.app_name') }}
</p>
</body>

</html>
