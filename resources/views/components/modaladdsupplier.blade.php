<div wire:click="closeshowai" class="bg-dark opacity-50"
    style="height: 100%;width: 100%;left: 0;top: 0;overflow: hidden;position: fixed;"></div>
<div class="modal d-block" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Add supplier</h3>
                <button type="button" class="btn-close" wire:click="closemodaladdsupplier" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Supplier Name<span class="text-danger">*</span></strong>
                        <input type="text" name="name" class="form-control" wire:model='modelsuppliername'
                            placeholder="supplier Name">
                        @error('modelsuppliername')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>PIC<span class="text-danger">*</span></strong>
                        <input type="text" name="pic" class="form-control" wire:model='modelsupplierpic'
                            placeholder="PIC">
                        @error('modelsupplierpic')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Term Of Payment<span class="text-danger">*</span></strong>
                        <select required wire:model='modelsuppliertop'
                            class="form-select @error('modelsuppliertop') is-invalid @enderror" name="modelsuppliertop"
                            aria-label="Default select example">
                            <option value="">Open this select menu</option>
                            <option value="CoD">CoD
                            </option>
                            <option value="Cash">Cash
                            </option>
                            <option value="7 hari">7 hari
                            </option>
                            <option value="30 hari">
                                30 hari
                            </option>
                            <option value="DP 7 hari">DP 7
                                hari
                            </option>
                            <option value="DP 30 hari">
                                DP 30 hari
                            </option>
                        </select>
                        {{-- <input type="text" name="term_of_payment" wire:model='modelsuppliertop' class="form-control"
                            placeholder="Term Of Payment"> --}}
                        @error('modelsuppliertop')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Email</strong>
                        <input type="email" name="email" wire:model='modelsupplieremail' class="form-control"
                            placeholder="Email">
                        @error('modelsupplieremail')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Phone<span class="text-danger">*</span></strong>
                        <input type="number" name="phone" class="form-control" wire:model='modelsupplierphone'
                            placeholder="Phone">
                        @error('modelsupplierphone')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Address</strong>
                        <input type="text" name="address" class="form-control" wire:model='modelsupplieraddress'
                            placeholder="Address">
                        @error('modelsupplieraddress')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>City<span class="text-danger">*</span></strong>
                        <input type="text" name="city" class="form-control" wire:model='modelsuppliercity'
                            placeholder="City">
                        @error('modelsuppliercity')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Province<span class="text-danger">*</span></strong>
                        <input type="text" name="province" class="form-control" wire:model='modelsupplierprovince'
                            placeholder="Province">
                        @error('modelsupplierprovince')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Kode Pos</strong>
                        <input type="number" name="post_code" class="form-control" wire:model='modelsupplierpos'
                            placeholder="Kode Pos">
                        @error('modelsupplierpos')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

            </div>

            <div class="modal-footer">
                <button class="btn" wire:click="closemodaladdsupplier">Cancel</button>
                <button class="btn btn-primary" id="btnsavesupplier" onclick="emitsavesipplier()">Save</button>
            </div>

        </div>
    </div>
</div>
