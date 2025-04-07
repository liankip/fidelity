<div>
    <button class="btn btn-success" type="button" wire:click="toggleModal">
        <i class="fas fa-plus"></i>
        Add Project
    </button>
    @if($showModal)
        <div>
            <div class="bg-black opacity-25"
                 style="height: 100%;width: 100%;left: 0;top: 0;overflow: hidden;position: fixed;z-index: 999"></div>

            <div class="modal d-block" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"
                 id="myModal">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form action="{{route('projects.group.store', $group->id)}}" enctype="multipart/form-data"
                              method="POST">
                            @csrf
                            <div class="modal-header">
                                <h3>Add new project</h3>
                                <button type="button" class="btn-close" wire:click="toggleModal"
                                        aria-label="Close"></button>
                            </div>
                            <div class="modal-body relative">
                                <div class="form-group" style="color:black">
                                    <strong class="text-black">Project Name</strong>
                                    <select name="project_id[]" id="project_id"
                                            class="mt-3 js-example-basic-multiple form-control @error('projects') is-invalid @enderror"
                                            multiple="multiple" required
                                    >
                                        @foreach($projects as $projek)
                                            <option value="{{ $projek->id }}">{{ $projek->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('project_id')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" wire:click="toggleModal"
                                        data-bs-dismiss="modal">Cancel
                                </button>

                                <button type="submit" class="btn btn-success">Save
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <script>
                $(document).ready(function () {
                    $('.js-example-basic-multiple').select2();

                });
            </script>

        </div>
    @endif
</div>
