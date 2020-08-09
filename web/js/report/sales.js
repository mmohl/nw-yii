$(document).on("change", ".kv-row-checkbox", ({ target }) => {
  let { id } = $(target).data();
  let is_ignored = $(target).val() == 0 ? 1 : 0;

  $.ajax({
    url: `/transaction/order-modify?id=${id}`,
    data: JSON.stringify({ id, is_ignored }),
    method: "POST",
    contentType: "application/json",
  })
    .then(({ message }) => {
      console.log(message);
      $.pjax.reload({ container: "#w0-container" });
    })
    .fail((err) => {});
});

// $('').on('change', elm => {
//     console.dir(elm)
// })
