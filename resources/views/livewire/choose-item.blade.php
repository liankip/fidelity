<div>
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2 class="primary-color-sne">List Item's of PR: {{ $prequest->pr_no }}</h2>
                <h4 class="text-secondary"><strong>{{ $prequest->project->name }}</strong></h4>
                <h4 class="text-secondary">
                    <strong>{{ $prequest->is_task == 0 ? 'Retail' : $prequest->partof }}</strong>
                </h4>

                @foreach (['danger', 'warning', 'success', 'info'] as $key)
                    @if (Session::has($key))
                        <div class="alert alert-{{ $key }} alert-dismissible fade show mb-1 mt-1" role="alert">
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
            <div class="mb-3">
                @if ($setting->boq || (!$setting->boq && $project->boq))
                    <div class="d-flex justify-content-between">
                        <div>
                            <button class="btn btn-outline-primary" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapse" aria-expanded="false" aria-controls="collapse">
                                Special Note
                            </button>
                            <a target="_blank" href="{{ route('task-monitoring.index', $task->id) }}"
                                class="btn btn-success">Project BOQ</a>
                        </div>
                        <div class="w-25">
                            <input type="text" wire:model.debounce.500ms="search" placeholder="Search Items..."
                                class="form-control border border-black mb-2 rounded-5" />
                        </div>
                    </div>
                    <div class="collapse mt-3" id="collapse">
                        <div class="alert alert-primary mb-0" role="alert">
                            <ul class="mb-0">
                                <li>Jika <strong>"Quantity in PR"</strong> jumlahnya sudah sama dengan yang ada
                                    di
                                    <strong>"Quantity in BOQ"</strong>, maka item tidak bisa ditambah lagi.
                                </li>
                                <li>Untuk bisa membuat PR baru atau menambah jumlah total item di PR, anda perlu
                                    menyesuaikan jumlah <strong>"Quantity in BOQ"</strong> yang ada di
                                    <a target="_BLANK" href="{{ route('task-monitoring.index', $task->id) }}"
                                        class="text-decoration-none alert-primary"><strong>Project BOQ</strong>
                                    </a>.
                                </li>
                            </ul>
                        </div>
                    </div>
                @endif
            </div>
            <div class="col-lg-12">
                <div class="overflow-x-max">
                    <table class="table primary-box-shadow">
                        <thead class="thead-light">
                            <tr class="table-secondary fw-semibold">
                                <th class="align-middle text-center border-top-left" style="width: 30%">Item</th>
                                <th class="align-middle text-center">Quantity</th>
                                <th class="align-middle text-center">Unit</th>
                                <th class="align-middle text-center" style="width: 20%">Usage Date</th>
                                <th class="align-middle text-center border-top-right" style="width: 20%">Specification
                                </th>
                            </tr>
                        </thead>
                        @forelse ($items as $index => $item)
                            @php
                                $exist = false;
                            @endphp
                            @foreach ($itemsarray as $element)
                                @if ($element['id'] == $item->item_id)
                                    @php
                                        $exist = true;
                                    @endphp
                                    @break;
                                @endif
                            @endforeach
                            <tr>
                                <td class="align-middle">{{ $item->item->name }}
                                    @if($item->is_bulk)
                                        <br>
                                        <span class="badge badge-primary">Bulked in PO</span>
                                    @endif
                                </td>
                                <td class="align-middle text-end">
                                    <input type="text" wire:model.defer="qty.{{ $index }}"
                                        class="form-control">
                                </td>
                                <td class="align-middle text-end">
                                    <input type="text" value="{{ $item->unit->name }}" class="form-control" readonly
                                        disabled>
                                </td>
                                <td class="align-middle text-end">
                                    <input type="date" class="form-control" value="{{ $item->estimation_date }}">
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
