<?php

use app\models\Report;

$this->params['breadcrumbs'] = '';
$this->title = 'Laporan Grafik';

$this->registerCssFile("@web/libraries/select2/dist/css/select2.min.css");
$this->registerCssFile("@web/libraries/chart.js/dist/Chart.min.css");

$this->registerJsFile("@web/libraries/chart.js/dist/Chart.min.js", ['depends' => [\yii\web\JqueryAsset::class]]);
// $this->registerJsFile("@web/concept/assets/vendor/charts/morris-bundle/raphael.min.js", ['depends' => [\yii\web\JqueryAsset::class]]);
// $this->registerJsFile("@web/concept/assets/vendor/charts/morris-bundle/morris.js", ['depends' => [\yii\web\JqueryAsset::class]]);
$this->registerJsFile("@web/js/report/chart.js", ['depends' => [\yii\web\JqueryAsset::class]]);
$this->registerJsFile("@web/libraries/moment/min/moment.min.js", ['depends' => [\yii\web\JqueryAsset::class]]);
$this->registerJsFile("@web/libraries/moment/locale/id.js", ['depends' => [\yii\web\JqueryAsset::class]]);
$this->registerJsFile("@web/libraries/select2/dist/js/select2.min.js", ['depends' => [\yii\web\JqueryAsset::class]]);
$this->registerJsFile("@web/libraries/select2/dist/js/i18n/id.js", ['depends' => [\yii\web\JqueryAsset::class]]);
?>

<div class="card">
  <h5 class="card-header">
    <div class="row">
      <div class="col-lg-4">
        <select name="" id="report-types" class="form-control">
          <option value="<?= Report::REPORT_TYPE_DAY ?>">Harian</option>
          <option value="<?= Report::REPORT_TYPE_MONTH ?>">Bulanan</option>
          <option value="<?= Report::REPORT_TYPE_ANNUAL ?>">Tahunan</option>
        </select>
      </div>
      <div class="col-lg-8">
        <select name="" id="report-parameters" class="form-control"></select>
      </div>
    </div>
  </h5>
  <div class="card-body">
    <canvas width="400" height="400" id="chart-report"></canvas>
  </div>
  <div class="card-footer">
  </div>
</div>