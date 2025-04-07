<div>
     <h4>User Management</h4>
     <div class="card mt-2">
          <div class="card-body p-5">
               <form method="POST" action="{{ route('hrd.updateUser', ['id' => $userData->id]) }}">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="user_id" value="{{ $userData->id }}"/>

                    <div class="row">
                         <div class="col">
                              <div class="col-xs-12 col-sm-12 col-md-12">
                                   <div class="form-group">
                                        <strong>NIK</strong>
                                        {{-- <span class="text-danger">*</span> --}}
                                        <input type="number" name="nik" class="form-control"
                                             value="{{ $userData->nik }}">
                                   </div>
                              </div>
                              <div class="col-xs-12 col-sm-12 col-md-12">
                                   <div class="form-group">
                                        <strong>Name</strong>
                                        <span class="text-danger">*</span>
                                        <input type="text" name="name" class="form-control"
                                             value="{{ $userData->name }}">
                                   </div>
                                   @error('name')
                                        <div class="text-danger">{{ $message }}</div>
                                   @enderror
                              </div>
                              <div class="col-xs-12 col-sm-12 col-md-12">
                                   <div class="form-group">
                                        <strong>Email</strong>
                                        {{-- <span class="text-danger">*</span> --}}
                                        <input type="text" name="email" class="form-control"
                                             value="{{ $userData->email }}">
                                   </div>
                              </div>
                              <div class="col-xs-12 col-sm-12 col-md-12">
                                   <div class="form-group">
                                        <strong>Password</strong>
                                        {{-- <span class="text-danger">*</span> --}}
                                        <input type="text" name="password" class="form-control">
                                   </div>
                              </div>
                              <div class="col-xs-12 col-sm-12 col-md-12">
                                   <div class="form-group">
                                        <strong>Position</strong>
                                        {{-- <span class="text-danger">*</span> --}}
                                        <input type="text" name="position" class="form-control"
                                             value="{{ $userData->position }}">
                                   </div>
                              </div>
                              <div class="col-xs-12 col-sm-12 col-md-12">
                                   <div class="form-group">
                                        <strong>Education</strong>
                                        {{-- <span class="text-danger">*</span> --}}
                                        <input type="text" name="education" class="form-control"
                                             value="{{ $userData->education }}">
                                   </div>
                              </div>
                         </div>
                         <div class="col">
                              <div class="col-xs-12 col-sm-12 col-md-12">
                                   <div class="form-group">
                                        <strong>Status</strong>
                                        {{-- <span class="text-danger">*</span> --}}
                                        <select class="form-select" name="status" aria-label="Default select example">
                                             <option selected value="">Pilih status pekerja</option>
                                             <option value="PKWT"
                                                  {{ $userData->status === 'PKWT' ? 'selected' : '' }}>PKWT</option>
                                             <option value="NON-PKWT"
                                                  {{ $userData->status === 'NON-PKWT' ? 'selected' : '' }}>NON-PKWT
                                             </option>
                                        </select>
                                   </div>
                              </div>
                              <div class="col-xs-12 col-sm-12 col-md-12">
                                   <div class="form-group">
                                        <strong>Gender</strong>
                                        {{-- <span class="text-danger">*</span> --}}
                                        <select class="form-select" name="gender" aria-label="Default select example">
                                             <option selected value="">Pilih gender</option>
                                             <option value="LAKI-LAKI"
                                                  {{ $userData->gender === 'LAKI-LAKI' ? 'selected' : '' }}>Laki-laki
                                             </option>
                                             <option value="PEREMPUAN"
                                                  {{ $userData->gender === 'PEREMPUAN' ? 'selected' : '' }}>Perempuan
                                             </option>
                                        </select>
                                   </div>
                              </div>
                              <div class="col-xs-12 col-sm-12 col-md-12">
                                   <div class="form-group">
                                        <strong>Tanggal Lahir</strong>
                                        {{-- <span class="text-danger">*</span> --}}
                                        <input type="date" name="dob" class="form-control"
                                             value="{{ $userData->dob ? \Carbon\Carbon::parse($userData->dob)->format('Y-m-d') : '' }}">
                                   </div>
                              </div>
                              <div class="col-xs-12 col-sm-12 col-md-12">
                                   <div class="form-group">
                                        <strong>Tanggal Diterima</strong>
                                        {{-- <span class="text-danger">*</span> --}}
                                        <input type="date" name="accepted_date" class="form-control" value="{{ $userData->accepted_date ? \Carbon\Carbon::parse($userData->accepted_date)->format('Y-m-d') : '' }}">
                                   </div>
                              </div>
                              <div class="col-xs-12 col-sm-12 col-md-12">
                                   <div class="form-group">
                                        <strong>Alamat</strong>
                                        {{-- <span class="text-danger">*</span> --}}
                                        <input type="text" name="address" class="form-control" value="{{ $userData->address }}">
                                   </div>
                              </div>
                              <div class="col-xs-12 col-sm-12 col-md-12">
                                   <div class="form-group">
                                        <strong>Disability</strong>
                                        {{-- <span class="text-danger">*</span> --}}
                                        <input type="text" name="disability" class="form-control" value="{{ $userData->disability }}">
                                   </div>
                              </div>
                         </div>

                    </div>
                    <button type="submit" class="btn btn-success">Edit</button>
                    <a href="{{ route('hrd.alluser') }}" class="btn btn-danger">Cancel</a>
               </form>
          </div>
     </div>
</div>
