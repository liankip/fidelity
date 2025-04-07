<div>
    <div class="d-flex justify-content-between align-items-center mb-3 gap-2">
        <input type="text" wire:model.debounce.500ms="search" placeholder="Search by item name" class="form-control">

        @if(count($Items) > 0)
            <button class="btn btn-primary" wire:click="continueHandler">Continue</button>
        @endif

    </div>

     <table class="table primary-box-shadow table-bordered">
          <thead class="thead-light">
               <tr class="table-secondary">
                    <th width="5%"></th>
                    <th>Item Name</th>
               </tr>
          </thead>

          <tbody>
               @foreach ($itemData as $item)
                    @php
                        $isInArray = in_array($item->id, $Items);
                    @endphp
                    <tr class="{{ $isInArray ? 'table-secondary' : '' }}">
                         <td>
                            <input class="form-check-input" type="checkbox" wire:model="Items" value="{{ $item->id }}">
                         </td>
                         <td>{{ $item->name }}</td>
                    </tr>
               @endforeach
          </tbody>
     </table>
     {{ $itemData->links() }}
</div>
