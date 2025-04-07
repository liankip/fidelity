<div>
     <h1>Create New JSA</h1>
     <form method="POST" enctype="multipart/form-data" class="bg-white p-4" action="{{ route('jsa-index.create') }}">
          @csrf
          <input type="hidden" value="{{ $jsaData->id ?? '' }}" name="jsa_id_placeholder">
          <div class="row">
               <div class="col">
                    <div class="col-xs-12 col-sm-12 col-md-12">
                         <div class="form-group">
                              <strong>Tanggal</strong>
                              <input type="text" name="jsa_date" class="form-control"
                              value="{{ isset($jsaData) ? \Carbon\Carbon::parse($jsaData->jsa_date)->format('d F Y') : \Carbon\Carbon::now()->format('d F Y') }}"
                              readonly>
                         </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12">
                         <div class="form-group">
                              <strong>Nomor dan Nama Pekerjaan</strong>
                              <span class="text-danger">*</span>
                              <div class="d-flex">
                                   <input type="number" class="form-control w-25"
                                        placeholder="Nomor" min="1" name="job_no" value="{{ $jsaData->job_no ?? '' }}">
                                   <input type="text" class="form-control"
                                        placeholder="Nama Pekerjaan" name="job_name" value="{{ $jsaData->job_name ?? '' }}">
                              </div>
                         </div>
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-12">
                         <div class="form-group">
                              <strong>Nomor dan Nama Jabatan</strong>
                              <div class="d-flex">
                                   <input type="number" class="form-control w-25"
                                        placeholder="Nomor" min="1" name="position_no" value="{{ $jsaData->position_no ?? '' }}">
                                   <input type="text" class="form-control"
                                        placeholder="Nama Jabatan" name="position_name" value="{{ $jsaData->position_name ?? '' }}">
                              </div>
                         </div>
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-12">
                         <div class="form-group">
                              <strong>Seksi / Departemen</strong>
                              <div>
                                   <input type="text" class="form-control" placeholder="Seksi / Departemen" name="section_department" value="{{ $jsaData->section_department ?? '' }}">
                              </div>
                         </div>
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-12">
                         <div class="form-group">
                              <strong>Jabatan Superior</strong>
                              <div>
                                   <input type="text" class="form-control" placeholder="Jabatan Superior" name="superior_position" value="{{ $jsaData->superior_position ?? '' }}">
                              </div>
                         </div>
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-12">
                         <div class="form-group">
                              <strong>Alat Pelindung Diri yang Harus Dipakai</strong>
                              <textarea class="form-control" placeholder="Notes" name="suggestion_notes" maxlength="400">{{ $jsaData->suggestion_notes ?? '-' }}</textarea>
                         </div>
                    </div>
               </div>

               
                    {{-- <div class="col-xs-12 col-sm-12 col-md-12">
                         <div class="form-group">
                              <strong>No JSA</strong>
                              <input type="text" class="form-control" disabled>
                         </div>
                    </div> --}}
                    <div class="col-xs-12 col-sm-12 col-md-12">
                         <div class="form-group">
                              <strong>Lokasi Kerja</strong>
                              <div class="d-flex gap-2">
                                   <input type="text" class="form-control" placeholder="Lokasi Kerja" name="job_location" value="{{ $jsaData->job_location ?? '' }}">
                              </div>
                         </div>
                    </div>

                    <div class="col-xs-3 col-sm-3 col-md-3">
                         <div class="form-group">
                             <strong>Upload file (PDF)</strong>
                             <div class="d-flex gap-2">
                                 <input type="file" class="form-control" name="file_upload" accept="application/pdf">
                             </div>
                         </div>
                     </div>
                     
               
          </div>
          <button class="btn btn-success mt-4 mb-4" type="submit">Submit</button>
     </form>
</div>
