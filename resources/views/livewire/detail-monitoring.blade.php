<div class="">
     <h1>Monitoring Purchase Request</h1>
     <div class="card p-4">
          <div class="row">
               <div class="col-md-8">
                    <p><strong>PROJECT:</strong> {{ $prData->project->name }}</p>
                    <p><strong>Task No: {{ $taskNo }}</strong></p>
               </div>
               <div class="col-md-4 text-start">
                    <p><strong>No:</strong> {{ $prData->pr_no }}</p>
               </div>
          </div>
          <div class="row">
               <div class="col-md-6">
                    <p><strong>Task Name: {{ $taskName }}</strong></p>
               </div>
          </div>
     </div>

     @if (Session::has('success'))
          <div class="alert alert-success alert-dismissible fade show" role="alert">
               <p>{{ Session::get('success') }}</p>
               <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
     @endif

     <div class="card p-4">
          <div>
               <input type="text" wire:model="searchTerm" placeholder="Search by item name"
                    class="form-control mb-3" />
          </div>
          <table class="table table-bordered text-center table-responsive">
               <thead class="table-light">
                    <tr>
                         {{-- <th>No</th> --}}
                         <th>Item</th>
                         <th>RFA</th>
                         <th colspan="2">Requested</th>
                         <th rowspan="2" class="align-middle">PO</th>
                         <th>Value</th>
                         <th>Delivery</th>
                         <th colspan="2">Actual</th>
                         <th colspan="2">Waste</th>
                         <th>Site Check</th>
                         <th>Relocate Waste to</th>
                    </tr>
                    <tr>
                         {{-- <th></th> --}}
                         <th></th>
                         <th></th>
                         <th>Qty</th>
                         <th>Unit</th>
                         <th></th>
                         <th>Date</th>
                         <th>Qty</th>
                         <th>Unit</th>
                         <th>Qty</th>
                         <th>Unit</th>
                         <th></th>
                         <th>Task No</th>
                    </tr>
               </thead>

               <tbody>
                    @php
                         $groupedPoDetails = collect($poDetailList)->groupBy(function ($item) {
                             return $item['po']['id'];
                         });
                    @endphp
                    @foreach ($groupedPoDetails as $poId => $groupedDetails)
                         @foreach ($groupedDetails as $index => $poDetail)
                              <tr>
                                   {{-- <td>{{ $loop->iteration }}</td> --}}
                                   <td>{{ $poDetail['item']['name'] }}</td>
                                   <td>
                                        @php
                                             $buttonClass = $poDetail['is_rfa_exist']
                                                 ? 'badge bg-success'
                                                 : 'badge bg-danger';
                                             $textValue = $poDetail['is_rfa_exist'] ? 'Ada' : 'Belum';
                                        @endphp
                                        <span class=" {{ $buttonClass }} text-white">{{ $textValue }}</span>
                                   </td>
                                   <td>{{ $poDetail['qty'] }}</td>
                                   <td>{{ $poDetail['unit'] }}</td>
                                   @if ($loop->first)
                                        <td rowspan="{{ $groupedDetails->count() }}" class="align-middle">
                                             {{ $poDetail['po']['po_no'] }}
                                        </td>
                                   @endif

                                   <td>Rp. {{ number_format($poDetail['amount']) }}</td>
                                   {{-- Delivery Order --}}
                                   @if ($loop->first && $poDetail['po'])
                                        <td rowspan="{{ $groupedDetails->count() }}" class="align-middle">
                                             @if ($poDetail['po']->totalDo() > 0)
                                                  @foreach ($poDetail['po']->do as $do)
                                                       <span
                                                            class="badge bg-success">{{ $do->created_at->format('j M Y') }}</span>
                                                  @endforeach
                                             @else
                                                  <a href="{{ route('create_do', ['id' => $poDetail['po']->id]) }}"
                                                       class="btn btn-primary">Upload Surat Jalan</a>
                                             @endif
                                        </td>
                                   @endif

                                   {{-- Check Surat Jalan & Upload Foto --}}
                                   @if ($poDetail['po']->totalDo() > 0)
                                        @php
                                             $itemSubmitted = false;
                                        @endphp

                                        {{-- Check PO Submition --}}
                                        @if ($poDetail['po']->hasSubmition())
                                             @foreach ($poDetail['po']->submition as $submition)
                                                  {{-- Display Item based on Inventory Details --}}
                                                  @foreach ($inventoryItems as $inventoryItem)
                                                       @if ($poDetail['item']['id'] == $submition->item_id && $inventoryItem->inventory->item->id == $poDetail['item']['id'])
                                                            @php
                                                                 $itemSubmitted = true;
                                                            @endphp

                                                            <td>{{ $poDetail['qty'] }}</td>
                                                            <td>{{ $inventoryItem->inventory->item->unit }}</td>
                                                       @endif
                                                  @endforeach
                                             @endforeach

                                             {{-- If Item Has no Submition --}}
                                             @if (!$itemSubmitted)
                                                  <td colspan="2">
                                                       <form action="{{ route('create_submition', $poDetail->id) }}"
                                                            method="post">
                                                            @csrf
                                                            @method('get')
                                                            <button type="submit"
                                                                 class="btn btn-sm btn-primary w-100">Upload
                                                                 Foto
                                                            </button>
                                                       </form>
                                                  </td>
                                             @endif
                                        @else
                                             <td colspan="2">
                                                  <form action="{{ route('create_submition', $poDetail->id) }}"
                                                       method="post">
                                                       @csrf
                                                       @method('get')
                                                       <button type="submit"
                                                            class="btn btn-sm btn-primary w-100">Upload
                                                            Foto
                                                       </button>
                                                  </form>
                                             </td>
                                        @endif
                                   @else
                                        <td colspan="2">
                                             <span class="badge bg-danger">Harap upload surat jalan</span>
                                        </td>
                                        <td>-</td>
                                        <td>-</td>
                                        <td>-</td>
                                        <td>-</td>
                                   @endif

                                   {{-- Waste & Site Check --}}
                                   @if ($poDetail['po']->hasSubmition())
                                        @php
                                             $itemExist = false;
                                        @endphp

                                        @foreach ($poDetail['po']->submition as $submition)
                                             {{-- Display Item based on Inventory Details --}}
                                             @foreach ($inventoryItems as $inventoryItem)
                                                  @if ($poDetail['item']['id'] == $submition->item_id && $inventoryItem->inventory->item->id == $poDetail['item']['id'])
                                                       @php
                                                            $itemExist = true;

                                                            $projectId = $prData->project->id;
                                                            $prId = $prData->id;
                                                            $itemId = $poDetail['item']['id'];

                                                            $siteUploaded = \App\Models\SiteCheckModel::where(
                                                                'project_id',
                                                                $projectId,
                                                            )
                                                                ->where('pr_id', $prId)
                                                                ->where('item_id', $itemId)
                                                                ->first();
                                                       @endphp

                                                       <td>{{ $poDetail['qty'] - $inventoryItem->inventory_outs->sum('out') }}
                                                       </td>
                                                       <td>{{ $inventoryItem->inventory->item->unit }}</td>

                                                       {{-- Check Site Upload --}}
                                                       @if ($siteUploaded)
                                                            @php
                                                                 $jsonFileUpload = json_decode(
                                                                     $siteUploaded->file_upload,
                                                                     true,
                                                                 );
                                                            @endphp
                                                            <td><button type="button" class="btn btn-primary"
                                                                      data-bs-toggle="modal"
                                                                      data-bs-target="#detailModal-{{ $poDetail['id'] }}">
                                                                      Detail
                                                                 </button></td>

                                                            <!-- Detail Modal -->
                                                            <div class="modal fade"
                                                                 id="detailModal-{{ $poDetail['id'] }}" tabindex="-1"
                                                                 aria-labelledby="exampleModalLabel" aria-hidden="true"
                                                                 wire:key="{{ $poDetail['id'] }}">
                                                                 <div class="modal-dialog">
                                                                      <div class="modal-content">
                                                                           <div class="modal-header">
                                                                                <h1 class="modal-title fs-5"
                                                                                     id="exampleModalLabel">Detail
                                                                                     {{ $poDetail['item']->name }}
                                                                                </h1>
                                                                                <button type="button" class="btn-close"
                                                                                     data-bs-dismiss="modal"
                                                                                     aria-label="Close"></button>
                                                                           </div>
                                                                           <div class="modal-body">
                                                                                <div class="mb-3">
                                                                                     <h5 class="fw-bold">Nama PIC</h5>
                                                                                     <p class="text-muted">
                                                                                          {{ $siteUploaded->name }}</p>
                                                                                </div>

                                                                                <div class="mb-3">
                                                                                     <h5 class="fw-bold">Keterangan</h5>
                                                                                     <p class="text-muted">
                                                                                          {{ $siteUploaded->description }}
                                                                                     </p>
                                                                                </div>

                                                                                <div class="mb-3">
                                                                                     <h5 class="fw-bold">Foto</h5>
                                                                                     <img src="{{ Storage::url($jsonFileUpload['filePath']) }}"
                                                                                          alt="Image"
                                                                                          class="img-fluid rounded shadow-sm">
                                                                                </div>
                                                                           </div>

                                                                           <div class="modal-footer">
                                                                                <button type="button"
                                                                                     class="btn btn-secondary"
                                                                                     data-bs-dismiss="modal">Close</button>
                                                                           </div>
                                                                      </div>
                                                                 </div>
                                                            </div>
                                                       @else
                                                            <td><button class="btn btn-sm btn-primary"
                                                                      data-bs-toggle="modal"
                                                                      data-bs-target="#exampleModal-{{ $poDetail['id'] }}">Upload
                                                                      Form</button></td>
                                                       @endif
                                                       <td>
                                                            {{-- Check Task List --}}
                                                            @if ($taskList->count() > 0)
                                                                 <button class="btn btn-sm btn-info"
                                                                      data-bs-toggle="modal"
                                                                      data-bs-target="#relocateModal-{{ $poDetail['id'] }}">Relocate</button>

                                                                 {{-- Relocate Modal --}}
                                                                 <div class="modal fade"
                                                                      id="relocateModal-{{ $poDetail['id'] }}"
                                                                      tabindex="-1" aria-labelledby="exampleModalLabel"
                                                                      aria-hidden="true"
                                                                      wire:key="{{ $poDetail['id'] }}" wire:ignore>
                                                                      <div class="modal-dialog">
                                                                           <form wire:submit.prevent="handleRelocate({{ $poDetail }})">
                                                                                <div class="modal-content">
                                                                                     <div class="modal-header">
                                                                                          <h1 class="modal-title fs-5"
                                                                                               id="exampleModalLabel">
                                                                                               Relocate -
                                                                                               <span class="fw-light">
                                                                                                    {{ $poDetail['item']->name }}
                                                                                               </span>
                                                                                          </h1>
                                                                                          <button type="button"
                                                                                               class="btn-close"
                                                                                               data-bs-dismiss="modal"
                                                                                               aria-label="Close"></button>
                                                                                     </div>
                                                                                     <div class="modal-body">
                                                                                          <div class="row">
                                                                                               <div class="col-md-6">
                                                                                                    <div
                                                                                                         class="form-group">
                                                                                                         <label
                                                                                                              for="fromsite">From</label>
                                                                                                         <input
                                                                                                              type="text"
                                                                                                              class="form-control"
                                                                                                              id="fromsite"
                                                                                                              value="{{ $taskName }}"
                                                                                                              readonly>
                                                                                                    </div>
                                                                                               </div>
                                                                                               <div class="col-md-6">
                                                                                                    <div
                                                                                                         class="form-group">
                                                                                                         <label
                                                                                                              for="tosite">To</label>
                                                                                                         <select
                                                                                                              name="tosite"
                                                                                                              id="tosite"
                                                                                                              wire:model="relocateTo"
                                                                                                              class="form-control" required>
                                                                                                              <option
                                                                                                                   value="">
                                                                                                                   --
                                                                                                                   Pilih
                                                                                                                   Task
                                                                                                                   --
                                                                                                              </option>
                                                                                                              @foreach ($taskList as $task)
                                                                                                                   <option
                                                                                                                        value="{{ $task->id }}">
                                                                                                                        {{ $task->task->task }}
                                                                                                                   </option>
                                                                                                              @endforeach
                                                                                                         </select>
                                                                                                    </div>
                                                                                               </div>
                                                                                          </div>
                                                                                     </div>
                                                                                     <div class="modal-footer">
                                                                                          <button type="button"
                                                                                               class="btn btn-secondary"
                                                                                               data-bs-dismiss="modal">Close</button>
                                                                                               <button type="submit"
                                                                                               class="btn btn-primary"
                                                                                               >Save changes</button>
                                                                                     </div>
                                                                                </div>
                                                                           </form>
                                                                      </div>
                                                                 </div>
                                                                 {{-- Relocate Modal --}}
                                                            @else
                                                                 -
                                                            @endif

                                                       </td>

                                                       <!-- Upload Form Modal -->
                                                       <div class="modal fade"
                                                            id="exampleModal-{{ $poDetail['id'] }}" tabindex="-1"
                                                            aria-labelledby="exampleModalLabel" aria-hidden="true"
                                                            wire:key="{{ $poDetail['id'] }}" wire:ignore>
                                                            <form
                                                                 wire:submit.prevent="handleUpload({{ $poDetail['item']->id }})">
                                                                 <div class="modal-dialog">
                                                                      <div class="modal-content">
                                                                           <div class="modal-header">
                                                                                <h1 class="modal-title fs-5"
                                                                                     id="exampleModalLabel">
                                                                                     {{ $poDetail['item']->name }}</h1>
                                                                                <button type="button"
                                                                                     class="btn-close"
                                                                                     data-bs-dismiss="modal"
                                                                                     aria-label="Close"></button>
                                                                           </div>
                                                                           <div class="modal-body">
                                                                                <div class="form-group">
                                                                                     <label for="nama">Nama
                                                                                          PIC<span
                                                                                               class="text-danger">*</span></label>
                                                                                     <input type="text"
                                                                                          class="form-control"
                                                                                          id="nama" required
                                                                                          wire:model="nameModel">
                                                                                </div>
                                                                                <div class="form-group">
                                                                                     <label for="keterangan">Keterangan
                                                                                          <span
                                                                                               class="text-danger">*</span></label>
                                                                                     <textarea class="form-control" id="keterangan" required wire:model="descModel"></textarea>
                                                                                </div>
                                                                                <div class="form-group">
                                                                                     <label for="uploadFile">Upload
                                                                                          foto
                                                                                          <span
                                                                                               class="text-danger">*</span></label>
                                                                                     <input type="file"
                                                                                          class="form-control"
                                                                                          id="uploadFile" required
                                                                                          wire:model="uploadModel"
                                                                                          accept=".pdf,.jpg,.jpeg,.png">
                                                                                     <p class="text-muted" wire:loading
                                                                                          wire:target="uploadModel">
                                                                                          Uploading...</p>
                                                                                </div>

                                                                           </div>
                                                                           <div class="modal-footer">
                                                                                <button type="button"
                                                                                     class="btn btn-secondary"
                                                                                     data-bs-dismiss="modal">Close</button>
                                                                                <button type="submit"
                                                                                     class="btn btn-primary"
                                                                                     wire:loading.attr="disabled">Save
                                                                                     changes</button>
                                                                           </div>
                                                                      </div>
                                                                 </div>
                                                            </form>
                                                       </div>
                                                  @endif
                                             @endforeach
                                        @endforeach

                                        @if (!$itemExist)
                                             <td>-</td>
                                             <td>-</td>
                                             <td>-</td>
                                             <td>-</td>
                                        @endif
                                   @endif
                                   {{-- Waste & Site Check --}}

                              </tr>
                         @endforeach
                    @endforeach

               </tbody>

          </table>
     </div>
</div>
