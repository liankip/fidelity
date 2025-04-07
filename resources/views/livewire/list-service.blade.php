<div>
    <h1>Checklist Service</h1>
    <div class="alert alert-warning">
        <strong>
            Berikut merupakan form pembuatan Checklist Service
        </strong>
    </div>
    <hr>
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-2" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    <div class="mb-3">
        <a href="{{ route('checklist.index') }}" class="btn btn-success">Create List</a>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
            Upload file
        </button>
    </div>

    <div class="bg-white p-4" wire:ignore>
        <table class="table table-bordered" id="serviceTable">
            <thead>
                <th>No</th>
                <th>Service Id</th>
                <th>Updated At</th>
                <th>Action</th>
            </thead>
            <tbody>
                @foreach ($dataService as $serviceNo => $data)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $data->first()->service_id ? 'Service ID : ' . $serviceNo : '-' }}</td>
                        <td>{{ $data->first()->updated_at->format('j F Y') }}</td>
                        <td class="d-flex">
                            <a href="{{ route('checklist.index', ['id' => $serviceNo]) }}"
                                class="btn btn-warning">Details</a>
                            <button data-bs-toggle="modal" data-bs-target="#deleteModal" class="btn btn-danger"
                                wire:click="setDelete({{ $serviceNo }})">Delete</button>
                            @if ($data->first()->file_upload !== null)
                                <a href="{{ Storage::url($data->first()->file_upload) }}" target="__blank"
                                    class="btn btn-info">Print</a>
                            @else
                                <a href="{{ route('service.print', ['id' => $serviceNo]) }}"
                                    class="btn btn-info">Print</a>
                            @endif
                        </td>

                    </tr>
                @endforeach

            </tbody>
        </table>

    </div>

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form class="modal-content" method="POST" action="{{ route('service.upload') }}"
                enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>Upload file (PDF)</strong>
                            <span class="text-danger">*</span>
                            <div class="d-flex gap-2">
                                <input type="file" class="form-control" name="file_upload" accept="application/pdf"
                                    required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div>
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
            const dTable = new DataTable('#serviceTable', {
                ordering: false,
            });
        });
    </script>
</div>
