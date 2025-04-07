<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>BOQ Items</title>
</head>
<body>
<p>Email ini menginformasikan seluruh barang BOQ pada project <strong>{{ $projectData->name }}</strong> telah dibeli
    seluruhnya.</p>
<p>
    Regards, <br>
    {{ config('app.app_name') }}
</p>
</body>
</html>
