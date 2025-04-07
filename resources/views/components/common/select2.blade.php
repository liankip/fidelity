@props([
    'label',
    'placeholder',
    'async-data',
    'data'
])
<div x-data="{ selectId: null }" x-init="init" class="w-full">
    <div class="form-group">
        <strong class="mb-2">{{$label}}</strong>
        <select {{$attributes}} x-bind:id="selectId"
                class="mt-2 form-control @error($attributes->get('name')) is-invalid @enderror">
            <option value="" hidden>{{$placeholder}}</option>
        </select>
        @error($attributes->get('name'))
        <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <script>
        function init() {
            this.selectId = 'select2-' + Math.random().toString(36).substring(7);
            const select2Id = '#' + this.selectId;
            const endpoint = @js($asyncData);

            // Initialize Select2 after Alpine.js has rendered the element
            this.$nextTick(() => {
                $(select2Id).select2({
                    theme: 'bootstrap-5', // Adjust the theme as needed
                    "language": {
                        "noResults": function () {
                            return "No Results Found";
                        }
                    },
                    escapeMarkup: function (markup) {
                        return markup;
                    },
                    dropdownParent: $('.form-group'),
                    ajax: {
                        url: endpoint,
                        dataType: 'json',
                        delay: 250,
                        processResults: function (res) {
                            return {
                                results: $.map(res, function (data) {
                                    return {
                                        id: data.id,
                                        text: data.value
                                    };
                                })
                            };
                        },
                        cache: true
                    }
                });
            });
        }
    </script>
</div>
