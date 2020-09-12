var chartItems;

$(document).ready(() => {
    init()
})

function init() {
    getDashboard();
}


function initChart(payload) {
    var ctx = document.getElementById('item-chart');
    var myChart = new Chart(ctx, {
        type: 'line',
        // responsive: false,
        data: {
            labels: payload.labels,
            datasets: payload.datasets
        },
        options: {
            responsive: true,
            aspectRatio: 3,
            maintainAspectRatio: false,
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true,
                        suggestedMax: payload.labels.length + 1,
                        stepSize: 1
                    }
                }]
            }
        }
    });
}


function getDashboard() {
  $.ajax({
    url: "/site/dashboard",
  }).then((res) => {
    const {
      salesInfo,
      newCustomers,
      customers,
      totalOrders,
      unpaidOrders,
      sellItems,
    } = res;

    $("#data-sales").html(
      `Rp ${(parseInt(salesInfo.value) / 1000).toLocaleString("id")}k`
    );
    $("#data-new-customers").html(newCustomers.value);
    $("#data-customers").html(customers.value);
    $("#data-orders").html(totalOrders.value);

    $("#table-unpaid-orders tbody").empty();
    unpaidOrders.forEach(({ order_code, ordered_by, table_number }, index) => {
      $("#table-unpaid-orders tbody").append(
        `<tr><td>${
          index + 1
        }</td><td>${order_code}</td><td>${ordered_by}</td><td>${table_number}</td></tr>`
      );
    });

    let isSalesPositive = salesInfo.comparator > 0
    $("#data-sales-percentage").append(
      `<span class="icon-circle-small icon-box-xs text-${isSalesPositive ? 'success' : 'danger'} bg-${isSalesPositive ? 'success' : 'danger'}-light"><i class="fa fa-fw fa-arrow-${isSalesPositive ? 'up' : 'down'}"></i></span><span class="ml-1">${salesInfo.comparator}%</span>`
    )
    $("#data-sales-percentage").addClass(isSalesPositive ? 'text-success' : 'text-danger');
    let isCustomersPositive = customers.comparator > 0
    $("#data-customers-percentage").append(
      `<span class="icon-circle-small icon-box-xs text-${isCustomersPositive ? 'success' : 'danger'} bg-${isCustomersPositive ? 'success' : 'danger'}-light"><i class="fa fa-fw fa-arrow-${isCustomersPositive ? 'up' : 'down'}"></i></span><span class="ml-1">${customers.comparator}%</span>`
    )
    $("#data-customers-percentage").addClass(isSalesPositive ? 'text-success' : 'text-danger');
    let isOrdersPositive = totalOrders.comparator > 0
    $("#data-orders-percentage").append(
      `<span class="icon-circle-small icon-box-xs text-${isOrdersPositive ? 'success' : 'danger'} bg-${isOrdersPositive ? 'success' : 'danger'}-light"><i class="fa fa-fw fa-arrow-${isOrdersPositive ? 'up' : 'down'}"></i></span><span class="ml-1">${totalOrders.comparator}%</span>`
    ).parent().addClass(isSalesPositive ? 'text-success' : 'text-danger');
    $("#data-orders-percentage").addClass(isSalesPositive ? 'text-success' : 'text-danger');

    let tmpSellItems = Object.entries(sellItems);
    chartItems = c3.generate({
      bindto: "#c3chart_category",
      data: {
        columns: tmpSellItems,
        type: "donut",

        //   onclick: function (d, i) {
        //     console.log("onclick", d, i);
        //   },
        //   onmouseover: function (d, i) {
        //     console.log("onmouseover", d, i);
        //   },
        //   onmouseout: function (d, i) {
        //     console.log("onmouseout", d, i);
        //   },

        //   colors: {
        //     Men: "#5969ff",
        //     Women: "#ff407b",
        //     Accessories: "#25d5f2",
        //     Children: "#ffc750",
        //     Apperal: "#2ec551",
        //   },
      },
      // donut: {
      //   label: {
      //     show: false,
      //   },
      // },
    });
  });
}