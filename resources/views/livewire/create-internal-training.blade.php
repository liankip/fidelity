<div>
     <h1>Create Internal Training Form</h1>

     <form class="container bg-white p-4" wire:submit.prevent="handleSubmit">
          @csrf
          @foreach ($fields as $index => $field)
               <div class="row mt-3 mb-1">
                    <div class="col-lg-6">
                         <div class="mb-3" wire:key="{{ $index }}">
                              <label for="aspect_name_{{ $index }}" class="form-label">Aspek / Bahaya /
                                   Masalah</label>
                              <input type="text" name="aspect_name_{{ $index }}"
                                   id="aspect_name_{{ $index }}"
                                   wire:model="fields.{{ $index }}.aspect_name" class="form-control" required>
                         </div>

                         <div class="mb-3">
                              <label for="risk_effect_{{ $index }}" class="form-label">Dampak & Resiko</label>
                              <input type="text" name="risk_effect_{{ $index }}"
                                   id="risk_effect_{{ $index }}"
                                   wire:model="fields.{{ $index }}.risk_effect" class="form-control" required>
                         </div>

                         <div class="mb-3">
                              <label for="program_plan_{{ $index }}" class="form-label">Rencana Program</label>
                              <input type="text" name="program_plan_{{ $index }}"
                                   id="program_plan_{{ $index }}"
                                   wire:model="fields.{{ $index }}.program_plan" class="form-control" required>
                         </div>
                    </div>

                    <div class="col-lg-6">

                         <div class="mb-3">
                              <label for="plan_{{ $index }}" class="form-label">Rencana</label>
                              <input type="text" name="plan_{{ $index }}" id="plan_{{ $index }}"
                                   wire:model="fields.{{ $index }}.plan" class="form-control">
                         </div>

                         <div class="mb-3">
                              <label for="realization_{{ $index }}" class="form-label">Realisasi</label>
                              <input type="text" name="realization_{{ $index }}"
                                   id="realization_{{ $index }}"
                                   wire:model="fields.{{ $index }}.realization" class="form-control">
                         </div>

                         <div class="mb-3">
                              <label for="notes_{{ $index }}" class="form-label">Keterangan</label>
                              <textarea name="notes_{{ $index }}" id="notes_{{ $index }}" rows="3"
                                   wire:model="fields.{{ $index }}.notes" class="form-control"></textarea>
                         </div>
                    </div>
               </div>
               @if ($index > 0)
                    <button wire:click.prevent="removeField({{ $index }})"
                         class="btn btn-danger">Remove</button>
               @endif
               <hr>
          @endforeach

          <button wire:click.prevent="addField" class="btn btn-primary" wire:loading.attr="disabled">Add Field</button>
          <div class="mt-3">
               <button class="btn btn-success w-25" type="submit" {{ $isSubmitting ? 'disabled' : '' }}
               >Submit</button>
          </div>
     </form>
</div>
