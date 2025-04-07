<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Purchase Order No. {{ $poData->po_no }}</title>
</head>

<body>
<p>Email ini menginformasikan bahwa barang - barang dengan Purchase Order No. <strong>{{ $poData->po_no }}</strong>
    telah tiba. File gambar dapat diakses melalui link berikut : </p>
<p><a href="{{ env('APP_URL') . '/viewphoto_submition/' . $poData->id }}">Lihat foto barang</a>
</p>
<p>
    Regards, <br>
    {{ config('app.app_name') }}
</p>
</body>

</html>
