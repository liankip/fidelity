<div wire:ignore>
    <div class="mt-2">
        <div class="row">
            <div class="col-lg-12 margin-tb">
                <div class="pull-left">
                    <a href="{{ route('sku.index') }}" class="third-color-sne"> <i
                            class="fa-solid fa-chevron-left fa-xs"></i> Back</a>
                    <h2 class="primary-color-sne">Edit SKU</h2>
                </div>

                @push('styles')
                    <link rel="stylesheet" href="{{ asset('css/handsontable.full.min.css') }}" />
                    <link rel="stylesheet" href="{{ asset('assets/css/handsontable/chosen.css') }}">
                @endpush

                <livewire:common.alert />

                <div class="mt-5">
                    <div class="card primary-box-shadow">
                        <div class="card-body">
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="row mb-3">
                                    <div class="form-group col-md-12">
                                        <label for="nama" class="form-label">Nama SKU<span
                                                class="text-danger">*</span></label>
                                        <input type="text" wire:model="name" class="form-control"
                                            placeholder="Nama SKU">
                                        @error('name')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                {{-- <div class="row">
                                    <div class="form-group col-md-4">
                                        <label for="grosir_price" class="form-label">Harga Grosir<span
                                                class="text-danger">*</span></label>
                                        <input type="text" wire:model="grosir_price" class="form-control"
                                            placeholder="Harga Grosir">
                                        @error('grosir_price')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label for="distributor_price" class="form-label">Harga Distributor<span
                                                class="text-danger">*</span></label>
                                        <input type="text" wire:model="distributor_price" class="form-control"
                                            placeholder="Harga Distributor">
                                        @error('distributor_price')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label for="msrp_price" class="form-label">Harga MSRP<span
                                                class="text-danger">*</span></label>
                                        <input type="text" wire:model="msrp_price" class="form-control"
                                            placeholder="Harga MSRP">
                                        @error('msrp_price')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div> --}}
                            </div>

                            <div id="boqTable" style="width: 100%;"></div>
                        </div>

                        <div class="card-footer">
                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('customer.index') }}" class="btn btn-danger">Cancel</a>

                                <button class="btn btn-primary ml-3" id="btnSubmit" wire:loading.attr="disabled"
                                    wire:loading.class="btn btn-primary ml-3 disabled">
                                    <span wire:loading.remove><i class="fa-solid fa-floppy-disk pe-2"></i>
                                        Save
                                    </span>
                                    <span wire:loading>
                                        <div class="spinner-border spinner-border-sm text-light" role="status">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                    </span>
                                </button>
                            </div>

                        </div>
                    </div>
                </div>
                <script type="text/javascript" src="{{ asset('/assets/js/handsontable/plugins/handsontable.full.min.js') }}"></script>
                <script type="text/javascript" src="{{ asset('/assets/js/handsontable/plugins/chosen.jquery.js') }}"></script>
                <script type="text/javascript" src="{{ asset('/assets/js/handsontable/plugins/handsontable-chosen-editor.js') }}">
                </script>
                <script>
                    const container = document.querySelector('#boqTable');
                    (function($) {
                        const dataObject = [];
                        const hotElement = document.querySelector('#boqTable');

                        const hotSettings = {
                            data: dataObject,
                            columns: [{
                                data: 'item_code',
                                renderer: customDropdownRenderer,
                                editor: "chosen",
                                width: 150,
                                chosenOptions: {
                                    data: @json($items)
                                }
                            }, {
                                data: 'unit_id',
                                width: 150,
                                type: 'dropdown',
                                source: []
                            }, {
                                data: 'unit_price',
                                type: 'numeric',
                                numericFormat: {
                                    pattern: '0,0'
                                },
                                renderer: function(instance, td, row, col, prop, value, cellProperties) {
                                    value = Math.round(value / 1000) * 1000;

                                    Handsontable.renderers.NumericRenderer.apply(this, arguments);
                                },
                                editor: 'numeric',
                            }, {
                                data: 'quantity',
                                type: 'numeric',
                                numericFormat: {
                                    pattern: '0,0'
                                },
                                editor: 'numeric',
                            }, {
                                data: 'shipping_cost',
                                type: 'numeric',
                            }, {
                                data: 'notes',
                            }],
                            licenseKey: 'non-commercial-and-evaluation',
                            stretchH: 'all',
                            width: '100%',
                            autoWrapRow: true,
                            rowHeights: 30,
                            columnHeaderHeight: 40,
                            minRows: 10,
                            rowHeaders: true,
                            colHeaders: [
                                'Items',
                                'Unit',
                                'Unit price',
                                'Quantity',
                                'Shipping Cost',
                                'Notes'
                            ],
                            columnSorting: {
                                indicator: true
                            },
                            autoColumnSize: {
                                samplingRatio: 23
                            },
                            dropdownMenu: true,
                            contextMenu: true,
                            manualRowMove: true,
                            manualColumnMove: true,
                            multiColumnSorting: {
                                indicator: true
                            },
                            hiddenColumns: {
                                columns: [6],
                                indicators: true
                            },
                            manualRowResize: true,
                            manualColumnResize: true
                        };

                        const hot = new Handsontable(hotElement, hotSettings);
                        const boqs = @json($boqs);

                        let loadData = false;
                        if (boqs.length > 0) {
                            loadData = true;
                            boqs.forEach((element, index) => {
                                hot.setCellMeta(index, hot.propToCol('unit_id'), 'source',
                                    [element[1]]);
                                hot.setDataAtRowProp(index, 'item_code', element[0]);
                                hot.setDataAtRowProp(index, 'unit_id', element[1]);
                                hot.setDataAtRowProp(index, 'unit_price', element[2]);
                                hot.setDataAtRowProp(index, 'quantity', element[3]);
                                hot.setDataAtRowProp(index, 'shipping_cost', element[4]);
                                hot.setDataAtRowProp(index, 'notes', element[5]);
                            });
                        }
                        let dataLoadedCount = 0;

                        hot.addHook('afterChange', function(changes, src) {
                            if (loadData) {
                                dataLoadedCount++;

                                if (dataLoadedCount == (boqs.length * 4)) {
                                    loadData = false;
                                }
                                return;
                            }

                            changes.forEach(([row, prop, oldValue, newValue]) => {
                                if (prop === 'item_code') {
                                    $.ajax({
                                        url: '/get-units',
                                        type: 'GET',
                                        data: {
                                            item_id: newValue
                                        },
                                        dataType: 'json',
                                        success: function(data) {
                                            const options = data.map((item) => {
                                                return item.unit.name;
                                            });
                                            hot.setCellMeta(row, hot.propToCol('unit_id'), 'source',
                                                options);
                                            hot.setDataAtRowProp(row, 'unit_id', options[0]);
                                            hot.setDataAtRowProp(row, 'shipping_cost', 0);
                                            hot.setDataAtRowProp(row, 'quantity', 1);
                                        }
                                    });

                                    $.ajax({
                                        url: `/api/get-item-price/${newValue}`,
                                        type: 'GET',
                                        dataType: 'json',
                                        success: function(data) {
                                            hot.setDataAtRowProp(row, 'unit_price', data.price);
                                        }
                                    });
                                }

                                Livewire.hook('message.processed', (message, component) => {
                                    const hasInvalidClass = container.classList.contains('htInvalid');
                                    const childrenWithInvalidClass = container.querySelectorAll(
                                        '.htInvalid').length > 0;
                                    $('#btnSubmit, #btnSave').prop('disabled', hasInvalidClass ||
                                        childrenWithInvalidClass);
                                });
                            });
                        });

                        $('#btnSubmit').on('click', function() {
                            Livewire.emit('edit', JSON.stringify(getHotData(hot)));
                            Livewire.on('productEdit', () => {
                                window.location.href = "{{ route('sku.index') }}";
                            });
                        });

                        window.addEventListener('beforeunload', () => hot.loadData([]));

                        document.addEventListener('livewire:load', function() {
                            window.livewire.on('reset-table', () => {
                                hot.loadData([]);
                            });
                        });

                        function getHotData(hot) {
                            let datas = hot.getData();
                            return datas.filter((data) => {
                                let items = data.slice(0, data.length - 1);
                                return items.every((item) => item !== '' && item !== null);
                            });
                        }

                        function customDropdownRenderer(instance, td, row, col, prop, value, cellProperties) {
                            "use strict";
                            let selectedId;
                            const optionsList = cellProperties.chosenOptions.data;

                            if (typeof optionsList === "undefined" || typeof optionsList.length === "undefined" || !optionsList
                                .length) {
                                Handsontable.cellTypes.text.renderer(instance, td, row, col, prop, value, cellProperties);
                                return td;
                            }

                            const values = (value + "").split("|");
                            value = [];
                            for (var index = 0; index < optionsList.length; index++) {

                                if (values.indexOf(optionsList[index].id + "") > -1) {
                                    selectedId = optionsList[index].id;
                                    value.push(optionsList[index].label);
                                }
                            }
                            value = value.join(", ");

                            Handsontable.cellTypes.text.renderer(instance, td, row, col, prop, value, cellProperties);
                            return td;
                        }
                    })(jQuery);
                </script>
            </div>
        </div>
    </div>
</div>
