function setDatatable(tableId, options = {}) {
    const defaultOptions = {
        responsive: true,
        dom: '<"top d-flex justify-content-between align-items-center"lfB>rt<"bottom d-flex justify-content-between align-items-center"ip><"clear">',
        buttons: [
            {
                extend: 'csv',
                text: '<i class="fas fa-file-csv me-1"></i> Export CSV',
                className: 'btn btn-success btn-sm',
                title: tableId.charAt(0).toUpperCase() + tableId.slice(1),
                exportOptions: {
                    columns: ':visible'
                }
            },
            {
                extend: 'copy',
                text: '<i class="fas fa-copy me-1"></i> Copy',
                className: 'btn btn-dark btn-sm',
                exportOptions: {
                    columns: ':visible'
                }
            }
        ],
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
        pageLength: 10,
        processing: true,
        language: {
            processing: '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div> Loading...'
        },
        initComplete: function () {
            $('.dataTables_filter input').addClass('form-control form-control-sm');
            $('.dataTables_length select').addClass('form-select form-select-sm');
            $('.dt-buttons').addClass('btn-group');
            $('.dt-buttons button').removeClass('btn-secondary');
        }
    };

    // Merge custom options with default
    const config = $.extend(true, {}, defaultOptions, options);

    // Initialize DataTable
    $('#' + tableId).DataTable(config);
}
