<div>
     <h1>Meeting Form</h1>

     <div class="bg-white p-4">
          <form method="POST" wire:submit.prevent="handleSubmit">
               @csrf
               <div class="mb-3">
                    <label for="meeting_date" class="form-label">Tanggal Rapat</label>
                    <input type="date" class="form-control" id="meeting_date" name="meeting_date" wire:model="meetingDate" required >
               </div>
               <div class="mb-3">
                    <label for="location" class="form-label">Lokasi</label>
                    <input type="text" class="form-control" id="location" name="location" wire:model="meetingLocation" required>
               </div>
               <div class="mb-3 d-lg-flex gap-4 bg-light p-2">
                    <div class="col-lg-6" wire:ignore>
                         <label for="attendance_list" class="form-label">Daftar Hadir</label>
                         <select class="form-control" id="attendance_list" required>
                              @foreach ($dataUser as $user)
                                   <option value="{{ $user->name }}">{{ $user->name }}</option>
                              @endforeach
                         </select>

                         <label for="guest_list">Tamu</label>
                         <input type="text" class="form-control" id="guest_list">
                         <button class="btn btn-info mt-1"
                              wire:click="$emit('addGuest', document.getElementById('guest_list').value)"
                              type="button">Add</button>
                    </div>
                    <div class="col-lg-6 d-flex flex-column">
                         @if (!empty($selectedOptions))
                              <h3>Karyawan</h3>
                         @endif
                         @foreach ($selectedOptions as $index => $option)
                              <div class="text-left w-100 d-flex mb-1">
                                   <button class="btn btn-danger btn-xs" type="button"
                                        wire:click="removeSelected({{ $index }})">X</button>
                                   <input class="text-black h-100 d-flex align-items-center form-control w-50" readonly
                                        value="{{ $option }}">
                              </div>
                         @endforeach

                         @if (!empty($guestList))
                              <h3>Tamu</h3>
                         @endif
                         @foreach ($guestList as $index => $option)
                              <div class="text-left w-100 d-flex mb-1">
                                   <button class="btn btn-danger btn-xs" type="button"
                                        wire:click="removeGuest({{ $index }})">X</button>
                                   <input class="text-black h-100 d-flex align-items-center form-control w-50" readonly
                                        value="{{ $option }}">
                              </div>
                         @endforeach
                    </div>

               </div>

               <div class="mb-3">
                    <label for="notulen_rapat" class="form-label">Notulen Rapat</label>

                    @foreach ($notulenRapat as $index => $notulen)
                         <div class="input-group mb-3">
                              <textarea type="text" class="form-control" id="notulen_rapat_{{ $index }}"
                                   name="notulen_rapat[]" wire:model="notulenRapat.{{ $index }}" required></textarea>
                              @if ($index > 0)
                                   <button class="btn btn-danger" type="button"
                                        wire:click="removeNotulenRapat({{ $index }})">
                                        Remove
                                   </button>
                              @endif
                         </div>
                    @endforeach
                    <button class="btn btn-success" type="button" wire:click="addNotulenRapat">Add Field</button>
               </div>

               <div class="mb-3">
                    <label for="meeting_notes" class="form-label">Notulensi</label>
                    <input class="form-control" id="meeting_notes" name="meeting_notes" wire:model="notulensi" required>
               </div>
               <button type="submit" class="btn btn-primary">Submit</button>
          </form>
     </div>
     <script>
          $(document).ready(function() {
               $('#attendance_list').select2({
                    theme: 'bootstrap-5'
               }).on('change', function(e) {
                    @this.call('updateSelected', $(this).val());
               });
          });
     </script>
</div>
