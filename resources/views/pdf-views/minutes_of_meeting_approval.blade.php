@php use Carbon\Carbon;use Illuminate\Support\Facades\Storage; @endphp
    <!DOCTYPE html>
<html>
<head>
    <title>Minutes of Meeting</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table, th, td {
            border: 1px solid black;
        }

        th, td {
            text-align: center;
            padding: 8px;
        }

        .header {
            text-align: center;
            font-weight: bold;
        }

        .section-title {
            margin: 10px 0;
            font-size: 16px;
            font-weight: bold;
            text-align: left;
        }

        .img-responsive {
            max-width: 100px;
            height: auto;
        }

        .table-header {
            background-color: #f2f2f2;
            font-weight: bold;
        }
    </style>
</head>
<body>

<table>
    <thead>
    <tr>
        <td style="width: 20%;">
            <img src="https://storage.googleapis.com/fidelity-assets/logo/SNE_Logo.png" alt="SNE Logo" width="100">
        </td>
        <td class="header" colspan="3" style="width: 60%;">
            Minutes Of Meeting {{ Carbon::parse($meeting->date)->translatedFormat('l, j F Y') }}
            {{ $meeting->meeting_title }}
        </td>
    </tr>
    <tr class="table-header">
        <th style="width: 5%;">No</th>
        <th style="width: 40%;">Point</th>
        <th style="width: 40%;">Remarks</th>
        <th style="width: 15%;">Photo</th>
    </tr>
    </thead>
    <tbody>
    @forelse ($meeting->points as $index => $point)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $point->poin }}</td>
            <td>{{ $point->remarks }}</td>
            <td><img src="{{ $point->photo }}" alt="Point Photo" class="img-responsive"></td>
        </tr>
    @empty
        <tr>
            <td colspan="4">No Points</td>
        </tr>
    @endforelse
    </tbody>
</table>

<div class="section-title">Attendance</div>
<table>
    <thead>
    <tr class="table-header">
        <th style="width: 5%;">No</th>
        <th style="width: 30%;">Name</th>
        <th style="width: 30%;">Email</th>
        <th style="width: 35%;">Signature</th>
    </tr>
    </thead>
    <tbody>
    @forelse ($meeting->participants as $index => $attendance)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $attendance->name }}</td>
            <td>{{ $attendance->email }}</td>
            <td><img src="{{ $attendance->signature }}" alt="Signature" class="img-responsive"></td>
        </tr>
    @empty
        <tr>
            <td colspan="4">No Attendance</td>
        </tr>
    @endforelse
    </tbody>
</table>

</body>
</html>
