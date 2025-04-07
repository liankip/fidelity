@extends('layouts.app')

@section('content')
     <h1>
          Finished Projects Report</h1>
     <div class="p-5 bg-white rounded">
          <table class="table table-bordered" id="itemTable">
               <thead class="text-center" style="background-color: #d0def7;">
                    <tr>
                         <th scope="col">No</th>
                         <th scope="col">Project Name</th>
                         <th scope="col">Project Budget</th>
                         <th scope="col">BOQ Estimation</th>
                         <th scope="col">Qty Item Request</th>
                         <th scope="col">Qty Item Dibelanjakan</th>
                         <th scope="col">Action</th>
                    </tr>
               </thead>
               <tbody>
                    @foreach ($projects as $project)
                         <tr>
                              <td>{{ $loop->iteration }}</td>
                              <td>{{ $project->name }}</td>
                              <td>{{ rupiah_format($project->value) }}</td>
                              <td>
                                   @if ($project->boqs && !$project->boqs->isEmpty())
                                        {{ rupiah_format($project->boqs->first()->total_price_estimation) }}
                                   @else
                                        {{ rupiah_format(0) }}
                                   @endif
                              </td>
                              <td>
                                   @php
                                        $notapprovedCount = 0;
                                        $totalQty = 0;
                                   @endphp
                                   @foreach ($project->purchase_orders as $purchaseOrder)
                                        @if ($purchaseOrder->status !== 'Approved')
                                             @php
                                                  $notapprovedCount++;
                                             @endphp
                                             @foreach ($purchaseOrder->podetail as $podetail)
                                                  @php
                                                       $totalQty += $podetail->qty;
                                                  @endphp
                                             @endforeach
                                        @endif
                                   @endforeach
                                   <strong>{{ $totalQty }}</strong>
                              </td>
                              <td>
                                   @php
                                        $Approved = 0;
                                        $totalQty = 0;
                                   @endphp

                                   @foreach ($project->purchase_orders as $purchaseOrder)
                                        @if ($purchaseOrder->status === 'Approved')
                                             @php
                                                  $Approved++;
                                             @endphp

                                             @foreach ($purchaseOrder->podetail as $podetail)
                                                  @php
                                                       $totalQty += $podetail->qty;
                                                  @endphp
                                             @endforeach
                                        @endif
                                   @endforeach
                                   <strong>{{ $totalQty }}</strong>
                              </td>
                              <td> <a href="{{ route('projects.reportsExport', ['projectId' => $project->id]) }}"
                                        class="btn btn-secondary">Export</a>
                              </td>
                         </tr>
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
@endsection
