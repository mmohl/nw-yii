<?php
// $this->registerJsFile = '';
$this->title = 'Dashboard';

$this->params['breadcrumbs'] = [];

// $this->registerJsFile(
//     '@web/js/dashboard.js',
//     ['depends' => [\yii\web\JqueryAsset::className()]]
// );

$this->registerJsFile('@web/libraries/chart.js/dist/Chart.min.js');
$this->registerCssFile("@web/libraries/chart.js/dist/Chart.min.css");
?>

<h1>Hello</h1>