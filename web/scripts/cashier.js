var dt = null

$(document).ready(() => {
    init()
})

function init() {
    dt = $('#table-customer-list').DataTable({
        serverSide: true,
        ajax: {
            url: '/index.php?r=cashier/cashier-datatable'
        },
        columns: [
            { data: 'order_code' },
            { data: 'ordered_by' },
            {
                sortable: false,
                searchable: false,
                render: (data, i, row) => `0`
            },
            {
                sortable: false,
                searchable: false,
                render: (data, i, row) => ``
            }
        ],
        language: { url: '/json/Indonesian-Alternative.json' }
    })
}