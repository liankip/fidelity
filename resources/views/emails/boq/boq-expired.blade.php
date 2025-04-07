<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
<p>Proyek-proyek berikut ini memiliki BOQ yang dibuat lebih dari satu bulan yang lalu dan tidak ada permintaan
    pembelian:</p>
<ul>
    @foreach($projectDetails as $project)
        <li><a href="">{{ $project['name'] }}</a></li>
    @endforeach
</ul>
<p>
    Regards, <br>
    {{ config('app.app_name') }}
</p>
</body>
</html>
