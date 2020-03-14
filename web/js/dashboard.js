$(document).ready(() => {
    init()
})

function init() {
    $.ajax({
        url: '/transaction/dashboard',
    }).then(res => {
        const { totalSales, totalOmzet, chartItems } = res

        let tableTotalSales = ''
        let tableTotalOmzet = ''

        Array.from(totalSales).forEach(({ label, value }) => {
            tableTotalSales += (`<tr><td>${label}</td><td>${value}</td></tr>`)
        })

        Array.from(totalOmzet).forEach(({ label, value }) => {
            tableTotalOmzet += (`<tr><td>${label}</td><td>${parseInt(value).toLocaleString('id')}</td></tr>`)
        })

        $('#total-sales').children('.panel-body').append(`<table class="table table-bordered">${tableTotalSales}</table>`)
        $('#total-omset').children('.panel-body').append(`<table class="table table-bordered">${tableTotalOmzet}</table>`)
        initChart(chartItems)
    })

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