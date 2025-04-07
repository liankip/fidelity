<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Inventory Out</title>
</head>

<body>
@php
    use Carbon\Carbon;Carbon::setLocale('id');
@endphp
<p>Email ini menginformasikan barang keluar tanggal
    {{ Carbon::parse($todayDate)->translatedFormat('j F Y') }}. Data terlampir pada attachment.</p>
<p>
    Regards, <br>
    {{ config('app.app_name') }}
</p>
</body>

</html>
