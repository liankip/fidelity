<div>
     <h1>Create Checklist</h1>

     <div class="container bg-white p-4">
          <form wire:submit.prevent="handleSubmit">
               @foreach ($formData as $formIndex => $data)
                    <div class="row">
                         <div class="col-lg-4">
                              <input type="text" wire:model="formData.{{ $formIndex }}.vehicle_no"
                                   placeholder="Vehicle Number" class="form-control">
                         </div>
                         <div class="col-lg-4">
                              <input type="text" wire:model="formData.{{ $formIndex }}.vehicle_name"
                                   placeholder="Vehicle Name" class="form-control">
                         </div>
                         <div class="col-lg-4">
                              <input type="text" wire:model="formData.{{ $formIndex }}.service_type"
                                   placeholder="Service Type" class="form-control">
                         </div>

                         <div class="mt-3">
                              <strong>Checklist Months:</strong><br><br>
                              @foreach ($months as $monthIndex => $monthName)
                                   <div class="form-check form-check-inline {{ $monthIndex >= 9 ? 'mt-2' : '' }}">
                                        <label>
                                             <input type="checkbox" class="form-check-input"
                                                  wire:model="formData.{{ $formIndex }}.checklist_months.{{ $monthName }}">
                                             {{ $monthName }}
                                        </label>
                                   </div>
                              @endforeach
                         </div>

                         <hr>
                    </div>
                    @if ($formIndex > 0)
                         <button wire:click.prevent="removeField({{ $formIndex }})"
                              class="btn btn-danger">Remove</button>
                    @endif
               @endforeach
               <div class="mt-4">
                    <button wire:click.prevent="addField" class="btn btn-primary" wire:loading.attr="disabled">Add
                         Field</button>
                    <button class="btn btn-success" type="submit">Submit</button>
               </div>
          </form>

     </div>
</div>
