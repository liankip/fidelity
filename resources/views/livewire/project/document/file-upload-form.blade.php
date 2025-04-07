<div>
    <form wire:submit.prevent="upload" id="uploadForm">
        <fieldset class="upload_dropZone text-center mb-3 p-4">
            <legend class="visually-hidden">Image uploader</legend>
            <i class="fas fa-cloud-upload-alt fa-3x"></i>
            <p class="small my-2">Drag &amp; Drop excel file inside dashed region<br><i>or</i></p>
            <input id="boqFile" class="position-absolute invisible"
                   type="file"
                   accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel"
                   wire:model="file"/>
            <label class="btn btn-upload mb-3" for="boqFile">Choose file(s)</label>
            <div class="upload_gallery d-flex flex-wrap justify-content-center gap-3 mb-0">
                <span>
                    @if($file)
                        {{ $file->getClientOriginalName() }}
                    @else
                        No file selected
                    @endif
                </span>
            </div>

        </fieldset>
        @error('file') <span class="text-danger">{{ $message }}</span> @enderror
    </form>
    <div class="d-flex justify-content-end">
        <button type="submit" class="btn btn-primary" wire:click="uploadDocument">Submit File</button>
    </div>
</div>
