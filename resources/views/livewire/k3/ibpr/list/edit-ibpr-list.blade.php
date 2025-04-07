<div>
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <div class="d-flex">
                    <a href="{{ route('k3.ibpr.ibprList', $ibpr->id) }}" class="btn btn-sm btn-secondary my-auto">
                        <i class="fa-solid fa-angle-left"></i>
                    </a>
                    <h2 class="my-auto">HIRADC - {{ $ibpr->name }}</h2>
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
                            <strong>Aktivitas/ Proses</strong>
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

                @foreach ($cases as $key => $case)
                    <div class="card mb-3" wire:key='{{ $key }}' id="case">
                        <div class="card-body">
                            <div class="row mb-2">
                                <div class="col">
                                    <div class="col-xs-12 col-sm-12 col-md-12">
                                        <div class="form-group">
                                            <strong>Potensi Bahaya/Aspek Lingkungan</strong>
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
                                            <strong>S/H/E</strong>
                                            <span class="text-danger">*</span>
                                            <div>
                                                <select class="form-select"
                                                    wire:model="cases.{{ $key }}.situation"
                                                    aria-label="Default select example">
                                                    <option hidden>Pilih situasi</option>
                                                    <option value="S">S</option>
                                                    <option value="H">H</option>
                                                    <option value="E">E</option>
                                                </select>
                                            </div>
                                            @error('situation')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <strong>Resiko/Dampak</strong>
                                            <span class="text-danger">*</span>
                                            <div>
                                                <input type="text" wire:model="cases.{{ $key }}.risk"
                                                    class="form-control">
                                            </div>
                                            @error('risk')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <strong>Kondisi (R/NR/N/A N EM)</strong>
                                            <span class="text-danger">*</span>
                                            <div>
                                                <select class="form-select"
                                                    wire:model="cases.{{ $key }}.condition"
                                                    aria-label="Default select example">
                                                    <option hidden>Pilih situasi</option>
                                                    <option value="R">R</option>
                                                    <option value="NR">NR</option>
                                                    <option value="N">N</option>
                                                    <option value="ANM">ANM</option>
                                                </select>
                                            </div>
                                            @error('condition')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <strong>Resiko tingkat awal</strong>
                                            <div class="d-flex gap-2">
                                                <div>
                                                    <label for="exampleInputEmail1" class="form-label">L</label>
                                                    <span class="text-danger">*</span>
                                                    <input type="number" wire:model="cases.{{ $key }}.risk_l"
                                                        class="form-control">
                                                </div>
                                                <div>
                                                    <label for="exampleInputEmail1" class="form-label">S</label>
                                                    <span class="text-danger">*</span>
                                                    <input type="number" wire:model="cases.{{ $key }}.risk_s"
                                                        class="form-control">
                                                </div>
                                                <div>
                                                    <label for="exampleInputEmail1" class="form-label">RFN</label>
                                                    <span class="text-danger">*</span>
                                                    <input type="number"
                                                        wire:model="cases.{{ $key }}.risk_rfn"
                                                        class="form-control">
                                                </div>
                                            </div>
                                            @error('risk_l')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                            @error('risk_s')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                            @error('risk_rfn')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <strong>Tingkat resiko awal</strong>
                                            <span class="text-danger">*</span>
                                            <div>
                                                <select class="form-select"
                                                    wire:model="cases.{{ $key }}.risk_level"
                                                    aria-label="Default select example">
                                                    <option hidden>Pilih tingkat resiko</option>
                                                    <option value="Acceptable">Acceptable</option>
                                                    <option value="Trivial">Trivial</option>
                                                    <option value="Substantial">Substantial</option>
                                                    <option value="Moderate">Moderate</option>
                                                </select>
                                            </div>
                                            @error('risk_level')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
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
                                        <div class="form-group">
                                            <strong>Sisa resiko</strong>
                                            <div class="d-flex gap-2">
                                                <div>
                                                    <label for="exampleInputEmail1" class="form-label">L</label>
                                                    <span class="text-danger">*</span>
                                                    <input type="number"
                                                        wire:model="cases.{{ $key }}.risk_l_left"
                                                        class="form-control">
                                                </div>
                                                <div>
                                                    <label for="exampleInputEmail1" class="form-label">S</label>
                                                    <span class="text-danger">*</span>
                                                    <input type="number"
                                                        wire:model="cases.{{ $key }}.risk_s_left"
                                                        class="form-control">
                                                </div>
                                                <div>
                                                    <label for="exampleInputEmail1" class="form-label">RFN</label>
                                                    <span class="text-danger">*</span>
                                                    <input type="number"
                                                        wire:model="cases.{{ $key }}.risk_rfn_left"
                                                        class="form-control">
                                                </div>
                                            </div>
                                            @error('risk_l_left')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                            @error('risk_s_left')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                            @error('risk_rfn_left')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <strong>Tingkat sisa resiko</strong>
                                            <span class="text-danger">*</span>
                                            <div>
                                                <select class="form-select"
                                                    wire:model="cases.{{ $key }}.risk_level_left"
                                                    aria-label="Default select example">
                                                    <option hidden>Pilih tingkat resiko</option>
                                                    <option value="Acceptable">Acceptable</option>
                                                    <option value="Trivial">Trivial</option>
                                                    <option value="Substantial">Substantial</option>
                                                    <option value="Moderate">Moderate</option>
                                                </select>
                                            </div>
                                            @error('risk_level_left')
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
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <strong>Eliminasi</strong>
                                        {{-- <span class="text-danger">*</span> --}}
                                        <div>
                                            <input type="text" wire:model="cases.{{ $key }}.elimination"
                                                class="form-control">
                                        </div>
                                        @error('elimination')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <strong>Substitusi</strong>
                                        {{-- <span class="text-danger">*</span> --}}
                                        <div>
                                            <input type="text" wire:model="cases.{{ $key }}.substitution"
                                                class="form-control">
                                        </div>
                                        @error('substitution')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <strong>Pengendalian Teknis/Rekayasa Engineering</strong>
                                        {{-- <span class="text-danger">*</span> --}}
                                        <div>
                                            <input type="text"
                                                wire:model="cases.{{ $key }}.technical_control"
                                                class="form-control">
                                        </div>
                                        @error('technical_control')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <strong>Rambu/Peringatan/Pengendalian Administratif</strong>
                                        {{-- <span class="text-danger">*</span> --}}
                                        <div>
                                            <input type="text"
                                                wire:model="cases.{{ $key }}.warning_control"
                                                class="form-control">
                                        </div>
                                        @error('warning_control')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <strong>Penggunaan APD</strong>
                                        {{-- <span class="text-danger">*</span> --}}
                                        <div>
                                            <input type="text" wire:model="cases.{{ $key }}.apd_usage"
                                                class="form-control">
                                        </div>
                                        @error('apd_usage')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <strong>PIC</strong>
                                        {{-- <span class="text-danger">*</span> --}}
                                        <div>
                                            <input type="text" wire:model="cases.{{ $key }}.pic"
                                                class="form-control">
                                        </div>
                                        @error('pic')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <strong>Status (Ya/Tidak)</strong>
                                        <span class="text-danger">*</span>
                                        <div>
                                            <select class="form-select" wire:model="cases.{{ $key }}.status"
                                                aria-label="Default select example">
                                                <option hidden>Pilih status</option>
                                                <option value="Ya">Ya</option>
                                                <option value="Tidak">Tidak</option>
                                            </select>
                                        </div>
                                        @error('status')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <strong>Target penyelesaian</strong>
                                        {{-- <span class="text-danger">*</span> --}}
                                        <div>
                                            <input type="text"
                                                wire:model="cases.{{ $key }}.target_achievement"
                                                class="form-control">
                                        </div>
                                        @error('target_achievement')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <strong>Efektif Minimalkan Risiko/Dampak Lingkungan</strong>
                                        {{-- <span class="text-danger">*</span> --}}
                                        <div>
                                            <input type="text"
                                                wire:model="cases.{{ $key }}.min_effective"
                                                class="form-control">
                                        </div>
                                        @error('min_effective')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <strong>Menimbulkan Risiko Baru/Dampak Lingkungan</strong>
                                        {{-- <span class="text-danger">*</span> --}}
                                        <div>
                                            <input type="text" wire:model="cases.{{ $key }}.new_risk"
                                                class="form-control">
                                        </div>
                                        @error('new_risk')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <strong>Tindakan monitoring</strong>
                                        {{-- <span class="text-danger">*</span> --}}
                                        <div>
                                            <input type="text" wire:model="cases.{{ $key }}.monitoring"
                                                class="form-control">
                                        </div>
                                        @error('monitoring')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        @if ($key > 0)
                            <button type="button" wire:click="removeCase('{{ $key }}')"
                                class="btn btn-danger">Delete Case</button>
                        @endif
                    </div>
                @endforeach

                <button wire:click.prevent="update" wire:loading.remove type="submit" class="btn btn-success">Edit</button>
                <button wire:loading wire:target='update' type="submit" class="btn btn-secondary"
                    disabled>Saving...</button>
                <a class="btn btn-secondary" href="{{ route('k3.ibpr.ibprList', $ibpr->id) }}">
                    Cancel
                </a>
            </form>
        </div>
    </div>
</div>
