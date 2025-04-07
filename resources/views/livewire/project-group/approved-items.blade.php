<h3 class="text-secondary"><strong>Group {{ $groupName }}</strong></h3>
<h4 class="text-secondary">Approved Items</h4>
<div class="p-5 bg-white rounded">

    <table class="table table-bordered" id="itemTable">
     <thead class="text-center" style="background-color: #d0def7;">
          <tr>
              <th scope="col">No</th>
              <th scope="col">Item Name</th>
              <th scope="col">Project Name</th>
              <th scope="col">Total Quantity</th>
              <th scope="col">Unit</th>
              <th scope="col">PO List</th>
          </tr>
      </thead>
      <tbody>
          @php
              $counter = 0;
          @endphp
          @foreach ($groupedItem as $itemName => $itemData)
              @if (!empty($itemData['poList']))
                  <tr>
                      <th scope="row" class="text-center" style="background-color: #edf2fb;">
                          {{ ++$counter }}
                      </th>
                      <td>{{ $itemName }}</td>
                      <td>
                         @foreach ($itemData['projectNames'] as $project )
                              <p>{{ $project }}</p>
                         @endforeach
                      </td>
                      <td>{{ $itemData['quantity'] }}</td>
                      <td>{{ $itemData['unit'] }}</td>
                      <td>
                          @foreach ($itemData['poList'] as $po)
                              <p>
                                  <a href="{{ route('po-detail', $po['po_id']) }}" target="_blank">{{ $po['po_no'] }}</a>
                              </p>
                          @endforeach
                      </td>
                  </tr>
              @endif
          @endforeach
      </tbody>
      
    </table>

    <script>
        $(document).ready(function() {
             const dTable = new DataTable('#itemTable', {
                  ordering: false,
             });
        });
   </script>
</div>
