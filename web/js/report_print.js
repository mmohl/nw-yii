$("#btn-print-report").on("click", function () {
  let year = $("#year-selector").val();

  window.open(`/report/print?month=1&year=${year}`, "_blank");
});
