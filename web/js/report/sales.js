$(document).on('change', '.kv-row-checkbox', elm => {
    console.dir(elm)
    $.ajax({
        url: '/'
    })
})

// $('').on('change', elm => {
//     console.dir(elm)
// })