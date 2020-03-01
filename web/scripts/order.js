var currentMenu = null

$(document).ready(() => {
    init()

    $('#tab-big a').click(function (e) {
        e.preventDefault()
        $(this).tab('show')
        const { target } = e

        getItems($(target).data('category'), 1, 10)
    })

    $('#tab-small a').click(function (e) {
        e.preventDefault()
        $(this).tab('show')
        const { target } = e

        getItems($(target).data('category'), 1, 10)
    })

    $('.counter').on("click", ({ target }) => {
        const button = $(target).closest('button')
        const type = $(button).data('type')

        if (type == 'add') {
            let currentValue = parseInt($('#total-will-be-ordered').val())
            currentValue++
            $('#total-will-be-ordered').val(currentValue)
        } else {
            let currentValue = parseInt($('#total-will-be-ordered').val())
            currentValue--
            if (currentValue >= 1) $('#total-will-be-ordered').val(currentValue)
        }
    })

    $('#input-ordered-by').on('keyup', _.debounce(e => {
        let text = $('#input-ordered-by').val().trim()
        const items = JSON.parse(localStorage.getItem('orders'))

        if (text.length >= 2 && items.length > 0) {
            $('#btn-trigger-order-create').prop('disabled', false)
        } else {
            $('#btn-trigger-order-create').prop('disabled', true)
        }
    }, 100))

    $('#btn-trigger-order-create').on('click', () => {
        const data = JSON.stringify({
            orderedBy: $('#input-ordered-by').val(),
            items: JSON.parse(localStorage.getItem("orders")),
            _csrf: yii.getCsrfToken()
        })

        $.ajax({
            url: '/index.php?r=cashier/make-order',
            contentType: "application/json; charset=utf-8",
            data,
            dataType: 'JSON',
            method: 'POST'
        }).then(({ message }) => {
            localStorage.removeItem('orders')
            window.alert(message)
            setTimeout(() => window.location.reload(), 500)
        })
    })
})

$(document).on("click", '.counter-table', ({ target }) => {
    const button = $(target).closest('button')
    const type = $(button).data('type')

    if (type == 'add') {
        const input = $(button).parent().prev()
        const menuId = $(input).data('menuId')

        let val = $(input).val()
        val++

        let orders = JSON.parse(localStorage.getItem("orders"))
        for (let i = 0; i < orders.length; i++) {
            if (orders[i].id == menuId) {
                orders[i].qty = val
            }
        }

        localStorage.setItem('orders', JSON.stringify(orders))
        renderTableCart()
        // $(input).val(val)
    } else {
        const input = $(button).parent().next()
        const menuId = $(input).data('menuId')
        let val = $(input).val()
        val--

        if (val == 0) {
            removeMenuOrder(menuId)
            renderTableCart()
            showTotalItem()
        } else {
            let orders = JSON.parse(localStorage.getItem("orders"))
            for (let i = 0; i < orders.length; i++) {
                if (orders[i].id == menuId) {
                    orders[i].qty = val
                }
            }

            localStorage.setItem('orders', JSON.stringify(orders))
            renderTableCart()
            // $(input).val(val)
        }
    }
})

$(document).on('click', '.menu-item', ({ target }) => {
    const holder = $(target).closest('div.menu-item')
    const id = $(holder).data('menuId')

    getItem(id)
})

$(document).on('click', '#btn-menu-order-remove', ({ target }) => {
    const button = $(target).closest('button')
    const menuId = $(button).data('menuId')

    removeMenuOrder(menuId)

    renderTableCart()
    showTotalItem()
})

$('#btn-trigger-modal-cart').on('click', () => {
    renderTableCart()

    $('#modal-cart').modal('show')
})

$("#btn-trigger-order-save").on('click', () => {
    if ($('#total-will-be-ordered').val() == 0) {
        alert('Minimal pesanan menu adalah 1.')
        return
    }

    let list = []
    let existingOrder = localStorage.getItem('orders')
    const order = { ...currentMenu, qty: $('#total-will-be-ordered').val() }

    if (existingOrder) {
        existingOrder = JSON.parse(existingOrder)
        list = [...existingOrder]
    }

    list.push(order)
    localStorage.setItem('orders', JSON.stringify(list))

    showTotalItem()
    $('#modal-item-order').modal('hide')
})

