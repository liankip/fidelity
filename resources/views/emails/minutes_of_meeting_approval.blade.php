@php use Carbon\Carbon; @endphp
    <!DOCTYPE html>
<html>
<head>
    <title>MoM Project {{ $meeting->project->name }} "{{ $meeting->meeting_title ?? 'Meeting Title' }}
        " {{ Carbon::parse($meeting->date)->format('d M Y') }}</title>
</head>
<body>
<h2>MoM Project: {{ $meeting->project->name }} "{{ $meeting->meeting_title ?? 'Meeting Title' }}
    " {{ Carbon::parse($meeting->date)->format('d M Y') }}</h2>
<p>Berikut terlampir MoM Project: {{ $meeting->project->name }}
    "<strong>{{ $meeting->meeting_title ?? 'Meeting Title' }}</strong>"
    Tanggal
    <strong>{{ Carbon::parse($meeting->date)->format('d M Y') }}</strong></p>

<p>*tidak ada sanggahan dalam 1x24 jam maka MOM ini berlaku</p>
</body>
</html>
