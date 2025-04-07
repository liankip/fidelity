<div>
    <h4>Attendance: {{ date('d F Y', strtotime($selectedDate)) }}</h4>
    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-dismissible fade show mb-2" role="alert">
            {{ $message }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <hr>

    <div class="row g-3 align-items-center">
        <div class="col-auto">
            <label for="inputPassword6" class="col-form-label">Select Date</label>
        </div>
        <div class="col-auto">
            <input class="form-control" type="date" wire:model="selectedDate">
        </div>
    </div>
    <div class="card mt-5">
        <div class="card-body p-5">
            <div class="input-group mb-3">
                <input type="text" class="form-control" wire:model.debounce.500ms="search" name="search"
                    placeholder="Search" value="" aria-label="Recipient's username"
                    aria-describedby="button-addon2">
            </div>

            <div class="mt-3">
                <table class="table table-borderless table-striped mt-3">
                    <thead>
                        <tr class="table-primary">
                            <th class="text-center" style="width: 5%;">No</th>
                            <th style="width: 25%;">Name</th>
                            <th style="width: 15%;">Status</th>
                            <th style="width: 15%;">Checkin Time</th>
                            <th style="width: 15%;">Latitude</th>
                            <th style="width: 15%;">Longitude</th>
                            <th style="width: 10%;">Map Location</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($attendances as $attendance)
                            @php
                                $status = '';
                                if ($attendance['status'] == 'ontime') {
                                    $status = 'badge bg-success text-white';
                                } elseif ($attendance['status'] == 'late') {
                                    $status = 'badge bg-warning text-black';
                                } elseif ($attendance['status'] == 'permission') {
                                    $status = 'badge bg-primary text-white';
                                } else {
                                    $status = 'badge bg-danger text-white';
                                }
                            @endphp
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td>{{ $attendance['employee']['name'] }}</td>
                                <td><span class="{{ $status }}">{{ ucfirst($attendance['status']) }}</span></td>
                                <td style="font-family: monospace;">{{ $attendance['check_in_time'] }}</td>
                                @if ($attendance['latitude'])
                                    <td style="font-family: monospace;">{{ $attendance['latitude'] }}</td>
                                    <td style="font-family: monospace;">{{ $attendance['longitude'] }}</td>
                                    <td><a href="{{ $attendance['map_url'] }}" target="_BLANK">View</a></td>
                                @else
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                @endif
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">No Data</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
