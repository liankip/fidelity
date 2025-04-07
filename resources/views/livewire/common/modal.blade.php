<div class="modal fade" id="bootstrap-modal" tabindex="-1" role="dialog" aria-hidden="true" wire:ignore.self
     style="z-index: 9991;">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            @if ($component)
                @livewire($component['name'], $component['arguments'], key($activemodal))
            @endif
        </div>
    </div>

    <script>
        window.addEventListener('showBootstrapModal', event => {
            $('#bootstrap-modal').modal('show');
        })

        window.addEventListener('closeBootstrapModal', event => {
            $('#bootstrap-modal').modal('hide');
        })

    </script>
</div>
