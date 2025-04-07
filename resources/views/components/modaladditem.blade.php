<div wire:click="closeshowai" class="bg-dark opacity-50"
    style="height: 100%;width: 100%;left: 0;top: 0;overflow: hidden;position: fixed;"></div>
<div class="modal" tabindex="-1" style="display: block;" aria-modal="true" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content ">
            <div class="modal-header">
                <h3>Add Item</h3>
                <button type="button" class="btn-close" wire:click="closeshowai" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="exampleFormControlInput1" class="form-label">Name Item<span
                            class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('itemname') is-invalid @enderror" wire:model="itemname" placeholder="">
                    @error('itemname')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                    @if ($itemname)
                        @if (count($matchitem))
                            <div class="mt-1 text-warning">
                                <span class=" ms-3">Existing items match:</span>
                                <ul>
                                    @foreach ($matchitem as $existname)
                                        {{-- @dd($existname) --}}
                                        <li>
                                            <div style="margin-top: 3px"> {{ $existname->name }}</div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    @endif
                </div>
                <div class="mb-3">
                    <label for="exampleFormControlInput1" class="form-label">Unit <span
                            class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('itemunit') is-invalid @enderror" wire:model="itemunit" placeholder="">

                    @error('itemunit')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>


            </div>

            <div class="modal-footer">
                <button class="btn" wire:click="closeshowai">Cancel</button>
                {{--
                <button class="btn btn-secondary" style="cursor: not-allowed" disabled>Save</button> --}}
                <button class="btn btn-primary" wire:click="storeitem">Save</button>
            </div>

        </div>
    </div>
</div>
