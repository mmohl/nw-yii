var select2Options = null;

$(document).ready(() => {
  let type = $("#report-types").val();

  buildChart(type);
});

$("#report-types").on("change", ({ target }) => {
  let type = $(target).val();

  buildChart(type);
});

function getChartOptions(type) {
  return $.ajax({
    url: `/report/chart-options`,
    data: { type },
  }).promise();
}

async function buildChart(type) {
  let options = await getChartOptions(type);

  if (type == "tahun") {
    $("#report-parameters").empty();
    $("#report-parameters").prop("disabled", true);
  } else if (select2Options) {
    $("#report-parameters").prop("disabled", false);
    // $("#report-parameters").select2("destroy");
    $("#report-parameters").empty();
  }

  options.forEach(({ label, value, isEnabled, isSelected }) => {
    $("#report-parameters").append(
      `<option  value="${value}" 
      ${isEnabled ? "" : "disabled"}
      ${isSelected ? "selected" : ""}>${label}</option>`
    );
  });

  select2Options = $("#report-parameters").select2({
    multiple: true,
    tags: true,
  });

  $("#report-parameters").trigger("change");
}

$("#report-parameters").on("change", () => {
  let values = $("#report-parameters").select2("val");
  let type = $("#report-types").val();

  getChart(type, values);
});

function getChart(type, values) {
  $.ajax({
    url: `/report/get-chart`,
    data: { values, type },
  }).then(({ labels, datasets }) => {
    $("#chart-report").empty();
    let ctx = document.getElementById("chart-report");

    let myChart = new Chart(ctx, {
      type: "line",
      data: {
        labels,
        datasets,
      },
      options: {
        maintainAspectRatio: false,
        scales: {
          yAxes: [
            {
              ticks: {
                // Include a dollar sign in the ticks
                callback: function (value, index, values) {
                  return value.toLocaleString("id");
                },
              },
            },
          ],
        },
        tooltips: {
          callbacks: {
            label: (tooltipItem, data) =>
              tooltipItem.yLabel.toLocaleString("id"),
          },
        },
      },
    });
  });
}
