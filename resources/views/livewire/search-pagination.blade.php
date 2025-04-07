<div class="container">
    <div class="row">
        <div class="col-md-12">

            <input type="text"  class="form-control" placeholder="Search" wire:model="searchTerm" />

            <table class="table table-bordered" style="margin: 10px 0 10px 0;">
                <tr>
                    <th>Name</th>
                    <th>Type</th>
                </tr>
                @foreach($items as $item)
                <tr>
                    <td>
                        {{ $item->name }}
                    </td>
                    <td>
                        {{ $item->type }}
                    </td>
                </tr>
                @endforeach
            </table>
            {{ $items->links() }}
        </div>
    </div>
</div>
