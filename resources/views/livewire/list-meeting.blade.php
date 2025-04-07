<div>
    <h1>Meeting Form</h1>
    <div class="alert alert-warning">
        <strong>
            Berikut merupakan form pembuatan Meeting Form
        </strong>
    </div>
    <hr>
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-2" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @elseif (session('fail'))
        <div class="alert alert-danger alert-dismissible fade show mb-2" role="alert">
            {{ session('fail') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    <div class="mb-3">
        <a href="{{ route('meeting.create') }}" class="btn btn-success">Create List</a>
    </div>

    <div class="bg-white p-4" wire:ignore>
        <table class="table table-bordered" id="meetingTable">
            <thead>
                <th>No</th>
                <th>Meeting Date</th>
                <th>Meeting Location</th>
                <th>Action</th>
            </thead>
            <tbody>
                @foreach ($dataMeeting as $data)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $data->meeting_date }}</td>
                        <td>{{ $data->meeting_location }}</td>
                        <td class="d-flex">
                            <a href="{{ route('meeting.create', ['id' => $data->id]) }}"
                                class="btn btn-warning">Details</a>
                            <button data-bs-toggle="modal" data-bs-target="#deleteModal" class="btn btn-danger"
                                wire:click="setDelete({{ $data->id }})">Delete</button>
                            <a href="{{ route('meeting.print', ['id' => $data->id]) }}" class="btn btn-info">Print</a>
                        </td>
                    </tr>
                @endforeach

            </tbody>
        </table>

    </div>

    <!-- Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true"
        wire:ignore.self>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h5>Apakah Anda yakin ingin menghapus data ?</h5>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tidak</button>
                    <button type="submit" class="btn btn-primary" wire:click="handleDelete({{ $deleteId }})"
                        wire:loading.attr="disabled">Ya</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            const dTable = new DataTable('#meetingTable', {
                ordering: false,
            });
        });
    </script>
</div>
