<h1>Edit Overtime Form</h1>
@if (session('success'))
     <div class="alert alert-success w-25 position-absolute" role="alert" style="top: 70px; right: 20px;">
          <svg width="20" height="20" viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg">
               <path d="M15 0C6.72 0 0 6.72 0 15C0 23.28 6.72 30 15 30C23.28 30 30 23.28 30 15C30 6.72 23.28 0 15 0ZM12 22.5L4.5 15L6.615 12.885L12 18.255L23.385 6.87L25.5 9L12 22.5Z"
                    fill="#FFFFFF" />
          </svg>
          {{ session('success') }}
     </div>
@endif
<div class="bg-white p-5">
     <form method="POST" action="{{ route('overtime.editData') }}">
          @csrf

          <input type="hidden" value="{{ $overtimeData->first()->overtime_id }}" name="overtime_id">
          <div class="form-group d-lg-flex justify-content-between gap-4">
               <div class="col-lg-6 d-flex flex-column gap-4">
                    <div>
                         <label for="tanggal">Tanggal <span class="text-danger">*</span></label>
                         <input type="date" class="form-control" id="tanggal" name="overtime_date" required
                              value="{{ $overtimeData->first()->overtime_date }}">
                    </div>

                    <div>
                         <label for="yang-menugaskan">Yang Menugaskan</label>
                         <select class="form-control" id="yang-menugaskan" name="assigned_by" readonly>
                              <option value="{{ Auth::user()->id }}" selected>{{ Auth::user()->name }}</option>
                         </select>
                    </div>

                    <div class="mt-1" id="dynamicSelectContainer">
                         <label for="username">Nama <span class="text-danger">*</span></label>
                         <div class="d-flex align-items-center justify-content-between mb-2">
                              <div class="select-container col-lg-10">
                                   <select class="form-control position-relative" name="user_id[]" id="username">
                                        @foreach ($userData as $user)
                                             <option value="{{ $user->id }}" {{ $user->id }}>
                                                  {{ $user->name }}
                                             </option>
                                        @endforeach
                                   </select>
                              </div>
                              <div>
                                   <button type="button" class="btn btn-success" onclick="addSelect()">+</button>
                              </div>
                         </div>

                    </div>

                    <div class="d-flex align-items-center justify-content-between">
                         <div class="col-lg-5">
                              <label for="jam-mulai">Jam Mulai <span class="text-danger">*</span></label>
                              <input type="time" class="form-control" id="jam-mulai" name="start_time"
                                   value="{{ $overtimeData->first()->start_time }}" required>
                         </div>

                         <div class="h-100 d-flex align-items-center mt-3"><span>s/d</span></div>

                         <div class="col-lg-5">
                              <label for="target-selesai">Target Selesai <span class="text-danger">*</span></label>
                              <input type="time" class="form-control" id="target-selesai" name="finish_time" required
                                   value="{{ $overtimeData->first()->finish_time }}">
                         </div>
                    </div>

               </div>

               <div class="col-lg-6 d-flex flex-column gap-4">
                    <div>
                         <label for="project">Project <span class="text-danger">*</span></label>
                         <select type="text" class="form-control" id="project" name="project_id" required>
                              @foreach ($projectData as $project)
                                   <option value="{{ $project->id }}"
                                        {{ $overtimeData->first()->project_id === $project->id ? 'selected' : '' }}>
                                        {{ $project->name }}
                                   </option>
                              @endforeach

                         </select>
                    </div>

                    <div>
                         <label for="uraian-pelaporan-lembur">Uraian Pelaporan Lembur <span class="text-danger">*</span></label>
                         <textarea class="form-control" id="uraian-pelaporan-lembur" name="overtime_report" rows="3" required>{{ $overtimeData->first()->overtime_report }}</textarea>
                    </div>

                    <div>
                         <div class="form-group">
                              <label for="estimasi-biaya-lembur">Estimasi Biaya Lembur <span
                                        class="text-danger">*</span></label>
                              <div class="d-flex border-black border p-1 rounded">

                                   <input value="Rp." class="border-0" style="width:6%;">
                                   <input type="text" class="form-control border-0" id="estimasi-biaya-lembur"
                                        name="est-cost" oninput="formatCurrency(this)" required value="{{ number_format($overtimeData->first()->est_cost) }}">
                                   {{-- <input type="hidden" name="estimasi-biaya-lembur" id="estimasi-biaya-lembur-hidden"> --}}
                              </div>
                         </div>
                    </div>
               </div>
          </div>

          <div class="mt-4">
               <button type="submit" class="btn btn-success"
                    @if (session('success')) disabled @endif>Edit</button>
               <a type="button" href="javascript:history.back()" class="btn btn-danger">Cancel</a>
          </div>
     </form>

