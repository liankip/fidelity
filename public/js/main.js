
function initDataTables(table, paging = true) {
    const config = {
        dom: "<'row'><'row'<'col-md-7'lB><'col-md-5'f>>rt<'row'<'col-md-4'i><'col-md-8 dataTables_paging'<'#colvis'><'.dt-page-jump'>p>>",
        paging,
        autoWidth: true,
        search:true,
        language: {
            lengthMenu: "_MENU_",
            search: "",
            searchPlaceholder: "Search...",
        },
        buttons: [{
            extend: 'collection',
            className: 'btn btn-sm btn-export',
            text: 'Export',
            buttons: [{
                    extend: 'copy',
                    exportOptions: {
                        columns: [":not(.not-export)"]
                    }
                },
                {
                    extend: 'excel',
                    exportOptions: {
                        columns: [":not(.not-export)"]
                    }
                },
                {
                    extend: 'csv',
                    exportOptions: {
                        columns: [":not(.not-export)"]
                    }
                },
                {
                    extend: 'pdf',
                    exportOptions: {
                        columns: [":not(.not-export)"]
                    }
                },
                {
                    extend: 'print',
                    exportOptions: {
                        columns: [":not(.not-export)"]
                    }
                }
            ]
        }]
    }

    return new DataTable(table, config)
}
