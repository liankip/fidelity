<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>

<body>
<p>Email ini menginformasikan bahwa pengajuan payment submission {{ $paymentSubData->type }} tanggal
    <strong>{{ $paymentSubData->created_at->format('d F Y') }}</strong>
    telah dibuat.
</p>
<p>
    Akses payment submission dapat diakses melalui link berikut : <span><a
            href="{{ env('APP_URL') . '/payment-submission-approval' }}">Link
                    submission</a></span>
</p>
<p>
    Regards, <br>
    {{ config('app.app_name') }}
</p>
</body>

</html>
