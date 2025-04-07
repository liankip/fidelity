<div class="">
    <div>
        <div>
            <div>
                <h2>List Item's of PR: {{ $prequest->pr_no }}</h2>
                <h4 class="text-secondary"><strong>{{ $prequest->project->name }}</strong></h4>
                <hr>

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

    <div class="row justify-content-center mt-5">
        
    <div class="col-lg-12">
        <a class="btn btn-info mb-4" href="{{ route('itempr.addItem', $prid) }}">Add Item</a>
        <div class="row">
            <div class="col-lg-12 margin-tb">
                
                <div class="overflow-x-max">
                    
                    <table class="table table-bordered">
                        <tr class="table-secondary fw-semibold">
                            <td class="text-center" style="font-size: 12px; width: 5%">#</td>
                            <td class="text-center" style="font-size: 12px; width: 25%">Item Name</td>
                            <td class="text-center" style="font-size: 12px; width: 10%">Qty</td>
                            <td class="text-center" style="font-size: 12px; width: 10%">Unit</td>
                            <td class="text-center" style="font-size: 12px; width: 15%">Usage Date</td>
                            <td class="text-center" style="font-size: 12px; width: 25%">Specification</td>
                            <td class="text-center" style="font-size: 12px; width: 10%">Action</td>
                        </tr>

                        @if (count($itemsarray))
                            @foreach ($itemsarray as $key => $item)
                                @if ($setting->boq || (!$setting->boq && $project->boq))
                                    @if ($itemsarray[$key]['max_item'] > 0)
                                        <tr wire:key='{{ $key }}'>
                                            <td class="text-center">{{ $key + 1 }}</td>
                                            <td>
                                                <div>{{ $item['name'] }}</div>

                                            </td>
                                            <td style="min-width: 100px">
                                                <input class="form-control"
                                                    wire:model="itemsarray.{{ $key }}.count"
                                                    wire:change="updateqty({{ $key }},{{ $item['id'] }})"
                                                    @if (isset($itemsarray[$key]['min_item'])) min="{{ $itemsarray[$key]['min_item'] }}" @endif
                                                    type="number" max="{{ $itemsarray[$key]['max_item'] }}"
                                                    step="1">
                                                @error('itemsarray.' . $key . '.count')
                                                    <span class="error text-danger fs-tiny">{{ $message }}</span>
                                                @enderror
                                            </td>
                                            <td>
                                                <input type="hidden"
                                                    wire:model="itemsarray.{{ $key }}.unit"
                                                    value="{{ $item['id'] }}">
                                                <input type="text" value="{{ $itemsarray[$key]['unit'] }}"
                                                    class="form-control" readonly disabled>
                                            </td>
                                            <td>
                                                <input type="datetime-local" name="estimation_date"
                                                    class="form-control"
                                                    wire:model="itemsarray.{{ $key }}.estimation_date"
                                                    wire:blur="updateestimationdate({{ $key }})">
                                            </td>
                                            <td style="min-width: 120px">
                                                <textarea name="" class="form-control" wire:model="itemsarray.{{ $key }}.note"
                                                    wire:blur="updatenote({{ $key }})" cols="8" rows="5"></textarea>
                                            </td>
                                        @else
                                        <tr wire:key='{{ $key }}'>
                                            <td class="text-center">{{ $key + 1 }}</td>
                                            <td>
                                                <div>{{ $item['name'] }}</div>

                                            </td>
                                            <td style="min-width: 100px">
                                                <input class="form-control"
                                                    wire:model="itemsarray.{{ $key }}.count"
                                                    wire:change="updateqty({{ $key }},{{ $item['id'] }})"
                                                    @if (isset($itemsarray[$key]['min_item'])) min="{{ $itemsarray[$key]['min_item'] }}" @endif
                                                    type="number" max="{{ $itemsarray[$key]['max_item'] }}"
                                                    step="1">
                                                @error('itemsarray.' . $key . '.count')
                                                    <span class="error text-danger fs-tiny">{{ $message }}</span>
                                                @enderror
                                            </td>
                                            <td>
                                                <input type="hidden"
                                                    wire:model="itemsarray.{{ $key }}.unit"
                                                    value="{{ $item['id'] }}">
                                                <input type="text" value="{{ $itemsarray[$key]['unit'] }}"
                                                    class="form-control" readonly disabled>
                                            </td>
                                            <td>
                                                <input type="datetime-local" name="estimation_date"
                                                    class="form-control"
                                                    wire:model="itemsarray.{{ $key }}.estimation_date"
                                                    wire:blur="updateestimationdate({{ $key }})">
                                            </td>
                                            <td style="min-width: 120px">
                                                <textarea name="" class="form-control" wire:model="itemsarray.{{ $key }}.note"
                                                    wire:blur="updatenote({{ $key }})" cols="8" rows="5"></textarea>
                                            </td>
                                    @endif
                                @else
                                    <tr wire:key='{{ $key }}'>
                                        <td class="text-center">{{ $key + 1 }}</td>
                                        <td>{{ $item['name'] }}</td>
                                        <td style="min-width: 100px">
                                            <input class="form-control"
                                                wire:model="itemsarray.{{ $key }}.count"
                                                wire:change="updateqty({{ $key }},{{ $item['id'] }})"
                                                type="number" step="1">
                                        </td>
                                        <td>
                                            <select class="form-select"
                                                wire:model="itemsarray.{{ $key }}.unit"
                                                wire:change="update_unit({{ $key }})">
                                                @foreach (App\Models\ItemUnit::where('item_id', $item['id'])->get() as $item_data)
                                                    <option value="{{ $item_data->unit->name }}"
                                                        {{ $item['unit'] == $item_data->unit->name ? 'selected' : '' }}>
                                                        {{ $item_data->unit->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <input type="datetime-local" name="estimation_date"
                                                class="form-control"
                                                wire:model="itemsarray.{{ $key }}.estimation_date"
                                                wire:blur="updateestimationdate({{ $key }})">
                                        </td>
                                        <td style="min-width: 120px">
                                            <textarea name="" class="form-control" wire:model="itemsarray.{{ $key }}.note"
                                                wire:blur="updatenote({{ $key }})" cols="8" rows="5"></textarea>
                                        </td>
                                @endif

                                <td class="text-center">
                                    <button class="btn btn-danger" data-bs-toggle="tooltip"
                                        data-bs-placement="top" data-bs-title="Delete Item"
                                        style="padding-left: 10px;padding-right: 10px; padding-bottom: 4px;padding-top: 4px"
                                        @if (isset($itemsarray[$key]['min_item']) && $itemsarray[$key]['min_item'] > 0) disabled @endif
                                        wire:click="removeitem({{ $key }})">
                                        <i class="fa-solid fa-trash"></i> Delete
                                    </button>

                                </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="7" class="text-center">No previously added items</td>
                            </tr>
                        @endif
                    </table>
                </div>

                <a class="btn btn-success" href="{{ url('/prtest') }}">Selesai</a>

            </div>
        </div>
    </div>
</div>
@if ($showadditem)
    @include('components.modaladditem')
@endif

</div>
