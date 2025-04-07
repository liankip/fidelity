<div>
    <div class="d-flex justify-content-between">
        <a href="{{route('boq.index', $projectId)}}" class="btn btn-danger mb-5">
            <i class="fas fa-arrow-left"></i> Back
        </a>
        <a href="{{config('app.boq_template')}}" target="_blank">
            <button class="btn btn-success mb-5 ms-3">
                <i class="fas fa-download"></i> Download Template
            </button>
        </a>
    </div>

    <form wire:submit.prevent="upload" id="uploadForm">
        <fieldset class="upload_dropZone text-center mb-3 p-4">
            <legend class="visually-hidden">Image uploader</legend>
            <i class="fas fa-cloud-upload-alt fa-3x"></i>
            <p class="small my-2">Drag &amp; Drop excel file inside dashed region<br><i>or</i></p>
            <input id="boqFile" class="position-absolute invisible"
                   type="file"
                   accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel"
                   wire:model="boqFile"/>
            <label class="btn btn-upload mb-3" for="boqFile">Choose file(s)</label>
            <div class="upload_gallery d-flex flex-wrap justify-content-center gap-3 mb-0">
                <span>
                    @if($boqFile)
                        {{ $boqFile->getClientOriginalName() }}
                    @else
                        No file selected
                    @endif
                </span>
            </div>
        </fieldset>
        <button type="submit" class="btn btn-primary">Submit File</button>
    </form>

    @if($boqList)
        <div class="row mt-5">
            <div class="card">
                <div class="card-body">
                    <div class="col-md-12">
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th>No</th>
                                <th>Item ID</th>
                                <th>Item Name</th>
                                <th>Unit</th>
                                <th>Quantity</th>
                                <th>Harga</th>
                                <th>Ongkos Kirim</th>
                                <th>Kota Asal</th>
                                <th>Kota Tujuan</th>
                                <th>Note</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($boqList as $boq)
                                <tr>
                                    <td>
                                        {{ $loop->iteration }}
                                    </td>
                                    <td>{{ $boq['item_id'] }}
                                        @if(array_key_exists('item_id', $boq['error']['list']))
                                            <span><i class="fas fa-times text-danger"></i></span>
                                        @endif
                                    </td>
                                    <td>{{ $boq['item_name'] }}</td>
                                    <td>
                                        {{ $boq['unit'] }}
                                        @if(array_key_exists('unit', $boq['error']['list']))
                                            <span><i class="fas fa-times text-danger"></i></span>
                                        @endif
                                    </td>
                                    <td>
                                        {{ $boq['qty'] }}
                                        @if(array_key_exists('qty', $boq['error']['list']))
                                            <span><i class="fas fa-times text-danger"></i></span>
                                        @endif
                                    </td>
                                    <td>
                                        @if(is_numeric($boq['price_estimation']))
                                            Rp. {{ number_format($boq['price_estimation'], 0, ',', '.') }}
                                        @else
                                            {{ $boq['price_estimation'] }}
                                        @endif
                                        @if(array_key_exists('price_estimation', $boq['error']['list']))
                                            <span><i class="fas fa-times text-danger"></i></span>
                                        @endif
                                    </td>
                                    <td>
                                        @if(is_numeric($boq['shipping_cost']))
                                            Rp. {{ number_format($boq['shipping_cost'], 0, ',', '.') }}
                                        @else
                                            {{ $boq['shipping_cost'] }}
                                        @endif
                                        @if(array_key_exists('shipping_cost', $boq['error']['list']))
                                            <span><i class="fas fa-times text-danger"></i></span>
                                        @endif
                                    </td>
                                    <td>{{ $boq['origin'] }}</td>
                                    <td>{{ $boq['destination'] }}</td>
                                    <td>{{ $boq['note'] }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            @if($isError)
                <x-warning-alert :errors="$boqList->pluck('error')"/>
            @endif
            <div class="row justify-content-end mb-5 ">
                <button class="btn btn-success w-auto" wire:click="submitBOQ">
                    Submit BOQ
                </button>
            </div>
        </div>
    @endif
    <script>
        const preventDefaults = event => {
            event.preventDefault();
            event.stopPropagation();
        };

        const highlight = event =>
            event.target.classList.add('highlight');

        const unhighlight = event =>
            event.target.classList.remove('highlight');

        const getInputAndGalleryRefs = element => {
            const zone = element.closest('.upload_dropZone') || false;
            const gallery = zone.querySelector('.upload_gallery') || false;
            const input = zone.querySelector('input[type="file"]') || false;
            return {input: input, gallery: gallery};
        }

        const handleDrop = event => {
            const dataRefs = getInputAndGalleryRefs(event.target);
            dataRefs.files = event.dataTransfer.files;
            handleFiles(dataRefs);
        }

        const eventHandlers = zone => {
            const dataRefs = getInputAndGalleryRefs(zone);
            if (!dataRefs.input) return;

            // Prevent default drag behaviors
            ;['dragenter', 'dragover', 'dragleave', 'drop'].forEach(event => {
                zone.addEventListener(event, preventDefaults, false);
                document.body.addEventListener(event, preventDefaults, false);
            });

            // Highlighting drop area when item is dragged over it
            ;['dragenter', 'dragover'].forEach(event => {
                zone.addEventListener(event, highlight, false);
            });
            ;['dragleave', 'drop'].forEach(event => {
                zone.addEventListener(event, unhighlight, false);
            });

            // Handle dropped files
            zone.addEventListener('drop', handleDrop, false);

            // Handle browse selected files
            dataRefs.input.addEventListener('change', event => {
                dataRefs.files = event.target.files;
            }, false);

        }

        // Initialise ALL dropzones
        const dropZones = document.querySelectorAll('.upload_dropZone');
        for (const zone of dropZones) {
            eventHandlers(zone);
        }

        // Double checks the input "accept" attribute
        const isExcelFile = file =>
            ['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/vnd.ms-excel'].includes(file.type);

        // Handle both selected and dropped files
        const handleFiles = dataRefs => {

            let files = [...dataRefs.files];

            // Remove unaccepted file types
            files = files.filter(item => {
                if (!isExcelFile(item)) {
                    console.log('Not an excel, ', item.type);
                }
                return isExcelFile(item) ? item : null;
            });

            if (!files.length) return;
            dataRefs.files = files;

            // set files to input
            const event = new Event('change');
            const fileList = new DataTransfer();
            files.forEach(file => fileList.items.add(file));
            dataRefs.input.files = fileList.files;
            dataRefs.input.dispatchEvent(event);

        }
    </script>
</div>
