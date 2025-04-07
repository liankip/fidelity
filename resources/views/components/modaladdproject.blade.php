<div wire:click="closeddproject" class="bg-dark opacity-50"
    style="height: 100%;width: 100%;left: 0;top: 0;overflow: hidden;position: fixed;"></div>
<div class="modal" tabindex="-1" style="display: block;" aria-modal="true" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content ">
            <div class="modal-header">
                <span><strong>Add Project</strong></span>
                <button type="button" class="btn-close" wire:click="closeddproject" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">

                    <div class="col-xs-12 col-sm-12 col-md-12">
                        @if($project_exist)
                        <div class="alert alert-danger" role="alert">
                            The Project already exist!
                        </div>
                        @else
                        <div class="alert alert-success" role="alert">
                            The Project not yet exist!
                        </div>
                        @endif
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>Project Name:</strong>
                            <input type="text" name="projectnameadd" wire:model='projectnameadd' class="form-control" placeholder="Project Name">
                            @error('projectnameadd')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-12 mt-2">
                        <div class="form-group">
                            <strong>Company Name:</strong>
                            <input type="text" name="projectcompanynameadd" wire:model.defer='projectcompanynameadd' class="form-control"
                                placeholder="Company Name">
                            @error('projectcompanynameadd')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-12 mt-2">
                        <div class="form-group">
                            <strong>PIC:</strong>
                            <input type="text" name="projectpicadd" wire:model.defer='projectpicadd' class="form-control" placeholder="PIC">
                            @error('projectpicadd')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-12 mt-2">
                        <div class="form-group">
                            <strong>Email:</strong>
                            <input type="email" name="projectemailadd" wire:model.defer='projectemailadd' class="form-control" placeholder="Email">
                            @error('projectemailadd')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-12 mt-2">
                        <div class="form-group">
                            <strong>Phone:</strong>
                            <input type="number" name="projectphoneadd" wire:model.defer='projectphoneadd' class="form-control" placeholder="Phone">
                            @error('projectphoneadd')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-12 mt-2">
                        <div class="form-group">
                            <strong>Address:</strong>
                            <input type="text" name="projectaddressadd" wire:model.defer='projectaddressadd' class="form-control" placeholder="Address">
                            @error('projectaddressadd')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-12 mt-2">
                        <div class="form-group">
                            <strong>City:</strong>
                            <input type="text" name="projectcityadd" wire:model.defer='projectcityadd' class="form-control" placeholder="City">
                            @error('projectcityadd')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-12 mt-2">
                        <div class="form-group">
                            <strong>Province:</strong>
                            <input type="text" name="projectprovinceadd" wire:model.defer='projectprovinceadd' class="form-control" placeholder="Province">
                            @error('projectprovinceadd')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-12 mt-2">
                        <div class="form-group">
                            <strong>Kode Pos:</strong>
                            <input type="number" name="projectpostcodeadd" wire:model.defer='projectpostcodeadd' class="form-control" placeholder="Kode Pos">
                            @error('projectpostcodeadd')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button class="btn" wire:click="closeddproject">Cancel</button>
                <button class="btn btn-primary" wire:click="storeproject" wire:loading.attr="disabled" @if($project_exist) disabled @endif>Save</button>
            </div>
        </div>
    </div>
</div>
