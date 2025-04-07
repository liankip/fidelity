<div>
    <h1>JSA (Job Safety Analysis)</h1>
    <div class="alert alert-warning"><strong>Berikut merupakan form pembuatan JSA (Job Safety Analysis)</strong></div>
    <hr>
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-2" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="p-4 bg-white" wire:ignore>
        <a href="{{ route('jsa.index') }}" class="btn btn-success mb-5">Create New JSA</a>
        <table class="table table-bordered" id="jsaTable">
            <thead>
                <tr>
                    <th>No</th>
                    <th>No JSA</th>
                    <th>Job Name</th>
                    <th>Position Name</th>
                    <th>Section/Department</th>
                    <th>JSA Date</th>
                    <th>Job Location</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($jsaData as $jsa)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td><a href="{{ route('jsa.index', ['id' => $jsa->id]) }}">{{ $jsa->no_jsa }}</a>
                        </td>
                        <td>{{ $jsa->job_name }}</td>
                        <td>{{ $jsa->position_name ?? '-' }}</td>
                        <td>{{ $jsa->section_department ?? '-' }}</td>
                        <td>{{ \Carbon\Carbon::parse($jsa->jsa_date)->format('d F Y') }}</td>
                        <td>{{ $jsa->job_location }}</td>
                        <td class="d-flex"><a href="{{ route('jsa-list.index', $jsa->id) }}"
                                class="btn btn-warning">Details</a>
                            @if (!empty($jsa->details_data) || $jsa->file_upload !== null)
                                <a href="{{ route('jsa-print', $jsa->id) }}" class="btn btn-info">Print</a>
                            @endif
                            <button data-bs-toggle="modal" data-bs-target="#exampleModal" class="btn btn-danger"
                                wire:click="setDelete({{ $jsa->id }})">Delete</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true"
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
            const dTable = new DataTable('#jsaTable', {
                ordering: false,
            });
        });
    </script>
</div>
