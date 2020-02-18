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

    $('.menu-item').on('click', ({ target }) => {

    })
})

function getItems(category, page, perPage) {
    const r = 'menu/menu-items'

    $.ajax({
        url: '/',
        data: { r, category, page, perPage }
    }).then(({ data, page, perPage, total }) => {
        $('.tab-pane').each((i, div) => {
            $(div).empty()
        })

        data.forEach(item => {
            let panel = `
            <div class="col-md-3 menu-item" data-menu-id="${item.id}">
                <div class="thumbnail">
                    <img src="${item.img || '/images/nasi.jpg'}" alt="nasi">
                <div class="caption">
                    <h4>${item.name}</h4>
                    <p>Rp ${parseInt(item.price).toLocaleString('id')}</p>
                </div>
            </div>
            `
            $(`#${category}`).append(panel)
        })
    })
}

function init() {
    let activeCategory = $('li.active').first().children('a').data('category')
    getItems(activeCategory, 1, 10)
}