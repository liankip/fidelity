<div id="supplierModal">
    <div class="modal-header">
        <h3>Pilih Tanggal</h3>
        <button type="button" class="btn-close" wire:click="$emitUp('closeModal')"  aria-label="Close"></button>
    </div>
    <div class="modal-body">
        <label for="date_from">Tanggal Mulai</label>
        <input id="date_from" wire:model="date_from"
               class="form-control @error('date_from') is-invalid @enderror" type="date"/>
        @error('date_from')
        <div class="text-danger"> {{ $message }} </div>
        @enderror
        <label for="date_to">Tanggal Akhir</label>
        <input id="date_to" wire:model="date_to" class="form-control @error('date_to') is-invalid @enderror"
               type="date"/>
        @error('date_to')
        <div class="text-danger"> {{ $message }} </div>
        @enderror

        <label for="supplier">Supplier</label>
        <select class="form-control" id="supplier" wire:model="supplier">
            <option value="">-- Pilih Supplier --</option>
            @foreach ($supplierData as $supplier)
                <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
            @endforeach
        </select>
        @error('supplier')
        <div class="text-danger"> {{ $message }} </div>
        @enderror
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" wire:click="$emitUp('closeModal')"  data-bs-dismiss="modal">Close
        </button>

        <button type="button" class="btn btn-success" wire:click="export()" data-bs-dismiss="modal">Export
            PO
        </button>
    </div>
    <script>

        $(document).ready(function() {
          $("#supplier").select2({
            theme: 'bootstrap-5',
            dropdownParent: $("#supplierModal")
          });
        });
        
        </script>
</div>
