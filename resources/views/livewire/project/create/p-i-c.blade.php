<div class="card border">
    <div class="card-body">
        <strong>Struktur Organinsasi</strong>
        <div class="col-xs-12 col-sm-12 col-md-12 mt-2">
            <div class="form-group">
                <strong>PM In Charge <span class="text-danger">*</span></strong>
                <input type="text" name="pic" value="{{ old('pic') ? old('pic') : '' }}"
                    class="form-control @error('pic') is-invalid @enderror" placeholder="Name" id="pic">
                @error('pic')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12 mt-2">
            <div class="form-group">
                <strong>SM In Charge</strong>
                <input type="text" name="sm" value="{{ old('sm') ? old('sm') : '' }}"
                    class="form-control @error('sm') is-invalid @enderror" placeholder="Name">
                @error('sm')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12 mt-2">
            <div class="form-group">
                <strong>Logistic In Charge</strong>
                <input type="text" name="logistic" value="{{ old('logistic') ? old('logistic') : '' }}"
                    class="form-control @error('logistic') is-invalid @enderror" placeholder="Name">
                @error('logistic')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12 mt-2">
            <div class="form-group">
                <strong>EHS In Charge</strong>
                <input type="text" name="ehs" value="{{ old('ehs') ? old('ehs') : '' }}"
                    class="form-control @error('ehs') is-invalid @enderror" placeholder="Name">
                @error('ehs')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12 mt-2">
            <div class="form-group">
                <strong>Director In Charge</strong>
                <input type="text" name="director" value="{{ old('director') ? old('director') : '' }}"
                    class="form-control @error('director') is-invalid @enderror" placeholder="Name">
                @error('director')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>
    @php
        $userArray = $userData->map(function($user) {
            return ['value' => $user->name, 'id' => $user->id, 'email' => $user->email];
        })->toArray();
    @endphp
    <script>

        const inputNames = ['sm', 'logistic', 'ehs', 'director'];

        inputNames.forEach(name => {
            const input = document.querySelector(`input[name=${name}]`);
            new Tagify(input);
        });

        document.addEventListener("DOMContentLoaded", function() {
            const picInput = document.getElementById('pic');

            const sanitizedUserData = @json($userArray);

            if (picInput) { // Ensure the input element exists
                const tagify = new Tagify(picInput, {
                    tagTextProp: 'data-id',
                    dropdown: {
                        enabled: 0,
                        maxItems: 10,
                        closeOnSelect: false,
                        classname: 'users-list',
                    },
                    templates: {
                        dropdownItem: function(tagData) {
                            return `<div class="tagify__dropdown__item" data-id="${tagData.id}" data-value="${tagData.value}" data-email="${tagData.email}">
                                        <strong>${tagData.value} - ${tagData.email}</strong>
                                    </div>`;
                        }
                    },
                    whitelist: sanitizedUserData.map(user => ({
                        value: user.value,
                        email: user.email,
                        id: user.id,
                    })),
                });

                tagify.on('dropdown:select', function(e) {
                    const selectedElement = e.detail.elm;
                    const tagData = {
                        id: selectedElement.getAttribute('data-id'),
                        value: selectedElement.getAttribute('data-value'),
                        email: selectedElement.getAttribute('data-email'),
                    };

                    tagify.addTags([tagData]);

                });
            } else {
                console.error('Input element with id "pic" not found');
            }
        });
    </script>
</div>
