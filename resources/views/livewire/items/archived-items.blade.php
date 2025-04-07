<div class="card primary-box-shadow">
    {{-- <form action="{{ route('items.import') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="file" name="file" class="form-control">
        <br>
        <button class="btn btn-success">Upload CSV</button>
    </form> --}}
    <div class="card-body">

        <div class="d-flex mt-3">
            <div class="w-100">
                <form action="" method="get" class="">
                    <div class="input-group">
                        <input type="text" class="form-control" name="search" placeholder="Search"
                               wire:model="searchcompact">

                    </div>
                </form>
            </div>
        </div>

        <hr>
        <div class="overflow-x-max">
            <table class="table mt-3">
                <tr class="thead-light">
                    <th class="text-center border-top-left">Kode barang</th>
                    <th class="text-center">Gambar</th>
                    <th class="text-center">Nama Barang</th>
                    <th class="text-center">Merk</th>
                    <th class="text-center">Kategori Barang</th>
                    <th class="text-center">Jenis Barang</th>
                    <th class="text-center">Satuan</th>
                    <th class="text-center">Created At</th>
                    <th class="text-center border-top-right">Action</th>
                </tr>
                @foreach ($items as $key => $item)
                    <tr>
                        <td class="text-center">{{ $item->item_code }}</td>
                        <td class="text-center">
                            <img
                                src={{ $item->image == 'images/no_image.png' ? url($item->image) : 'storage/' . $item->image }} alt=""
                                width="100 px">
                        </td>
                        <td>{{ $item->name }}</td>
                        <td class="text-center">{{ $item->brand ? $item->brand : '-' }}</td>
                        <td>{{$item->category ? $item->category->name : "-"}}</td>
                        <td class="text-uppercase">{{$item->type}}</td>
                        <td>
                            @forelse($item->item_unit as $unit)
                                <div>
                                    - {{ $unit->unit->name }}
                                </div>
                            @empty
                            @endforelse
                        </td>
                        @if ($item->created_at)
                            <td>
                                <div>{{ $item->created_at->format('d F Y - H:i') }}</div>
                                <div><em>{{ $item->created_by ? $item->item_created_by->name : '' }}</em>
                                </div>
                            </td>
                        @else
                            <td>{{ $item->created_at }}</td>
                        @endif
                        <td class="text-center">
                            <button wire:click="restore({{$item->id}})" class="btn btn-sm btn-primary mt-1">
                                Restore
                            </button>
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>
        <div class="mt-4">
            {{ $items->links() }}
        </div>
    </div>
</div>
