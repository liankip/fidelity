<div>
    <div class="row">
        <div class="col-lg-12 margin-tb">
            @if ($createForm == 0)
                <div class="pull-left">
                    <h2 class="primary-color-sne">Permission or Leave Request</h2>
                </div>
            @else
                <div class="card">
                    <div class="card-body">
                        <div class="">
                            <h2>Permission or Leave Request</h2>
                            <p>
                                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed lacus eros, malesuada
                                laoreet urna eu, rutrum molestie mauris. Maecenas mauris ligula, volutpat nec mi sed,
                                rhoncus dignissim neque. Ut dignissim pharetra consectetur. Pellentesque lobortis ante
                                sit amet arcu ultricies suscipit. Etiam eu tortor ligula. Sed tincidunt
                            </p>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-dismissible fade show mb-2" role="alert">
            {{ $message }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    <div class="card mt-5 primary-color-sne">
        <div class="card-body">
            @if ($createForm == 0 && $editForm == 0)
                <form action="" method="get" class="d-flex">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" wire:model="search" name="search"
                            placeholder="Search" value="" aria-label="Recipient's username"
                            aria-describedby="button-addon2">
                    </div>
                </form>
                <div class="d-flex justify-content-between">
                    <ul class="nav nav-tabs">
                        <li class="nav-item">
                            <button class="nav-link @if ($filter == 0) tabs-link-active @endif"
                                wire:click='filterHandler(0)'>All
                            </button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link @if ($filter == 1) tabs-link-active @endif"
                                wire:click='filterHandler(1)'>New
                            </button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link @if ($filter == 2) tabs-link-active @endif"
                                wire:click='filterHandler(2)'>Approved
                            </button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link @if ($filter == 3) tabs-link-active @endif"
                                wire:click='filterHandler(3)'>Rejected
                            </button>
                        </li>
                    </ul>
                    <button type="button" class="btn btn-success" wire:click="handleCreateForm">Create +</button>
                </div>
                <div class="overflow-x-max">
                    <table class="table fs-6 mt-2">
                        <thead class="thead-light text-center">
                            <tr class="text-center table-secondary">
                                <th style="width: 3%" class="align-middle border-top-left">No</th>
                                <th style="width: 13%" class="align-middle">Tgl Request</th>
                                <th style="width: 6%" class="align-middle">Nama Karyawan</th>
                                <th style="width: 10%" class="align-middle">Jumlah Hari</th>
                                <th style="width: 5%" class="align-middle">Tanggal Izin/Cuti</th>
                                <th style="width: 10%" class="align-middle">Alasan</th>
                                <th style="width: 8%" class="align-middle">Bukti</th>
                                <th style="width: 7%" class="align-middle">Sisa Cuti Tahunan</th>
                                @if (Auth::user()->hasTopLevelAccess())
                                    <th style="width: 7%" class="align-middle border-top-right">Action</th>
                                @else
                                    <th style="width: 7%" class="align-middle">Status</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($leaves as $key => $leave)
                                <tr wire:key="leave-{{ $leave->id }}"
                                    style="font-size: 13px; background-color: white">
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ \Carbon\Carbon::parse($leave->created_at)->format('Y-m-d') }}</td>
                                    <td>{{ $leave->user->name }}</td>
                                    <td>{{ $leave->days_count }}</td>
                                    <td>
                                        {{ $leave->start_date }} <br>
                                        <span>s/d</span><br>
                                        {{ $leave->end_date }}
                                    </td>
                                    <td>{{ $leave->reason }}</td>
                                    <td>
                                        @if ($leave->attachment_file)
                                            <a href="{{ asset('storage/' . $leave->attachment_file) }}"
                                                target="_blank">Download Attachment</a>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>{{ $leave->remaining_days }}</td>
                                    <td>
                                        @if (Auth::user()->hasTopLevelAccess())
                                            @if ($leave->status == 'New')
                                                <button type="button" class="btn btn-sm btn-success"
                                                    wire:click="approveModal({{ $leave->id }})">Approve</button>
                                                <button type="button" class="btn btn-sm mt-1 btn-danger"
                                                    wire:click="rejectModal({{ $leave->id }})">Reject</button>
                                                <button type="button" class="btn btn-sm mt-1 btn-danger"
                                                    wire:click="handleEditForm({{ $leave->id }})">Edit</button>
                                            @elseif($leave->status == 'Approved')
                                                <span class="fw-bold fs-6 text-success">{{ $leave->status }}</span>
                                            @else
                                                <span class="fw-bold fs-6 text-danger">{{ $leave->status }}</span>
                                            @endif
                                            @if ($leave->reason == 'Sakit' && $leave->attachment_file == null)
                                                <button type="button"
                                                    wire:click="attachmentModal({{ $leave->id }})"
                                                    class="btn btn-sm mt-1 btn-info">Upload surat</button>
                                            @endif
                                        @else
                                            @if ($leave->status == 'New')
                                                <span class="fw-bold fs-6">{{ $leave->status }}</span>
                                            @elseif ($leave->status == 'Approved')
                                                <span class="fw-bold fs-6 text-success">{{ $leave->status }}</span>
                                            @else
                                                <span class="fw-bold fs-6 text-danger">{{ $leave->status }}</span>
                                            @endif
                                        @endif
                                    </td>
                                </tr>
                                @if ($selectedUploadFile === $leave->id)
                                    <div
                                        class="bg-black opacity-25"style="height: 100%;width: 100%;left: 0;top: 0;overflow: hidden;position: fixed;z-index:999">
                                    </div>

                                    <div class="modal d-block" tabindex="-1" role="dialog"
                                        aria-labelledby="myModalLabel" aria-hidden="true" id="myModal">
                                        <div class="modal-dialog">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Apakah Anda Yakin Menyetujui Permintaan
                                                            Cuti</h5>
                                                        <button type="button" class="btn-close"
                                                            data-bs-dismiss="modal" wire:click="closeModal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form method="POST" enctype="multipart/form-data">
                                                            <input wire:model='attachment_update' class="form-control"
                                                                type="file" id="attachment_update">
                                                            <span>
                                                                Silahkan upload bukti Notes sakit. Dengan jenis file
                                                                .jpg / .png
                                                                / .pdf dengan ukuran maksimal 1MB
                                                            </span>

                                                            @error('attachment_update')
                                                                <div class="text-danger mt-1">{{ $message }}</div>
                                                            @enderror
                                                            <div class="d-flex mt-3">
                                                                <button type="button" class="btn btn-secondary"
                                                                    wire:click="closeModal"
                                                                    data-bs-dismiss="modal">Close</button>
                                                                <button type="button" class="btn btn-primary"
                                                                    wire:click="uploadFile({{ $leave->id }})">Submit</button>
                                                                <p class="mt-1" wire:loading
                                                                    wire:target="attachment_update">Uploading...</p>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                @if ($selectedLeave === $leave->id)
                                    <div
                                        class="bg-black opacity-25"style="height: 100%;width: 100%;left: 0;top: 0;overflow: hidden;position: fixed;z-index:999">
                                    </div>

                                    <div class="modal d-block" tabindex="-1" role="dialog"
                                        aria-labelledby="myModalLabel" aria-hidden="true" id="myModal">
                                        <div class="modal-dialog">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Apakah Anda Yakin Menyetujui Permintaan
                                                            Cuti</h5>
                                                        <button type="button" class="btn-close"
                                                            data-bs-dismiss="modal" wire:click="closeModal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p class="text-center">{{ $leave->user->name }} -
                                                            {{ $leave->project->name }}</p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            wire:click="closeModal"
                                                            data-bs-dismiss="modal">Close</button>
                                                        <button type="button" class="btn btn-primary"
                                                            wire:click="approveRequest({{ $leave->id }})">Submit</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                @if ($selectedRejectLeave === $leave->id)
                                    <div
                                        class="bg-black opacity-25"style="height: 100%;width: 100%;left: 0;top: 0;overflow: hidden;position: fixed;z-index:999">
                                    </div>

                                    <div class="modal d-block" tabindex="-1" role="dialog"
                                        aria-labelledby="myModalLabel" aria-hidden="true" id="myModal">
                                        <div class="modal-dialog">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Apakah Anda Yakin Menolak Permintaan
                                                            Cuti</h5>
                                                        <button type="button" class="btn-close"
                                                            data-bs-dismiss="modal" wire:click="closeModal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p class="text-center">{{ $leave->user->name }} -
                                                            {{ $leave->project->name }}</p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            wire:click="closeModal"
                                                            data-bs-dismiss="modal">Close</button>
                                                        <button type="button" class="btn btn-primary"
                                                            wire:click="rejectRequest({{ $leave->id }})">Submit</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center">
                                        <h5 class="my-2">Data Not Found</h5>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="overflow-x-max">
                    {{ $leaves->links() }}
                </div>
            @elseif($createForm == 1)
                <form method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col">
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <strong>Tanggal Pengajuan</strong>
                                    <input type="date" class="form-control"
                                        value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" disabled>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <strong>Nama</strong>
                                    <span class="text-danger">*</span>
                                    <div>
                                        <select wire:model="user_id" id="user_id"
                                            class="js-example-basic-single form-control @error('user_id') is-invalid @enderror">
                                            <option value="" hidden>Pilih User</option>

                                            @foreach ($users as $user)
                                                <option value="{{ $user->id }}">
                                                    {{ $user->name }} ({{ $user->email }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @error('user_id')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <strong>Project</strong>
                                    <span class="text-danger">*</span>
                                    <div>
                                        <select wire:model.defer='project_id' name="project_id" id="project_id"
                                            class="js-example-basic-single form-select @error('project_id') is-invalid @enderror ">

                                            <option value="" hidden readonly>Pilih Project</option>

                                            @foreach ($projects as $project)
                                                <option value="{{ $project->id }}">
                                                    {{ $project->name }}<span>:
                                                    </span>{{ $project->company_name }}
                                                </option>
                                            @endforeach

                                        </select>
                                    </div>

                                    @error('project_id')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <strong>Alasan</strong>
                                    <span class="text-danger">*</span>
                                    <div x-data="{ reason: '' }">
                                        <div class="form-check">
                                            <input class="form-check-input" wire:model="reason" x-model="reason"
                                                type="radio" name="reason" id="reason_1" value="Sakit">
                                            <label class="form-check-label" for="reason_1">
                                                Sakit
                                            </label>
                                        </div>

                                        <div x-show="reason === 'Sakit'" id="attachment">
                                            <input wire:model='attachment' class="ms-3 form-control" type="file"
                                                id="attachment">
                                            <span>
                                                Silahkan upload bukti Notes sakit. Dengan jenis file .jpg / .png
                                                / .pdf dengan ukuran maksimal 1MB
                                            </span>
                                        </div>

                                        <div class="form-check">
                                            <input class="form-check-input" wire:model="reason" x-model="reason"
                                                type="radio" name="reason" id="reason_2" value="Cuti">
                                            <label class="form-check-label" for="reason_2">
                                                Cuti
                                            </label>
                                        </div>

                                        <div class="form-check">
                                            <input class="form-check-input" wire:model="reason" x-model="reason"
                                                type="radio" name="reason" id="reason_3"
                                                value="Darurat Keluarga">
                                            <label class="form-check-label" for="reason_3">
                                                Darurat Keluarga
                                            </label>
                                        </div>

                                        <div class="form-check">
                                            <input class="form-check-input" wire:model="reason" x-model="reason"
                                                type="radio" name="reason" id="reason_4" value="Lainnya">
                                            <label class="form-check-label" for="reason_4">
                                                Lainnya
                                            </label>
                                        </div>
                                    </div>

                                    @error('reason')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <strong>Notes</strong>
                                    <textarea class="form-control" wire:model="notes" placeholder="Notes"></textarea>
                                </div>

                            </div>
                        </div>

                        <div class="col">
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <strong>Tanggal Izin cuti</strong>
                                    <span class="text-danger">*</span>
                                    <div class="d-flex gap-2">
                                        @if ($setting->leave_request_limit == 1)
                                            <input type="date" wire:model="start_date"
                                                min="{{ \Carbon\Carbon::now()->addDays(7)->format('Y-m-d') }}"
                                                class="form-control">
                                        @else
                                            <input type="date" wire:model="start_date" class="form-control">
                                        @endif

                                        <p class="m-auto">s/d</p>
                                        @if ($setting->leave_request_limit == 1)
                                            <input type="date" wire:model="end_date"
                                                min="{{ \Carbon\Carbon::now()->addDays(7)->format('Y-m-d') }}"
                                                class="form-control">
                                        @else
                                            <input type="date" wire:model="end_date" class="form-control">
                                        @endif
                                    </div>
                                </div>
                                @error('start_date')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                                @error('end_date')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <strong>Jumlah Hari</strong>
                                    <div class="d-flex gap-2">
                                        <input type="number" wire:model="days_count" class="form-control" disabled>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button wire:click.prevent="store" wire:loading.remove type="submit"
                        class="btn btn-success">Create
                        +</button>
                    <button wire:loading wire:target='store' type="submit" class="btn btn-secondary"
                        disabled>Saving...</button>
                    <button type="button" class="btn btn-danger" wire:click="handleCreateForm">Cancel</button>
                </form>
            @elseif($editForm == 1)
                <form method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col">
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <strong>Tanggal Pengajuan</strong>
                                    <input type="date" class="form-control" wire:model="editing_created_at"
                                        disabled>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <strong>Nama</strong>
                                    <span class="text-danger">*</span>
                                    <div>
                                        <select wire:model="editing_user_id" id="editing_user_id"
                                            class="js-example-basic-single form-control @error('user_id') is-invalid @enderror">
                                            <option value="" hidden>Pilih User</option>

                                            @foreach ($users as $user)
                                                <option value="{{ $user->id }}">
                                                    {{ $user->name }} ({{ $user->email }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @error('user_id')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <strong>Project</strong>
                                    <span class="text-danger">*</span>
                                    <div>
                                        <select wire:model.defer='editing_project_id' id="editing_project_id"
                                            class="js-example-basic-single form-select @error('project_id') is-invalid @enderror ">

                                            <option value="" hidden readonly>Pilih Project</option>

                                            @foreach ($projects as $project)
                                                <option value="{{ $project->id }}">
                                                    {{ $project->name }}<span>:
                                                    </span>{{ $project->company_name }}
                                                </option>
                                            @endforeach

                                        </select>
                                    </div>

                                    @error('project_id')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <strong>Alasan</strong>
                                    <span class="text-danger">*</span>
                                    <div x-data="{ editing_reason: '{{ $editing_reason }}' }">
                                        <div class="form-check">
                                            <input wire:ignore class="form-check-input" wire:model="editing_reason"
                                                {{ $editing_reason == 'Sakit' ? 'checked=checked' : '' }}
                                                x-model="editing_reason" type="radio" name="editing_reason"
                                                id="editing_reason_1" value="Sakit">
                                            <label class="form-check-label" for="editing_reason_1">
                                                Sakit
                                            </label>
                                        </div>

                                        <div x-show="editing_reason === 'Sakit'" id="attachment">
                                            @if ($editing_attachment)
                                                <a href="{{ asset('storage/' . $editing_attachment) }}"
                                                    target="_blank">Download Existing Attachment</a>
                                            @endif
                                            <input wire:model='editing_new_attachment' class="ms-3 form-control"
                                                type="file" id="attachment">
                                            <span>
                                                Silahkan edit bukti Notes sakit. Dengan jenis file .jpg / .png
                                                / .pdf dengan ukuran maksimal 1MB
                                            </span>
                                        </div>

                                        <div class="form-check">
                                            <input wire:ignore class="form-check-input" wire:model="editing_reason"
                                                {{ $editing_reason == 'Cuti' ? 'checked=checked' : '' }}
                                                x-model="editing_reason" type="radio" name="editing_reason"
                                                id="editing_reason_2" value="Cuti">
                                            <label class="form-check-label" for="editing_reason_2">
                                                Cuti
                                            </label>
                                        </div>

                                        <div class="form-check">
                                            <input wire:ignore class="form-check-input" wire:model="editing_reason"
                                                {{ $editing_reason == 'Darurat Keluarga' ? 'checked=checked' : '' }}
                                                x-model="editing_reason" type="radio" name="editing_reason"
                                                id="editing_reason_3" value="Darurat Keluarga">
                                            <label class="form-check-label" for="editing_reason_3">
                                                Darurat Keluarga
                                            </label>
                                        </div>

                                        <div class="form-check">
                                            <input class="form-check-input" wire:model="editing_reason"
                                                {{ $editing_reason == 'Lainnya' ? 'checked=checked' : '' }}
                                                x-model="editing_reason" type="radio" name="editing_reason"
                                                id="editing_reason_4" value="Lainnya">
                                            <label class="form-check-label" for="editing_reason_4">
                                                Lainnya
                                            </label>
                                        </div>
                                    </div>

                                    @error('editing_reason')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <strong>Notes</strong>
                                    <textarea class="form-control" wire:model="editing_notes" placeholder="Notes"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <strong>Tanggal Izin cuti</strong>
                                    <span class="text-danger">*</span>
                                    <div class="d-flex gap-2">
                                        @if ($setting->leave_request_limit == 1)
                                            <input type="date" wire:model="editing_start_date"
                                                value="{{ $editing_start_date }}"
                                                min="{{ \Carbon\Carbon::now()->addDays(7)->format('Y-m-d') }}"
                                                class="form-control">
                                        @else
                                            <input type="date" wire:model="editing_start_date"
                                                value="{{ $editing_start_date }}" class="form-control">
                                        @endif
                                        <p class="m-auto">s/d</p>
                                        @if ($setting->leave_request_limit == 1)
                                            <input type="date" wire:model="editing_end_date"
                                                value="{{ $editing_end_date }}"
                                                min="{{ \Carbon\Carbon::now()->addDays(7)->format('Y-m-d') }}"
                                                class="form-control">
                                        @else
                                            <input type="date" wire:model="editing_end_date"
                                                value="{{ $editing_end_date }}" class="form-control">
                                        @endif
                                    </div>
                                </div>
                                @error('start_date')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                                @error('end_date')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <strong>Jumlah Hari</strong>
                                    <div class="d-flex gap-2">
                                        <input type="number" wire:model="editing_days_count" class="form-control"
                                            disabled>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button wire:click.prevent="update({{ $editing_id }})" wire:loading.remove type="submit"
                        class="btn btn-success">Edit</button>
                    <button wire:loading wire:target='update({{ $editing_id }})' type="submit"
                        class="btn btn-secondary" disabled>Saving...</button>
                    <button type="button" class="btn btn-danger" wire:click="handleEditForm">Cancel</button>
                </form>
            @endif
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $('#user_id').select2({
                theme: 'bootstrap-5',
            });
            $(document).on('change', '#user_id', function(e) {
                @this.set('user_id', e.target.value);
            });

            $('#project_id').select2({
                theme: 'bootstrap-5',
            });

            $(document).on('change', '#project_id', function(e) {
                @this.set('project_id', e.target.value);
            });

            // Edit
            $('#editing_user_id').select2({
                theme: 'bootstrap-5',
            });
            $(document).on('change', '#editing_user_id', function(e) {
                @this.set('editing_user_id', e.target.value);
            });

            $('#editing_project_id').select2({
                theme: 'bootstrap-5',
            });

            $(document).on('change', '#editing_project_id', function(e) {
                @this.set('editing_project_id', e.target.value);
            });
        })
        document.addEventListener("livewire:load", () => {
            Livewire.hook('message.processed', (message, component) => {
                $('#project_id').select2({
                    theme: 'bootstrap-5',
                });

                $(document).on('change', '#project_id', function(e) {
                    @this.set('project_id', e.target.value);
                });
            });

            Livewire.hook('message.processed', (message, component) => {
                $('#user_id').select2({
                    theme: 'bootstrap-5',
                });

                $(document).on('change', '#user_id', function(e) {
                    @this.set('user_id', e.target.value);
                });
            });

            // Edit

            Livewire.hook('message.processed', (message, component) => {
                $('#editing_project_id').select2({
                    theme: 'bootstrap-5',
                });

                $(document).on('change', '#editing_project_id', function(e) {
                    @this.set('editing_project_id', e.target.value);
                });
            });

            Livewire.hook('message.processed', (message, component) => {
                $('#editing_user_id').select2({
                    theme: 'bootstrap-5',
                });

                $(document).on('change', '#editing_user_id', function(e) {
                    @this.set('editing_user_id', e.target.value);
                });
            });
        });
    </script>
</div>
