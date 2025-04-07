@props([
    'id' => '',
    'paging' => true,
])
<div class="table-responsive">
    <table id={{ $id }} class="table">
        {{ $slot }}
    </table>

    <script>
        const tableId = @json($id);
        let paging = @json($paging);

        let datatable = initDataTables(`#${tableId}`, paging);
        if (typeof Livewire !== 'undefined') {
            Livewire.hook('message.processed', (message, component) => {
                $(`#${tableId}`).DataTable().destroy();
                datatable = initDataTables(`#${tableId}`);
            });
        }
    </script>
</div>
