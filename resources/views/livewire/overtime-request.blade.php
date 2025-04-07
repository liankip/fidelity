<div>
     <h1>Overtime Request</h1>

     @if (session('success'))
          <div class="alert alert-success w-25 position-absolute" role="alert" style="top: 70px; right: 20px;">
               <svg width="20" height="20" viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M15 0C6.72 0 0 6.72 0 15C0 23.28 6.72 30 15 30C23.28 30 30 23.28 30 15C30 6.72 23.28 0 15 0ZM12 22.5L4.5 15L6.615 12.885L12 18.255L23.385 6.87L25.5 9L12 22.5Z"
                         fill="#FFFFFF" />
               </svg>
               {{ session('success') }}
          </div>
     @endif

     <hr>
     <div class="bg-white p-2">

          <!-- Your Blade view -->
          <div class="my-3">
               <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                         <button wire:click="setFilter('all')" class="nav-link {{ $filter === 'all' ? 'active' : '' }}">
                              All
                         </button>
                    </li>
                    <li class="nav-item position-relative" role="presentation">
                         <button wire:click="setFilter('new')" class="nav-link {{ $filter === 'new' ? 'active' : '' }}">
                              New
                         </button>
                    </li>
                    <li class="nav-item position-relative" role="presentation">
                         <button wire:click="setFilter('approved')"
                              class="nav-link {{ $filter === 'approved' ? 'active' : '' }}">
                              Approved
                         </button>
                    </li>
                    <li class="nav-item position-relative" role="presentation">
                         <button wire:click="setFilter('rejected')"
                              class="nav-link {{ $filter === 'rejected' ? 'active' : '' }}">
                              Rejected
                         </button>
                    </li>
               </ul>
          </div>

          <!-- Display the filtered data -->
          @foreach ($overtimeData as $data)
               <!-- Your HTML structure to display the data -->
          @endforeach

          <div class="table-responsive">
               <table class="table table-bordered" id="overtimeTable">
                    <thead class="text-center">
                         <tr>
                              <th>No</th>
                              <th>Overtime Date</th>
                              <th>Project</th>
                              <th>User Name</th>
                              <th>Requester</th>
                              <th>Work Hour</th>
                              <th>Status</th>
                              <th>Overtime Description</th>
                              <th>Estimation Cost</th>
                              <th>Realization</th>
                              <th>Action</th>
                         </tr>
                    </thead>
                    <tbody class="text-center">
                         @php $rowNumber = 1; @endphp
                         @foreach ($overtimeData as $overtimeId => $records)
                              @php $firstRecord = $records->first(); @endphp
                              @if ($firstRecord)
                                   <tr>
                                        <td>{{ $rowNumber++ }}</td>
                                        <td>{{ \Carbon\Carbon::parse($firstRecord->overtime_date)->format('d/m/Y') }}
                                        </td>
                                        <td>{{ $firstRecord->project->name }}</td>
                                        <td class="text-start text-sm" style="width: 150px;">
                                             @foreach ($records as $record)
                                                  <li>{{ $record->user->name }}</li>
                                             @endforeach
                                        </td>
                                        <td>{{ $firstRecord->assignedBy->name }}</td>
                                        <td>
                                             <span>{{ \Carbon\Carbon::createFromTimeString($firstRecord->start_time)->format('H:i') }}</span>
                                             sd
                                             <span>{{ \Carbon\Carbon::createFromTimeString($firstRecord->finish_time)->format('H:i') }}</span>
                                        </td>
                                        <td
                                             @if ($firstRecord->status === 'Approved') class="text-success fw-bold"
                                            @elseif ($firstRecord->status === 'Rejected')
                                            class="text-danger fw-bold" @endif>
                                             {{ $firstRecord->status }}</td>
                                        <td>{{ $firstRecord->overtime_report }}</td>
                                        <td class="text-sm">Rp. {{ number_format($firstRecord->est_cost) }}</td>
                                        <td class="text-sm">
                                             @if ($firstRecord->realization === null && $firstRecord->status === 'New')
                                                  <button class="bg-transparent border-0" data-bs-toggle="modal"
                                                       data-bs-target="#staticBackdrop"
                                                       data-overtime-id="{{ $firstRecord->overtime_id }}"
                                                       data-request-date = "{{ $firstRecord->overtime_date }}"
                                                       data-project-name="{{ $firstRecord->project->name }}"
                                                       data-est-cost="{{ $firstRecord->est_cost }}"><i
                                                            class="fas fa-pencil"></i></button>
                                             @elseif($firstRecord->status !== 'New')
                                                  -
                                             @else
                                                  Rp. {{ number_format($firstRecord->realization) }}
                                             @endif

                                        </td>
                                        <td class="d-flex">
                                             <button type="button" class="btn btn-success" data-bs-toggle="modal"
                                                  data-bs-target="#approvalModal" data-button-value="Approve"
                                                  data-overtime-id="{{ $firstRecord->overtime_id }}"
                                                  data-request-date = "{{ $firstRecord->overtime_date }}"
                                                  data-project-name="{{ $firstRecord->project->name }}"
                                                  @if ($firstRecord->status !== 'New') disabled @endif>Approve</button>
                                             <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                                                  data-bs-target="#approvalModal" data-button-value="Reject"
                                                  data-overtime-id="{{ $firstRecord->overtime_id }}"
                                                  data-request-date = "{{ $firstRecord->overtime_date }}"
                                                  data-project-name="{{ $firstRecord->project->name }}"
                                                  @if ($firstRecord->status !== 'New') disabled @endif>Reject</button>
                                             @if (Auth::user()->id == $firstRecord->assigned_by && $firstRecord->status !== 'Approved')
                                                  <a class="btn btn-warning"
                                                       href="{{ route('overtime-edit.index', ['id' => $firstRecord->overtime_id]) }}">Edit</a>
                                             @endif
                                        </td>
                                   </tr>
                              @endif
                         @endforeach
                    </tbody>
               </table>
          </div>

     </div>
     <!-- Realization Modal -->
     <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
          aria-labelledby="staticBackdropLabel" aria-hidden="true" style="margin-top: 15%;">
          <div class="modal-dialog">
               <form class="modal-content" method="POST" action="{{ route('overtime-request.update') }}"
                    id="realForm">
                    @csrf
                    @method('patch')
                    <div class="modal-header">
                         <h1 class="modal-title fs-5" id="staticBackdropLabel"></h1>
                         <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-center">
                         <div>
                              <h5>Realisasi Biaya Lembur</h5>
                              <p><span id="modalProjectName"></span> - <span id="modalProjectDate"></span></p>
                              <div class="form-group">
                                   <div class="d-flex border-black border p-1 rounded">
                                        <input value="Rp." class="border-0" style="width:6%;">
                                        <input type="hidden" name="overtime_id" id="overtime-id-hidden">
                                        <input type="text" class="form-control border-0" id="realId"
                                             name="realization" oninput="formatCurrency(this)" required>
                                   </div>
                              </div>
                         </div>
                    </div>
                    <div class="modal-footer">
                         <button type="submit" class="btn btn-primary" id="submitSave">Save</button>
                         <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
               </form>
          </div>
     </div>

     <!-- Approval Modal -->
     <div class="modal fade" id="approvalModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
          aria-labelledby="staticBackdropLabel" aria-hidden="true" style="margin-top: 15%;">
          <div class="modal-dialog">
               <form class="modal-content" method="POST" action="{{ route('overtime-request.approval') }}"
                    id="realForm">
                    @csrf
                    @method('patch')
                    <div class="modal-header">
                         <h1 class="modal-title fs-5" id="staticBackdropLabel"></h1>
                         <button type="button" class="btn-close" data-bs-dismiss="modal"
                              aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-center">
                         <div>
                              <h5 id="approval-header">Teks judul</h5>
                              <p><span id="approvalName"></span> - <span id="approvalDate"></span></p>
                              <input type="hidden" id="overtime-approve-id" name="overtime_id">
                              <input type="hidden" id="approval-value" name="status">
                         </div>
                    </div>
                    <div class="modal-footer">
                         <button type="submit" class="btn btn-primary" id="submitSave">Yes</button>
                         <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                    </div>
               </form>
          </div>
     </div>

     <script>
          $(document).ready(function() {
               const dTable = new DataTable('#overtimeTable', {
                    ordering: false,
               });
          });

          function formatCurrency(input) {
               // Remove non-numeric characters and parse the input value as a number
               let numericValue = parseFloat(input.value.replace(/[^\d.-]/g, ''));

               // Check if the parsed value is a valid number
               if (!isNaN(numericValue)) {
                    // Format the number as a locale string
                    let formattedValue = numericValue.toLocaleString();

                    // Update the input value with the formatted currency
                    input.value = formattedValue;
               }
          }

          $('#staticBackdrop').on('show.bs.modal', function(event) {
               const button = $(event.relatedTarget); // Button that triggered the modal

               const overtimeId = button.data('overtime-id')
               const projectName = button.data('project-name');
               const requestDate = button.data('request-date')
               const estCost = button.data('est-cost')

               // Convert the date to 'dd/mm/yyyy' format
               const formattedDate = new Date(requestDate).toLocaleDateString('en-GB');

               $('#modalProjectName').text(projectName);
               $('#modalProjectDate').text(formattedDate);
               $('#overtime-id-hidden').val(overtimeId);
               $('#realId').val((estCost).toLocaleString());
          });

          $('#approvalModal').on('show.bs.modal', function(event) {
               const button = $(event.relatedTarget); // Button that triggered the modal
               const buttonValue = button.data('button-value')

               if (buttonValue === 'Approve') {
                    $('#approval-value').val('')
                    $('#approval-header').text('Apakah anda yakin menyetujui permintaan lembur di')
                    $('#approval-value').val('Approved')
               } else {
                    $('#approval-value').val('')
                    $('#approval-header').text('Apakah anda yakin tidak menyetujui permintaan lembur di')
                    $('#approval-value').val('Rejected')
               }

               const overtimeId = button.data('overtime-id')
               const projectName = button.data('project-name');
               const requestDate = button.data('request-date')

               // Convert the date to 'dd/mm/yyyy' format
               const formattedDate = new Date(requestDate).toLocaleDateString('en-GB');

               $('#approvalName').text(projectName);
               $('#approvalDate').text(formattedDate);
               $('#overtime-approve-id').val(overtimeId);
          });
     </script>

</div>
