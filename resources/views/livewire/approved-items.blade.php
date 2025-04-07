<h3 class="text-secondary"><strong>{{ $project->name }}</strong></h3>
<h4 class="text-secondary">Approved Items</h4>
<div class="p-5 bg-white rounded">
     <table class="table table-bordered" id="itemTable">
          <thead class="text-center" style="background-color: #d0def7;">
               <tr>
                    <th scope="col">No</th>
                    <th scope="col">Item Name</th>
                    <th scope="col">PO List</th>
               </tr>
          </thead>
          <tbody>
               @php
                    $counter = 0;
               @endphp
               @foreach ($boqList as $index => $boq)
                    @php
                         $po_status = $boq->po_status ?? null;
                    @endphp
                    @if (
                        $po_status &&
                            auth()->user()->hasAnyRole('it|top-manager|manager|purchasing'))
                         @php
                              $counter++;
                         @endphp
                         <tr>
                              <th scope="row" class="text-center" style="background-color: #edf2fb;">{{ $counter }}</th>
                              <td>
                                   <div>
                                        <p class="text-success">{{ $boq->item->name }}</p>
                                        <p class="text-success">PO Approved: {{ (int) $po_status['qty_total'] }} /
                                             {{ (int) $boq->qty }}</p>

                                   </div>
                              </td>
                              <td>
                                   <div>

                                        @foreach ($po_status['list'] as $po)
                                             <p>
                                                  <a href="{{ route('po-detail', $po['po_id']) }}"
                                                       target="_blank">{{ $po['po_no'] }}</a>
                                             </p>
                                        @endforeach

                                   </div>

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
