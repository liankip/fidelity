<div wire:ignore>
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left mb-2">
                <h2>Edit BOQ Capex Expense</h2>
                <h4 class="text-secondary"><strong>{{ $project->name }}</strong>
                </h4>
                <hr class="">
            </div>
        </div>
    </div>

    @push('styles')
        <link rel="stylesheet" href="{{ asset('css/handsontable.full.min.css') }}" />
        <link rel="stylesheet" href="{{ asset('assets/css/handsontable/chosen.css') }}">
    @endpush

    <livewire:common.alert />

    <div class="mt-5">
        <div>
            <div class="d-flex justify-content-between">
                <div class="d-flex mb-2">
                    <button class="btn btn-sm btn-primary" id="btnSave" wire:loading.attr="disabled">
                        <i class="fas fa-save"></i>
                        Save
                    </button>
                    <button class="btn btn-sm btn-success" id="btnExport" wire:loading.attr="disabled">
                        <i class="fas fa-file-export"></i>
                        Export
                    </button>
                </div>
                <div wire:loading.flex>
                    <div class="spinner-border text-primary" role="status">
                        <span class="sr-only">Saving...</span>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="col-md-12">
                    <div id="boqTable" style="width: 100%;"></div>
                </div>
            </div>

            <div class="d-flex justify-content-between">
                <div class="d-flex">
                    <button class="btn btn-outline-primary" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapse" aria-expanded="false" aria-controls="collapse">
                        Special Note
                    </button>
                    @if (count($reviewResult) > 0)
                        <button class="btn btn-outline-primary" type="button" data-bs-toggle="collapse"
                            data-bs-target="#reviewNote" aria-expanded="false" aria-controls="collapse">
                            Review Note
                        </button>
                    @endif
                </div>
                <button class="btn btn-primary ml-3" id="btnSubmit" wire:loading.attr="disabled">Ajukan BOQ</button>
            </div>
            <div class="collapse mt-3" id="collapse">
                <div class="alert alert-primary mb-0" role="alert">
                    <ul class="mb-0">
                        <li>Jika ingin menghapus baris, hapus semua isian pada baris tersebut</li>
                        <li>
                            Pastikan Unit Price, Quantity, dan Shipping Cost diisi dengan angka.
                        </li>
                    </ul>
                </div>
            </div>
            <div class="collapse mt-3" id="reviewNote">
                <div class="alert mb-0" role="alert">
                    <div class="card">
                        <div class="card-body">
                            <table class="table">
                                <thead class="thead-light">
                                    <th class="text-center align-middle" width="5%">No</th>
                                    <th class="text-center align-middle" width="15%">Item Name</th>
                                    <th class="text-center align-middle" width="5%">Unit</th>
                                    <th class="text-center align-middle" width="10%">Price Estimation</th>
                                    <th class="text-center align-middle" width="10%">Quantity</th>
                                    <th class="text-center align-middle" width="10%">Shipping Cost Estimation</th>
                                </thead>
                                <tbody>
                                    @foreach ($reviewResult as $item)
                                        <tr @if (is_null($item['item_name']['reviewed'])) class="text-danger" @endif>
                                            <td class="text-center align-middle">{{ $loop->iteration }}</td>
                                            @foreach ($item as $key => $val)
                                                <td class="align-middle text-center">
                                                    @if ($key === 'price')
                                                        <p>
                                                            {{ rupiah_format($item[$key]['current']) }}
                                                        </p>
                                                        @if ($item[$key]['reviewed'])
                                                            @if ($item[$key]['current'] != $item[$key]['reviewed'])
                                                                <p class="text-primary">Revision:
                                                                    {{ rupiah_format($item[$key]['reviewed']) }}
                                                                </p>
                                                            @endif
                                                        @endif
                                                    @else
                                                        <p>{{ $item[$key]['current'] }}</p>
                                                        @if ($item[$key]['reviewed'])
                                                            @if ($item[$key]['current'] != $item[$key]['reviewed'])
                                                                <p class="text-primary">Revision:
                                                                    {{ $item[$key]['reviewed'] }}
                                                                </p>
                                                            @endif
                                                        @endif
                                                    @endif

                                                </td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="mt-1">
                        <p class="fw-semibold"> Notes : <br>
                            <span class="text-danger">Text Merah</span> : Barang dihapus <br>
                        </p>
                    </div>
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
                        const childrenWithInvalidClass = Array.from(container.querySelectorAll(
                            '.htInvalid'));
                        if (hasInvalidClass || childrenWithInvalidClass.length > 0) {
                            $('#btnSubmit').prop('disabled', true);
                            $('#btnSave').prop('disabled', true);
                        } else {
                            $('#btnSubmit').prop('disabled', false);
                            $('#btnSave').prop('disabled', false);
                        }
                    });

                });
            });

            $('#btnSave').on('click', function() {
                Livewire.emit('save', JSON.stringify(getHotData(hot)));
            });

            $('#btnExport').on('click', function() {
                Livewire.emit('export', JSON.stringify(getHotData(hot)));
            });

            $('#btnSubmit').on('click', function() {
                Livewire.emit('submitForReview', JSON.stringify(getHotData(hot)));
            });

            window.addEventListener('beforeunload', function() {
                hot.loadData([]);
            });

            document.addEventListener('livewire:load', function() {
                window.livewire.on('reset-table', () => {
                    hot.loadData([]);
                });
            });
        })(jQuery);

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

            if (typeof optionsList === "undefined" || typeof optionsList.length === "undefined" || !optionsList.length) {
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
    </script>
</div>
