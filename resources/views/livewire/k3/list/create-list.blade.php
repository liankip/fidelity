<div>
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <div class="d-flex">
                    <a href="{{ route('k3.hiradc.allList', $hiradc->id) }}" class="btn btn-sm btn-secondary my-auto">
                        <i class="fa-solid fa-angle-left"></i>
                    </a>
                    <h2 class="my-auto">HIRADC - {{ $hiradc->name }}</h2>
                </div>
                @if ($message = Session::get('success'))
                    <div class="alert alert-success alert-dismissible fade show mb-2" role="alert">
                        {{ $message }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                <hr>
            </div>
        </div>
        <div class="mt-2">
            <form action="POST">
                @csrf
                <div class="card">
                    <div class="card-body">
                        <div class="form-group">
                            <strong>Sub judul</strong>
                            {{-- <span class="text-danger">*</span> --}}
                            <div>
                                <input type="text" wire:model="sub_name" class="form-control">
                            </div>
                            @error('sub_name')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <strong>Aktivitas kerja</strong>
                            <span class="text-danger">*</span>
                            <div>
                                <input type="text" wire:model="activity" class="form-control">
                            </div>
                            @error('activity')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <button type="button" wire:click="addCase" class="btn btn-success mb-2">Add More Case</button>

                <div class="card" id="case">
                    <div class="card-body">
                        <div class="row mb-2">
                            <div class="col">
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="form-group">
                                        <strong>Identifikasi bahaya</strong>
                                        <span class="text-danger">*</span>
                                        <div>
                                            <input type="text" wire:model="threat" class="form-control">
                                        </div>
                                        @error('threat')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <strong>Situasi</strong>
                                        <span class="text-danger">*</span>
                                        <div>
                                            <select class="form-select" wire:model="situation"
                                                aria-label="Default select example">
                                                <option hidden>Pilih situasi</option>
                                                <option value="R">R</option>
                                                <option value="NR">NR</option>
                                                <option value="E">E</option>
                                            </select>
                                        </div>
                                        @error('situation')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <strong>Aspek</strong>
                                        <span class="text-danger">*</span>
                                        <div>
                                            <select class="form-select" wire:model="aspect"
                                                aria-label="Default select example">
                                                <option hidden>Pilih aspek</option>
                                                <option value="H">H</option>
                                                <option value="S">S</option>
                                                <option value="E">E</option>
                                            </select>
                                        </div>
                                        @error('aspect')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <strong>Potensi dampak/akibat yang ditimbulkan</strong>
                                        <span class="text-danger">*</span>
                                        <div>
                                            <input type="text" wire:model="impact" class="form-control">
                                        </div>
                                        @error('impact')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div>
                                        <strong>Penilaian resiko</strong>
                                        <div class="d-flex gap-2">
                                            <div>
                                                <label for="exampleInputEmail1" class="form-label">K</label>
                                                <span class="text-danger">*</span>
                                                <input type="number" wire:model="risk_k" class="form-control">
                                            </div>
                                            <div>
                                                <label for="exampleInputEmail1" class="form-label">P</label>
                                                <span class="text-danger">*</span>
                                                <input type="number" wire:model="risk_p" class="form-control">
                                            </div>
                                            <div>
                                                <label for="exampleInputEmail1" class="form-label">TNR</label>
                                                <span class="text-danger">*</span>
                                                <input type="number" wire:model="risk_tnr" class="form-control">
                                            </div>
                                        </div>
                                        @error('risk_k')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                        @error('risk_p')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                        @error('risk_tnr')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <strong>Pengendalian saat ini</strong>
                                    <span class="text-danger">*</span>
                                    <div>
                                        <input type="text" wire:model="current_control" class="form-control">
                                    </div>
                                    @error('current_control')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div>
                                    <strong>Penilaian setelah pengendalian</strong>
                                    <div class="d-flex gap-2">
                                        <div>
                                            <label for="exampleInputEmail1" class="form-label">K</label>
                                            <span class="text-danger">*</span>
                                            <input type="number" wire:model="risk_k_after" class="form-control">
                                        </div>
                                        <div>
                                            <label for="exampleInputEmail1" class="form-label">P</label>
                                            <span class="text-danger">*</span>
                                            <input type="number" wire:model="risk_p_after" class="form-control">
                                        </div>
                                        <div>
                                            <label for="exampleInputEmail1" class="form-label">TNR</label>
                                            <span class="text-danger">*</span>
                                            <input type="number" wire:model="risk_tnr_after" class="form-control">
                                        </div>
                                    </div>
                                    @error('risk_k_after')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                    @error('risk_p_after')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                    @error('risk_tnr_after')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <strong>Peraturan terkait</strong>
                                    <span class="text-danger">*</span>
                                    <div>
                                        <input type="text" wire:model="related_rules" class="form-control">
                                    </div>
                                    @error('related_rules')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <strong>Tingkat resiko</strong>
                                    <span class="text-danger">*</span>
                                    <div>
                                        <select class="form-select" wire:model="risk_level"
                                            aria-label="Default select example">
                                            <option hidden>Pilih tingkat resiko</option>
                                            <option value="Acceptable">Acceptable</option>
                                            <option value="Trivial">Trivial</option>
                                        </select>
                                    </div>
                                    @error('risk_level')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <strong>Pengendalian lebih lanjut yang di syaratkan</strong>
                                    <div>
                                        <input type="text" wire:model="further_control" class="form-control">
                                    </div>
                                    @error('further_control')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @foreach ($cases as $key => $case)
                    <div class="card mb-3" wire:key="{{ $key }}">
                        <div class="card-body">
                            <div class="row mb-2">
                                <div class="col">
                                    <div class="col-xs-12 col-sm-12 col-md-12">
                                        <div class="form-group">
                                            <strong>Identifikasi bahaya</strong>
                                            <span class="text-danger">*</span>
                                            <div>
                                                <input type="text" wire:model="cases.{{ $key }}.threat"
                                                    class="form-control">
                                            </div>
                                            @error('threat')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <strong>Situasi</strong>
                                            <span class="text-danger">*</span>
                                            <div>
                                                <select class="form-select"
                                                    wire:model="cases.{{ $key }}.situation"
                                                    aria-label="Default select example">
                                                    <option hidden>Pilih situasi</option>
                                                    <option value="R">R</option>
                                                    <option value="NR">NR</option>
                                                    <option value="E">E</option>
                                                </select>
                                            </div>
                                            @error('situation')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <strong>Aspek</strong>
                                            <span class="text-danger">*</span>
                                            <div>
                                                <select class="form-select"
                                                    wire:model="cases.{{ $key }}.aspect"
                                                    aria-label="Default select example">
                                                    <option hidden>Pilih aspek</option>
                                                    <option value="H">H</option>
                                                    <option value="S">S</option>
                                                    <option value="E">E</option>
                                                </select>
                                            </div>
                                            @error('aspect')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <strong>Potensi dampak/akibat yang ditimbulkan</strong>
                                            <span class="text-danger">*</span>
                                            <div>
                                                <input type="text" wire:model="cases.{{ $key }}.impact"
                                                    class="form-control">
                                            </div>
                                            @error('impact')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div>
                                            <strong>Penilaian resiko</strong>
                                            <div class="d-flex gap-2">
                                                <div>
                                                    <label for="exampleInputEmail1" class="form-label">K</label>
                                                    <span class="text-danger">*</span>
                                                    <input type="number"
                                                        wire:model="cases.{{ $key }}.risk_k"
                                                        class="form-control">
                                                </div>
                                                <div>
                                                    <label for="exampleInputEmail1" class="form-label">P</label>
                                                    <span class="text-danger">*</span>
                                                    <input type="number"
                                                        wire:model="cases.{{ $key }}.risk_p"
                                                        class="form-control">
                                                </div>
                                                <div>
                                                    <label for="exampleInputEmail1" class="form-label">TNR</label>
                                                    <span class="text-danger">*</span>
                                                    <input type="number"
                                                        wire:model="cases.{{ $key }}.risk_tnr"
                                                        class="form-control">
                                                </div>
                                            </div>
                                            @error('risk_k')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                            @error('risk_p')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                            @error('risk_tnr')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <strong>Pengendalian saat ini</strong>
                                        <span class="text-danger">*</span>
                                        <div>
                                            <input type="text"
                                                wire:model="cases.{{ $key }}.current_control"
                                                class="form-control">
                                        </div>
                                        @error('current_control')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div>
                                        <strong>Penilaian setelah pengendalian</strong>
                                        <div class="d-flex gap-2">
                                            <div>
                                                <label for="exampleInputEmail1" class="form-label">K</label>
                                                <span class="text-danger">*</span>
                                                <input type="number"
                                                    wire:model="cases.{{ $key }}.risk_k_after"
                                                    class="form-control">
                                            </div>
                                            <div>
                                                <label for="exampleInputEmail1" class="form-label">P</label>
                                                <span class="text-danger">*</span>
                                                <input type="number"
                                                    wire:model="cases.{{ $key }}.risk_p_after"
                                                    class="form-control">
                                            </div>
                                            <div>
                                                <label for="exampleInputEmail1" class="form-label">TNR</label>
                                                <span class="text-danger">*</span>
                                                <input type="number"
                                                    wire:model="cases.{{ $key }}.risk_tnr_after"
                                                    class="form-control">
                                            </div>
                                        </div>
                                        @error('risk_k_after')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                        @error('risk_p_after')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                        @error('risk_tnr_after')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <strong>Peraturan terkait</strong>
                                        <span class="text-danger">*</span>
                                        <div>
                                            <input type="text"
                                                wire:model="cases.{{ $key }}.related_rules"
                                                class="form-control">
                                        </div>
                                        @error('related_rules')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <strong>Tingkat resiko</strong>
                                        <span class="text-danger">*</span>
                                        <div>
                                            <select class="form-select"
                                                wire:model="cases.{{ $key }}.risk_level"
                                                aria-label="Default select example">
                                                <option hidden>Pilih tingkat resiko</option>
                                                <option value="Acceptable">Acceptable</option>
                                                <option value="Trivial">Trivial</option>
                                            </select>
                                        </div>
                                        @error('risk_level')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <strong>Pengendalian lebih lanjut yang diyaratkan</strong>
                                        <div>
                                            <input type="text"
                                                wire:model="cases.{{ $key }}.further_control"
                                                class="form-control">
                                        </div>
                                        @error('further_control')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button type="button" wire:click="removeCase('{{ $case['id'] }}')"
                            class="btn btn-danger">Delete Case</button>
                    </div>
                @endforeach

                <button wire:click.prevent="store" wire:loading.remove type="submit" class="btn btn-success">Create
                    +</button>
                <button wire:loading wire:target='store' type="submit" class="btn btn-secondary"
                    disabled>Saving...</button>
                <a class="btn btn-secondary" href="{{ route('k3.hiradc.allList', $hiradc->id) }}">
                    Cancel
                </a>
            </form>
        </div>
    </div>
</div>
