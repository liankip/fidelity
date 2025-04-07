<div>
     <h1>Safety Talk</h1>
     <div class="alert alert-warning">
          <strong>
               Berikut merupakan form pembuatan Safety Talk
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
          <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
               New Form
          </button>
     </div>

     <div class="bg-white p-4" wire:ignore>
          <table class="table table-bordered" id="csmsTable">
               <thead>
                    <th>No</th>
                    <th>Activity Date</th>
                    <th>Location</th>
                    <th>Job Status</th>
                    <th>Executor</th>
                    <th>Action</th>
               </thead>
               <tbody>
                    @foreach ($dataSafetyTalk as $data)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ \Carbon\Carbon::parse($data->activity_date)->format('l / d-m-Y') }}</td>
                        <td>{{ $data->location }}</td>
                        <td>{{ $data->job_status }}</td>
                        <td>{{ $data->executor }}</td>
                        <td class="d-flex">
                            <button class="btn btn-warning" wire:click='setParam({{ $data->id }})'
                                data-bs-toggle="modal" data-bs-target="#updateModal" type="button">Details</button>
                            <a href="{{ Storage::url($data->file_upload) }}" target="__blank"
                                class="btn btn-info">Print</a>
                            <button data-bs-toggle="modal" data-bs-target="#deleteModal" class="btn btn-danger"
                                wire:click="setDelete({{ $data->id }})">Delete</button>
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
               <form class="modal-content" wire:submit.prevent="handleSubmit" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                         <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                         <div>
                              <strong>Activity Date</strong>
                              <span class="text-danger">*</span>
                              <input type="date" class="form-control mb-2" wire:model="activityDate" required>
                         </div>

                         <div>
                              <strong>Location</strong>
                              <span class="text-danger">*</span>
                              <input type="text" class="form-control mb-2" wire:model="locationName"
                                   placeholder="Location Name" required>
                         </div>

                         <div>
                              <strong>Job Name</strong>
                              <span class="text-danger">*</span>
                              <input type="text" class="form-control mb-2" wire:model="jobName"
                                   placeholder="Job Status / Desc" required>
                         </div>

                         <div>
                              <strong>Executor</strong>
                              <span class="text-danger">*</span>
                              <input type="text" class="form-control mb-2" wire:model="executorStatus"
                                   placeholder="Executor" required>
                         </div>
                         <div class="col-xs-12 col-sm-12 col-md-12">
                              <div class="form-group">
                                   <strong>Upload file (PDF)</strong>
                                   <span class="text-danger">*</span>
                                   <div class="d-flex gap-2">
                                        <input type="file" class="form-control" wire:model="fileUpload"
                                             accept="application/pdf" required>
                                   </div>
                                   <div wire:loading wire:target="fileUpload">Uploading...</div>
                              </div>
                         </div>
                    </div>
                    <div class="modal-footer">
                         <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                         <button type="submit" class="btn btn-primary" wire:loading.attr="disabled"
                         wire:target="fileUpload">Save changes</button>
                    </div>
               </form>
          </div>
     </div>

     <!-- Update Modal -->
     <div class="modal fade" id="updateModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true"
          wire:ignore.self>
          <div class="modal-dialog">
               <form class="modal-content" wire:submit.prevent="handleUpdate" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                         <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                         <div>

                              <strong>Activity Date</strong>
                              <span class="text-danger">*</span>
                              <input type="date" class="form-control mb-2" wire:model="editDate" required>
                         </div>

                         <div>

                              <strong>Location</strong>
                              <span class="text-danger">*</span>
                              <input type="text" class="form-control mb-2" wire:model="editLocation"
                                   placeholder="Location Name" required>
                         </div>

                         <div>
                              <strong>Job Name</strong>
                              <span class="text-danger">*</span>
                              <input type="text" class="form-control mb-2" wire:model="editJob"
                                   placeholder="Job Status / Desc" required>
                         </div>

                         <div>
                              <strong>Executor</strong>
                              <span class="text-danger">*</span>
                              <input type="text" class="form-control mb-2" wire:model="editExecutor"
                                   placeholder="Executor" required>
                         </div>
                         <div class="col-xs-12 col-sm-12 col-md-12">
                              <div class="form-group">
                                   <strong>Upload file (PDF)</strong>
                                   <span class="text-danger">*</span>
                                   <div class="d-flex gap-2">
                                        <input type="file" class="form-control" wire:model='editFile'
                                             accept="application/pdf">
                                   </div>
                                   @if ($specificSafetyTalk !== null)
                                        <a href="{{ Storage::url($specificSafetyTalk->file_upload) }}"
                                             target="__blank" class="btn btn-info mt-2">Download existing document</a>
                                   @endif
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
                         <button type="button" class="btn-close" data-bs-dismiss="modal"
                              aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                         <h5>Apakah Anda yakin ingin menghapus dokumen ?</h5>
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
               const dTable = new DataTable('#csmsTable', {
                    ordering: false,
               });
          });
     </script>
</div>
