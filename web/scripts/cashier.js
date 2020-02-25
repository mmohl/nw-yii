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
                data: 'is_paid',
                searchable: false,
                render: (data, i, row) => `<label class="label label-${data == 1 ? 'success' : 'warning'}">${data == 1 ? 'Lunas' : 'Belum Bayar'}</label>`
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