function removeMenuOrder(menuId) {
    let orders = JSON.parse(localStorage.getItem('orders'))

    orders = orders.filter(order => order.id != menuId)
    localStorage.setItem('orders', JSON.stringify(orders))
}

function renderTableCart() {
    $('#modal-cart').children().first().children().first().children('div.modal-body').empty()
    const orders = JSON.parse(localStorage.getItem('orders')) || []
    let rows = ''

    orders.forEach(order => {
        let tr = `<tr><td>${order.name}</td>
            <td>
            <div class="input-group" style="width: 10em">
                <span class="input-group-btn">
                    <button class="btn btn-default counter-table" type="button" data-type="minus">
                        <span class="glyphicon glyphicon-minus"></span>
                    </button>
                </span>
                <input data-menu-id="${order.id}" readonly type="text" class="form-control" value="${order.qty}">
                <span class="input-group-btn">
                    <button class="btn btn-default counter-table" type="button" data-type="add">
                        <span class="glyphicon glyphicon-plus"></span>
                    </button>
                </span>
            </div>
            </td>
            <td><strong>Rp ${(order.qty * order.price).toLocaleString('id')}</strong></td>
            <td>
                <button class="btn btn-danger" type="button" id="btn-menu-order-remove" data-menu-id="${order.id}">
                    <span class="glyphicon glyphicon-trash"></span>
                </button>
            </td>
            </tr>`
        rows += tr
    })

    $('#modal-cart').children().first().children().first().children('div.modal-body')
        .append(`
            <table class="table">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Qty</th>
                        <th>Total Harga</th>
                        <th>&nbsp;</th>
                    </tr>
                </thead>
                <tbody>
                    ${rows}
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="2"><strong>Total Transaksi</strong></td>
                        <td colspan="2"><strong>Rp ${orders.reduce((p, n) => (n.price * n.qty) + p, 0).toLocaleString('id')}</strong></td>
                    </tr>
                </tfoot>
            </table>
    `)
}

function showTotalItem() {
    let total = localStorage.getItem('orders')

    if (total) {
        total = JSON.parse(total)
    } else {
        total = []
    }

    $('#total-items').html(total.length)
}

function getItems(category, page, perPage) {
    const r = 'menu/menu-items'

    $.ajax({
        url: '/',
        data: { r, category, page, perPage }
    }).then(({ data, page, perPage, total }) => {
        $('.tab-pane').each((i, div) => {
            $(div).empty()
        })

        if (data.length > 0) {
            data.forEach(item => {
                let panel = `
                <div class="col-md-3 menu-item" data-menu-id="${item.id}">
                    <div class="thumbnail">
                        <img src="/images/${category}/${item.img}" alt="${item.name}">
                    <div class="caption">
                        <h4>${item.name}</h4>
                        <p>Rp ${parseInt(item.price).toLocaleString('id')}</p>
                    </div>
                </div>
                `
                $(`#${category}`).append(panel)
            })
        } else {
            let panel = `
            <div class="col-md-3 menu-item" data-menu-id="${item.id}">
                <p>Menu kosong</p>
            </div>
            `
            $(`#${category}`).append(panel)
        }


    })
}

function init() {
    let activeCategory = $('li.active').first().children('a').data('category')
    getItems(activeCategory, 1, 10)
    showTotalItem()
}

function getItem(id) {
    const r = 'menu/menu-item'
    $.ajax({
        url: '/',
        data: { r, id }
    }).then(({ data }) => {
        currentMenu = data
        resetModal()
        setItemToModal(data)
        $('#modal-item-order').modal('show')
    })
}

function setItemToModal(item) {
    $('#modal-menu-item-image').attr('src', `/images/${item.category}/${item.img}`)
    $('.modal-title').first().html(item.name)
}

function resetModal() {
    $('.modal-title').first().html('')
    $('#total-will-be-ordered').val('1')
    // $('.modal-body').first().empty()
}