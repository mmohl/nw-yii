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

$(document).ready(() => {
  getMonths(getYear());
});

$("#year-selector").on("change", ({ target }) => {
  getMonths($(target).val());
});

function getMonths(year) {
  $.ajax({
    url: `/report/get-months?year=${getYear()}`,
  }).then((res) => {
    let { months } = res;
    $("#month-selector").empty();
    months.forEach((month) => {
      $("#month-selector").append(
        `<option ${month.isEnabled ? "" : "disabled"} ${
          month.isSelected ? "selected" : ""
        } value="${month.value}">${month.name}</option>`
      );
    });
    $("#month-selector").prop("disabled", false);
  });
}

function getYear() {
  return $(".toolbar-container div").children("select").first().val();
}
