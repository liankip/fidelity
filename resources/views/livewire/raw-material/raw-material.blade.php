<div class="mt-2">
    <style>
        /* Modal backdrop */
        .custom-modal {
            display: none;
            position: fixed;
            z-index: 1050;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.5);
        }

        /* Modal content */
        .custom-modal-content {
            background-color: #fff;
            margin: 10% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 30%;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        /* Close button */
        .custom-modal-close {
            color: #aaa;
            float: right;
            cursor: pointer;
        }

        .custom-modal-close:hover,
        .custom-modal-close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
    </style>

    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2 class="primary-color-sne">Raw Material</h2>
            </div>
        </div>
    </div>

    <div class="card primary-box-shadow mt-3">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3 gap-2">
                <input type="text" wire:model.debounce.500ms="search" placeholder="Search by item name" class="form-control">

                    <button class="btn btn-primary btnCreatePR" style="display: none;">Continue</button>

            </div>

            <table class="table primary-box-shadow table-bordered">
                <thead class="thead-light">
                <tr class="table-secondary">
                    <th class="text-center align-items-center border-top-left" width="5%"></th>
                    <th class="text-center not-export border-top-left">Item Name</th>
                    <th class="text-center not-export border-top-right">Product</th>
                </tr>
                </thead>

                <tbody>
                @forelse($itemData as $item)
                    <tr>
                        <td>
                            <input class="form-check-input item-checkbox" type="checkbox" value="{{ $item['ids'][0] }}">
                        </td>
                        <td class="d-flex justify-content-between">
                            <span>
                                {{ $item['item_name'] }}
                            </span>
                            <small class="badge {{ $item['stock'] > 0 ? 'badge-success' : 'badge-danger' }}">Stock: {{ $item['stock'] }}</small>
                        </td>
                        <td>
                            @if(count($item['product_name']) > 1)
                                @foreach ($item['product_name'] as $product)
                                    <li>{{ $product }}</li>
                                @endforeach
                            @else
                                {{ $item['product_name'][0] }}
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center">No item data available</td>
                    </tr>
                @endforelse

                </tbody>
            </table>
        </div>
    </div>

    <!-- Custom Modal -->
    <div id="createPurchaseRequestModal" class="custom-modal" wire:ignore.self>
        <div class="custom-modal-content">
            <span class="custom-modal-close">&times;</span>
            <h5>Purchase Request</h5>

            <form class="create-pr-form" method="POST" action="{{ route('create-new-pr') }}">
                @csrf
                <div class="form-group">
                    <label for="pr_type" class="col-form-label">PR Type:<span class="text-danger">*</span></label>

                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="type" value="Barang"
                            id="pr_type_1">
                        <label class="form-check-label" for="pr_type_1">Barang</label>
                    </div>

                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="type" value="Jasa"
                            id="pr_type_2">
                        <label class="form-check-label" for="pr_type_2">Jasa</label>
                    </div>

                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="type" value="Sewa Mesin"
                            id="pr_type_3">
                        <label class="form-check-label" for="pr_type_3">Sewa Mesin</label>
                    </div>

                    @error('type')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="requester" class="col-form-label">Requester: <span
                            class="text-danger">*</span></label>
                    <input type="text" name='requester' class="form-control" placeholder="Nama" required>
                </div>

                <div class="form-group">
                    <label for="remark">
                        <strong>Notes:</strong>
                    </label>
                    <textarea name='remark' rows="4" class="form-control" placeholder="Keterangan"></textarea>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-3">
                    <button type="button" class="btn btn-sm btn-secondary custom-modal-close">Cancel</button>
                    <button type="button" class="btn btn-sm btn-success d-flex align-items-center gap-2" id="saveButton">
                        Save
                        <div class="spinner-border text-primary" role="status" id="loadingSpinner"
                            style="display: none">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </button>
                </div>
            </form>
        </div>
    </div>
    @push('javascript')
        
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            let ItemsArray = [];

            $(document).on('click', '.custom-modal-close', function() {
                    // Instantly hide the custom modal
                    $('#customModal').css('display', 'none');
                    $('#createPurchaseRequestModal').css('display', 'none');
            });

                $(window).on('click', function(event) {
                    // Close the modal when clicking outside the content
                    if ($(event.target).is('#customModal')) {
                        $('#customModal').css('display', 'none');
                        $('#createPurchaseRequestModal').css('display', 'none');
                    }
                });

                $(document).on('click', '.btnCreatePR', function() {
                    $('#createPurchaseRequestModal').css('display', 'block');
                })

                $(document).on('click', '#saveButton', function() {
                    Livewire.emit('saveHandler', ItemsArray);
                })

                window.addEventListener('dataSaved', event => { 
                    $('.create-pr-form').submit();
                })
                
            $(document).on('change', '.item-checkbox', function() {
                const itemId = $(this).val();
                if ($(this).is(':checked')) {
                    ItemsArray.push(itemId);
                } else {
                    ItemsArray = ItemsArray.filter(id => id !== itemId);
                }

                toggleContinueButton();
            })

            function toggleContinueButton(){
                if(ItemsArray.length > 0){ 
                    $('.btnCreatePR').show();
                } else {
                    $('.btnCreatePR').hide();
                }
            }

            $('.btnCreatePR').hide();
        })
        
    </script>
    @endpush
</div>
