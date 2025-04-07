<div class="bg-white p-3">
    <input wire:model="search" type="text" placeholder="Search by item name" class="form-control border border-black">

    {{-- <div class="mt-5 bg-white p-3 rounded"> --}}
        <table class="table table-bordered mt-2">
            <thead class="text-center" style="background-color: #d0def7;">
                <tr>
                    <th>No</th>
                    <th>Item Name</th>
                    <th>Project Name</th>
                    <th>Quantity</th>
                    <th>Satuan</th>
                </tr>
            </thead>
            <tbody>
                @php $index = 1; @endphp
                @foreach($groupedItem as $itemName => $projects)
                    @php $firstProject = true; @endphp
                    @foreach($projects as $projectName => $itemDetails)
                        <tr>
                            <td style="background-color: #edf2fb;" class="border border-black">{{ $firstProject ? $index++ : '' }}</td>
                            <td>{{ $firstProject ? $itemName : '' }}</td>
                            <td>{{ $projectName }}</td>
                            <td>{{ number_format($itemDetails['quantity']) }}</td>
                            <td>{{ $itemDetails['unit'] }}</td>
                        </tr>
                        @php $firstProject = false; @endphp
                    @endforeach
                @endforeach
            </tbody>
        </table>
    {{-- </div> --}}

</div>
