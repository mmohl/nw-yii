var dt = null

$(document).ready(() => {
    init()
})

function init() {
    dt = $('#table-customer-list').DataTable({
        language: { url: '/json/Indonesian-Alternative.json' }
    })
}