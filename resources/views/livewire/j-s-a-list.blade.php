<div>
     <h2>JSA Detail</h2>
     <h3>{{ $jsaTitle }}</h3>

     <div class="p-4 bg-white">
          <form class="row" wire:submit.prevent="handlePost">
               @csrf
               @foreach ($tes as $tesIndex => $item)
                    <div class="col-xs-12 col-sm-12 col-md-4 mb-4">
                         <div class="form-group">
                              <strong>Urutan Dasar Langkah Kerja {{ $tesIndex + 1 }}</strong>
                              <span class="text-danger">*</span>
                              <br><br>
                              <div>
                                   <textarea wire:model="tes.{{ $tesIndex }}.urutan" class="form-control" placeholder="Masukkan Langkah Kerja" required></textarea>
                              </div>
                         </div>
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-4 mb-4">
                         <div class="form-group">
                              <strong>Risiko Yang Terkait</strong>
                              <span class="text-danger">*</span>
                              <div>
                                   @foreach ($item['risiko'] as $risikoIndex => $risiko)
                                        <strong>{{ $tesIndex + 1 }}.{{ $risikoIndex + 1 }}</strong>
                                        <textarea wire:model="tes.{{ $tesIndex }}.risiko.{{ $risikoIndex }}.risiko_item" class="form-control mb-1" required
                                             placeholder="Risiko {{ $tesIndex + 1 }}.{{ $risikoIndex + 1 }}"></textarea>
                                   @endforeach
                                   <button wire:click="addRisikoFieldTes({{ $tesIndex }})" class="btn btn-success"
                                        type="button">Add Risiko</button>
                                   @if ($risikoIndex > 0)
                                        <button wire:click="removeRisikoFieldTes({{ $tesIndex }})"
                                             class="btn btn-danger" type="button">Remove Field</button>
                                   @endif
                              </div>
                         </div>
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-4 mb-4">
                         <div class="form-group">
                              <strong>Tindakan atau Prosedur Pencegahan</strong>
                              <span class="text-danger">*</span>
                              <div>
                                   @foreach ($item['risiko'] as $risikoIndex => $risiko)
                                        @foreach ($risiko['tindakan'] as $tindakanIndex => $tindakan)
                                             <strong>{{ $tesIndex + 1 }}.{{ $risikoIndex + 1 }}.{{ $tindakanIndex + 1 }}</strong>
                                             <textarea wire:model="tes.{{ $tesIndex }}.risiko.{{ $risikoIndex }}.tindakan.{{ $tindakanIndex }}"
                                                  class="form-control mb-1" placeholder="Tindakan atas Risiko {{ $tesIndex + 1 }}.{{ $risikoIndex + 1 }}" required></textarea>
                                        @endforeach
                                        <div class="mb-4">

                                             <button
                                                  wire:click="addTindakanFieldTes({{ $tesIndex }}, {{ $risikoIndex }})"
                                                  class="btn btn-success" type="button">Add Tindakan</button>

                                             @if ($tindakanIndex > 0)
                                                  <button
                                                       wire:click="removeTindakanFieldTes({{ $tesIndex }}, {{ $risikoIndex }})"
                                                       class="btn btn-danger" type="button">Remove Tindakan</button>
                                             @endif
                                        </div>
                                   @endforeach
                              </div>
                         </div>
                    </div>
               @endforeach

               <button wire:click="addTes" class="btn btn-primary w-25" type="button">Add Input Field</button>
               @if (count($tes) > 1)
                    <button wire:click="removeStepTes" class="btn btn-danger w-25" type="button">Remove Latest Field</button>
               @endif
               <button class="btn btn-success mt-4">Submit</button>
          </form>

     </div>
</div>
