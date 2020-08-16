var dt = null
var currentOrderCode = null
var intervalDt = null
var currentOrder = null

$(document).ready(() => {
    init()
})

$(document).on('click', '#table-customer-list tbody tr', ({ target }) => {
    const tr = $(target).closest('tr')
    const [tdCode, , , , tdStatus] = tr.children()
    const orderCode = $(tdCode).html()
    const status = $(tdStatus).children('label').html()
    // const r = `transaction/get-order`
    currentOrderCode = orderCode

    $.ajax({
        url: '/transaction/get-order',
        data: { orderCode }
    }).then(res => {
        renderTotalItem(res, status)
        currentOrder = res
    })
})

$('#input-payment').on("keyup", _.debounce(({ target: { value } }) => {
    const inputPay = parseInt(value.replace(/\./ig, ''))
    const totalShouldPay = parseInt($('#input-total').val().replace(/\./ig, ''))

    if (inputPay >= totalShouldPay) {
        $('#btn-invoice-pay').prop('disabled', false)
        $('#input-changes').val((inputPay - totalShouldPay).toLocaleString('id'))
    } else {
        $('#btn-invoice-pay').prop('disabled', true)
        $('#input-changes').val(0)
    }

    if (inputPay) $('#input-payment').val(inputPay.toLocaleString('id'))
}, 500))

$('#btn-invoice-pay').on('click', () => {
    // const r = 'transaction/pay-order'
    const orderCode = currentOrderCode
    const payment = $('#input-payment').val() ? $('#input-payment').val().replace(/\./ig, '') : null
    const rounding = 0 //$('#input-rounding').val()
    const isIgnored = $('#input-ignored').prop('checked')

    $.ajax({
        url: '/transaction/pay-order',
        data: { orderCode, payment, rounding, isIgnored }
    }).then(res => {
        dt.ajax.reload()
        resetViewInvoice()
    }).fail(err => {
        window.alert('gagal melakukan pembayaran, silahkan coba lagi')
    })
})

$('#input-ignored').on('change', ({ target }) => {
    renderTotalItem(currentOrder, 'belum lunas', $(target).prop('checked'))
})

function resetViewInvoice() {
    $('#invoice-detail tbody').empty()

    $('#input-sub-total').val('')
    $('#input-tax').val('')
    $('#input-before-rounding').val('')
    $('#input-rounding').val('')
    $('#input-total').val('')
    $('#input-payment').val('')
    $('#input-changes').val('')
    $('#btn-invoice-pay').prop('disabled', true)
    $('#input-ignored').prop('checked', false)
}

function renderTotalItem(res, status, isIgnored = false) {
    resetViewInvoice()
    if (isIgnored) $('#input-ignored').prop('checked', true)

    res.items.forEach(item => {
        $('#invoice-detail tbody').append(`
            <tr>
                <td>${(item.name || '').toUpperCase()}</td>
                <td>${item.qty || 0}</td>
                <td>${(parseInt(item.price || 0)).toLocaleString('id')}</td>
            </tr>
        `)
    })

    $('#input-sub-total').val(res.subtotal.toLocaleString('id'))
    // $('#input-rounding').val(res.rounded.toLocaleString('id'))

    if (isIgnored) {
        $('#input-tax').val(0)
        // $('#input-before-rounding').val((res.total - res.taxTotal).toLocaleString('id'))
        $('#input-total').val(((res.total - res.taxTotal) + res.rounded).toLocaleString('id'))
    } else {
        $('#input-tax').val(res.taxTotal.toLocaleString('id'))
        // $('#input-before-rounding').val(res.total.toLocaleString('id'))
        $('#input-total').val((res.total + res.rounded).toLocaleString('id'))
    } 

    if (status.toLowerCase() == 'lunas') {
        $('#input-payment').prop('disabled', true)
        $('#input-payment').val(parseInt(res.total_payment).toLocaleString('id'))
        $('#input-changes').val((res.total_payment - (res.total + res.rounded)).toLocaleString('id'))
        // $('#input-ignored').prop('disabled', true)
        if (res.is_ignored == '1') $('#input-ignored').prop('checked', true)
    } else {
        $('#input-changes').val('')
        $('#input-payment').val('')
        $('#input-payment').prop('disabled', false)
        // $('#input-ignored').prop('disabled', false)
    }
}

function init() {
    dt = $('#table-customer-list').DataTable({
        dom: '<"toolbar">frtip',
        serverSide: true,
        pageLength: 15,
        // lengthChange: false,
        ajax: {
            url: '/transaction/cashier-datatable'
        },
        initComplete: function () {
            $("div.toolbar").prepend(`<button type="button" onclick="resetViewInvoice()" style="position: absolute;" class="btn btn-warning btn-sm"><span class="glyphicon glyphicon-erase"></span> Hapus Detail</button>`);
            intervalDt = setInterval('dt.ajax.reload()', 10000);
            // window.onbeforeunload = clearInterval(intervalDt)
        },
        columns: [
            { data: 'order_code', sortable: false, searchable: false },
            { data: 'ordered_by', sortable: false },
            { data: 'table_number', sortable: false },
            {
                data: 'items',
                sortable: false,
                searchable: false,
                render: (data, i, row) => `Rp ${parseInt(data.reduce((p, n) => (p += parseInt(n.qty) * parseInt(n.price)), 0)).toLocaleString('id')}`
            },
            {
                data: 'is_paid',
                searchable: false,
                sortable: false,
                render: (data, i, row) => `<label class="label label-${data == 1 ? 'success' : 'warning'}">${data == 1 ? 'Lunas' : 'Belum Bayar'}</label>`
            }
        ],
        language: { url: '/json/Indonesian-Alternative.json' }
    })
}