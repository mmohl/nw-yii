var dt = null
var currentOrderCode = null
var intervalDt = null

$(document).ready(() => {
    init()
})

$(document).on('click', '#table-customer-list tbody tr', ({ target }) => {
    const tr = $(target).closest('tr')
    const [tdCode, , , tdStatus] = tr.children()
    const orderCode = $(tdCode).html()
    const status = $(tdStatus).children('label').html()
    const r = `cashier/get-order`
    currentOrderCode = orderCode

    $.ajax({
        url: '/',
        data: { r, orderCode }
    }).then(res => {
        $('#invoice-detail tbody').empty()

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
        $('#input-tax').val(res.taxTotal.toLocaleString('id'))
        $('#input-before-rounding').val(res.total.toLocaleString('id'))
        $('#input-rounding').val(res.rounded.toLocaleString('id'))
        $('#input-total').val((res.total + res.rounded).toLocaleString('id'))

        if (status.toLowerCase() == 'lunas') {
            $('#input-payment').prop('disabled', true)
        } else {
            $('#input-payment').prop('disabled', false)
        }
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
    const r = 'cashier/pay-order'
    const orderCode = currentOrderCode
    const payment = $('#input-payment').val() ? $('#input-payment').val().replace(/\./ig, '') : null

    $.ajax({
        url: '/',
        data: { r, orderCode, payment }
    }).then(res => {
        dt.ajax.reload()
        resetViewInvoice()
    }).fail(err => {
        window.alert('gagal melakukan pembayaran, silahkan coba lagi')
    })
})

function resetViewInvoice() {
    currentOrderCode = null
    $('#invoice-detail tbody').empty()

    $('#input-sub-total').val('')
    $('#input-tax').val('')
    $('#input-before-rounding').val('')
    $('#input-rounding').val('')
    $('#input-total').val('')
    $('#input-payment').val('')
    $('#input-changes').val('')
    $('#btn-invoice-pay').prop('disabled', true)
}

function init() {
    dt = $('#table-customer-list').DataTable({
        serverSide: true,
        ajax: {
            url: '/index.php?r=cashier/cashier-datatable'
        },
        initComplete: function () {
            intervalDt = setInterval('dt.ajax.reload()', 10000);
            // window.onbeforeunload = clearInterval(intervalDt)
        },
        columns: [
            { data: 'order_code' },
            { data: 'ordered_by' },
            {
                data: 'items',
                sortable: false,
                searchable: false,
                render: (data, i, row) => `Rp ${parseInt(data.reduce((p, n) => (p += parseInt(n.qty) * parseInt(n.price)), 0)).toLocaleString('id')}`
            },
            {
                data: 'is_paid',
                searchable: false,
                render: (data, i, row) => `<label class="label label-${data == 1 ? 'success' : 'warning'}">${data == 1 ? 'Lunas' : 'Belum Bayar'}</label>`
            }
        ],
        language: { url: '/json/Indonesian-Alternative.json' }
    })
}