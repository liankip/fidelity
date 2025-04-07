<div class="bg-black opacity-25"
     style="height: 100%;width: 100%;left: 0;top: 0;overflow: hidden;position: fixed;z-index:999"></div>

<div class="modal d-block" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" id="myModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{route('boq.access.store', $project->id)}}" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h3>Add User Access</h3>
                    <button type="button" class="btn-close" wire:click="closeModal" aria-label="Close"></button>
                </div>
                <div class="modal-body relative">
                    <div class="form-group">
                        <strong>Assign to</strong>
                        <select wire:model="user_id" name="user_id" id="user_id"
                                class="js-example-basic-single form-control @error('user_id') is-invalid @enderror">
                            <option value="" hidden>Pilih User</option>
                        </select>
                        @error('user_id')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:click="closeModal"
                            data-bs-dismiss="modal">Batal</button>

                    <button type="submit" class="btn btn-success"
                            data-bs-dismiss="modal">Simpan</button>
                </div>
            </form>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $('#user_id').select2({
                theme: 'bootstrap-5',
                dropdownParent : $('#myModal'),
                "language": {
                    "noResults": function() {
                        return "No Results Found";
                    }
                },
                escapeMarkup: function(markup) {
                    return markup;
                },
                ajax: {
                    url: '/api/getusers',
                    dataType: 'json',
                    delay: 250,
                    processResults: function(data) {
                        return {
                            results: $.map(data, function(user) {
                                return {
                                    id: user.id,
                                    text: user.name + ' (' + user.email + ')'
                                };
                            })
                        };
                    },
                    cache: true
                }
            });
        });
    </script>
</div>


