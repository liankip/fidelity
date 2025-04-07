@props(['label', 'placeholder'])
<div class="form-group" x-data="{ selectId: null }" x-init="init">
    <label for="unit">
        {{ $label }}
        @if ($attributes->has('required'))
            <span class="text-danger fw-bold">*</span>
        @endif
    </label>
    <select x-bind:id="selectId" {{ $attributes }}
        class="form-select @error($attributes->get('name')) is-invalid @enderror">
        <option value="" hidden>
            {{ $placeholder }}
        </option>
        {{ $slot }}
    </select>

    <script>
        function init() {
            this.selectId = 'select2-' + Math.random().toString(36).substring(7);
            const select2Id = '#' + this.selectId;

            this.$nextTick(() => {
                $(select2Id).select2({
                    theme: 'bootstrap-5', // Adjust the theme as needed
                    "language": {
                        "noResults": function() {
                            return "No Results Found";
                        }
                    },
                    escapeMarkup: function(markup) {
                        return markup;
                    },
                    dropdownParent: $('.form-group'),
                });
                $(select2Id).on('change', function(e) {
                    var data = $(select2Id).select2("val");
                    @this.set(e.target.name, data);
                });
            });
        }
    </script>

</div>