</div>

<script>
     let selectIndex = 1;

     $(document).ready(function() {
          $('#username, #project').select2({
               theme: 'bootstrap-5'
          })
          const initData = {!! json_encode($overtimeData) !!}
          const userData = {!! json_encode($userData) !!}

          const container = document.getElementById('dynamicSelectContainer');

          initData.forEach(overtime => {
               const newSelectContainer = document.createElement('div');
               newSelectContainer.className =
                    'd-flex align-items-center justify-content-between mb-2 gap-1 col-lg-11';

               const newSelect = document.createElement('select');
               newSelect.className = 'form-control mb-2';
               newSelect.name = `user_id[${selectIndex}]`;

               // Populate options with userData
               userData.forEach(user => {
                    const option = document.createElement('option');
                    option.value = user.id;
                    option.text = user.name;
                    newSelect.appendChild(option);
               });

               // Add the overtime user as selected
               const selectedOption = document.createElement('option');
               selectedOption.value = overtime.user_id;
               selectedOption.text = overtime.user ? overtime.user.name : '';
               selectedOption.selected = true; // Mark it as selected
               newSelect.appendChild(selectedOption);

               newSelectContainer.appendChild(newSelect);

               // Delete button for the new select
               const deleteButton = document.createElement('button');
               deleteButton.type = 'button';
               deleteButton.className = 'btn btn-danger btn-sm rounded-pill';
               deleteButton.textContent = '-';
               deleteButton.onclick = function() {
                    container.removeChild(newSelectContainer);
               };

               newSelectContainer.appendChild(deleteButton);
               container.appendChild(newSelectContainer);

               // Apply Select2 to the newly created select
               $(newSelect).select2({
                    theme: 'bootstrap-5'
               });

               selectIndex++;
          });

     })

     function addSelect() {
          const container = document.getElementById('dynamicSelectContainer');
          const newSelectContainer = document.createElement('div');
          newSelectContainer.className = 'd-flex align-items-center justify-content-between mb-2 gap-1 col-lg-11';

          const newSelect = document.createElement('select');
          newSelect.className = 'form-control mb-2';
          newSelect.name = `user_id[${selectIndex}]`;

          // Copy options from the original select
          document.querySelectorAll('#dynamicSelectContainer .select-container:first-child select option').forEach((
               option) => {
               const newOption = document.createElement('option');
               newOption.value = option.value;
               newOption.text = option.text;
               newSelect.appendChild(newOption);
          });

          newSelectContainer.appendChild(newSelect);

          // Delete button for the new select
          const deleteButton = document.createElement('button');
          deleteButton.type = 'button';
          deleteButton.className = 'btn btn-danger btn-sm rounded-pill';
          deleteButton.textContent = '-';
          deleteButton.onclick = function() {
               container.removeChild(newSelectContainer);
          };

          newSelectContainer.appendChild(deleteButton);
          container.appendChild(newSelectContainer);

          // Apply Select2 to the newly created select
          $(newSelect).select2({
               theme: 'bootstrap-5'
          });

          selectIndex++;
     }

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
</script>
