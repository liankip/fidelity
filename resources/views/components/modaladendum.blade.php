<div class="bg-dark opacity-25" style="height: 100%;width: 100%;left: 0;top: 0;overflow: hidden;position: fixed;"></div>

<div class="modal d-block" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Akes Ditolak</h3>
                <button type="button" class="btn-close" wire:click="closeModal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>
                    Anda tidak memiliki akses untuk membuat adendum
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" wire:click="closeModal"
                    data-bs-dismiss="modal">Batal</button>

                <button type="button" class="btn btn-success" wire:click="requestAccessAdendum"
                    data-bs-dismiss="modal">Minta
                    Akses</button>
            </div>
        </div>
    </div>
</div>
