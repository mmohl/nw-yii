<?php
// $this->registerJsFile = '';
$this->title = 'Dashboard';

$this->registerJsFile(
    '@web/js/dashboard.js',
    ['depends' => [\yii\web\JqueryAsset::className()]]
);

$this->registerJsFile('@web/libraries/chart.js/dist/Chart.min.js');
$this->registerCssFile("@web/libraries/chart.js/dist/Chart.min.css");
?>

<div class="row">
    <div class="col-lg-3">
        <div class="panel panel-primary" id="total-sales">
            <div class="panel-body">
                <h4>Total Pesanan</h4>
            </div>
        </div>
    </div>
    <div class="col-lg-3">
        <div class="panel panel-info" id="total-omset">
            <div class="panel-body">
                <h4>Total Omset</h4>
            </div>
        </div>
    </div>
    <div class="col-lg-3">
        <div class="panel panel-warning" id="total-profit">
            <div class="panel-body">
                Total Profit
            </div>
        </div>
    </div>
    <!-- <div class="col-lg-3">
        <div class="panel panel-danger" id="total-">
            <div class="panel-body">
                Total Penjualan
            </div>
        </div>
    </div> -->
</div>

<canvas id="item-chart" width="10" height="10"></canvas>