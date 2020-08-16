$("#btn-print-report").on("click", function () {
  let year = $("#year-selector").val();
  let month = $("#month-selector").val();

  window.open(`/report/print?month=${month}&year=${year}`, "_blank");
});
