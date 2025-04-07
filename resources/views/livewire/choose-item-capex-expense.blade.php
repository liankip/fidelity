    <div>
        <div class="row">
            <div class="col-lg-12 margin-tb">
                <div class="pull-left">
                    <a href="{{ route('capex-expense.boq', ['project_id' => $project_id]) }}" class="third-color-sne"> <i
                            class="fa-solid fa-chevron-left fa-xs"></i> Back</a>
                    <h2 class="primary-color-sne">List Capex Expense</h2>
                    <h4 class="text-secondary">
                        <strong>Capex Expense</strong>
                    </h4>

                    @foreach (['danger', 'warning', 'success', 'info'] as $key)
                        @if (Session::has($key))
                            <div class="alert alert-{{ $key }} alert-dismissible fade show mb-1 mt-1"
                                role="alert">
                                {{ Session::get($key) }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                                </button>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
        <div class="card primary-box-shadow">
            <div class="card-body">
                <div class="col-lg-12">
                    <div class="overflow-x-max">
                        <table class="table primary-box-shadow">
                            <thead class="thead-light">
                                <tr class="table-secondary fw-semibold">
                                    <th class="align-middle text-center border-top-left" style="width: 30%">Item</th>
                                    <th class="align-middle text-center">Quantity</th>
                                    <th class="align-middle text-center">Unit</th>
                                    <th class="align-middle text-center border-top-right" style="width: 20%">
                                        Specification
                                    </th>
                                </tr>
                            </thead>
                            @forelse ($items as $index => $item)
                                <tr>
                                    <td class="align-middle">{{ $item->item->name }}
                                    </td>
                                    <td class="align-middle text-end">
                                        <input type="text" wire:model.defer="qty.{{ $index }}"
                                            class="form-control">
                                    </td>
                                    <td class="align-middle text-end">
                                        {{ $item->unit->name }}
                                    </td>
                                    <td class="align-middle text-end">
                                        <textarea wire:model.defer="notes.{{ $index }}" rows="3" class="form-control"
                                            placeholder="Add and Edit Specification"></textarea>
                                        @error('notes.' . $index)
                                            <span class="error text-danger">{{ $message }}</span>
                                        @enderror
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">No Item Found</td>
                                </tr>
                            @endforelse
                        </table>

                    </div>
                    <div class="d-flex justify-content-center">

                    </div>
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" style="float:right;" class="btn btn-success"
                    wire:click="addItem({{ json_encode($items) }})" wire:loading.attr="disabled">
                    <i class="fas fa-save"></i>
                    Simpan
                </button>
            </div>
        </div>
    </div>
