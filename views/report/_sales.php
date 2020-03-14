<?php

use app\models\Order;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use kartik\date\DatePicker;
use yii\web\View;

$this->title = 'Laporan Penjualan';

$this->registerJsFile(
    '@web/js/report_print.js',
    ['depends' => [\yii\web\JqueryAsset::className()]]
);

echo GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'showPageSummary' => true,
    'pjax' => true,
    'striped' => false,
    'hover' => true,
    'panel' => ['type' => 'default', 'heading' => 'Data Penjualan'],
    'toggleDataContainer' => ['class' => 'btn-group mr-2'],
    'export' => false,
    'toolbar' => [
        [
            'content' =>
            Html::button('<i class="glyphicon glyphicon-print"></i> Cetak Laporan', [
                'type' => 'button',
                'title' => 'Cetak Laporan',
                'class' => 'btn btn-primary',
                'id' => 'btn-print-report'
            ]),
            'options' => ['class' => 'btn-group-sm']
        ]
    ],
    // 'toggleDataContainer' => ['class' => 'btn-group-sm'],
    'exportContainer' => ['class' => 'btn-group-sm'],
    'columns' => [
        [
            'attribute' => 'date',
            'filterType' => GridView::FILTER_DATE,
            // 'filter' => ArrayHelper::map(Order::find()->select('date')->orderBy('date')->asArray()->distinct(true)->all(), 'date', 'date'),
            'filterWidgetOptions' => [
                'pluginOptions' => [
                    'format' => 'yyyy-mm-dd',
                    'todayHighlight' => true
                ]
            ],
            // 'filterInputOptions' => ['placeholder' => 'Any supplier'],
            'group' => true,  // enable grouping
            'groupFooter' => function ($model, $key, $index, $widget) { // Closure method
                return [
                    'mergeColumns' => [[0, 3], [5, 6]], // columns to merge in summary
                    'content' => [             // content to show in each summary cell
                        1 => 'Total',
                        // 3 => GridView::F_SUM,
                        4 => GridView::F_SUM,
                        7 => GridView::F_SUM,
                    ],
                    'contentFormats' => [      // content reformatting for each summary cell
                        3 => ['format' => 'number', 'thousandSep' => '.'],
                        4 => ['format' => 'number', 'thousandSep' => '.'],
                        7 => ['format' => 'number', 'thousandSep' => '.'],
                    ],
                    'contentOptions' => [      // content html attributes for each summary cell
                        0 => ['style' => 'text-align:center'],
                        4 => ['style' => 'text-align:right'],
                        7 => ['style' => 'text-align:right'],
                    ],
                    // html attributes for group summary row
                    'options' => ['class' => 'info table-info', 'style' => 'font-weight:bold;']
                ];
            }
        ],
        [
            'class' => 'kartik\grid\ExpandRowColumn',
            'width' => '50px',
            'value' => function ($model, $key, $index, $column) {
                return GridView::ROW_COLLAPSED;
            },
            'detail' => function ($model, $key, $index, $column) {
                return Yii::$app->controller->renderPartial('_expand-row', ['model' => $model]);
            },
            'headerOptions' => ['class' => 'kartik-sheet-style'],
            'expandOneOnly' => true
        ],
        [
            'attribute' => 'order_code'
        ],
        [
            // 'class' => '\kartik\grid\SerialColumn',
            'attribute' => 'subTotal',
            'hAlign' => GridView::ALIGN_RIGHT,
            'format' => ['decimal', 0],
            // 'pageSummary' => true,
            'value' => function ($model) {
                $total = 0;
                foreach ($model->items as $item) {
                    $total += ($item->qty * $item->price);
                }

                return $total;
            }
        ],
        [
            'attribute' => 'tax',
            'pageSummary' => true,
            'hAlign' => GridView::ALIGN_RIGHT,
            'pageSummaryFunc' => GridView::F_SUM,
            'format' => ['decimal', 0],
            'value' => function ($model) {
                $total = 0;
                foreach ($model->items as $item) {
                    $total += ($item->qty * $item->price);
                }

                return round(($total * 10) / 100);
            }
        ],
        [
            // 'class' => '\kartik\grid\SerialColumn',
            'attribute' => 'beforeRounding',
            'hAlign' => GridView::ALIGN_RIGHT,
            'format' => ['decimal', 0],
            // 'pageSummary' => true,
            'value' => function ($model) {
                $total = 0;
                foreach ($model->items as $item) {
                    $total += ($item->qty * $item->price);
                }

                $tax = round(($total * 10) / 100);

                return $total + $tax;
            }
        ],
        [
            'attribute' => 'rounding',
            'hAlign' => GridView::ALIGN_RIGHT,
            // 'pageSummary' => true,
            'pageSummaryFunc' => GridView::F_SUM,
            'format' => 'text',
            'value' => function ($model) {
                return $model->rounding > 0 ? "+{$model->rounding}" : $model->rounding;
            }
        ],
        [
            'attribute' => 'totalRounding',
            'label' => 'Setelah Rounding',
            'hAlign' => GridView::ALIGN_RIGHT,
            'pageSummary' => true,
            'pageSummaryFunc' => GridView::F_SUM,
            'format' => ['decimal', 0],
            'value' => function ($model) {
                $total = 0;
                foreach ($model->items as $item) {
                    $total += ($item->qty * $item->price);
                }

                $tax = round(($total * 10) / 100);

                return $total + $tax + $model->rounding;
            }
        ],
        [
            'attribute' => 'total_payment',
            'hAlign' => GridView::ALIGN_RIGHT,
            // 'pageSummary' => true,
            'pageSummaryFunc' => GridView::F_SUM,
            'format' => ['decimal', 0]
        ],
        [
            'attribute' => 'changes',
            'hAlign' => GridView::ALIGN_RIGHT,
            // 'pageSummary' => true,
            'pageSummaryFunc' => GridView::F_SUM,
            'format' => ['decimal', 0],
            'value' => function ($model) {
                $total = 0;
                foreach ($model->items as $item) {
                    $total += ($item->qty * $item->price);
                }

                $tax = ($total * 10) / 100;

                return $model->total_payment - ($total + $tax + $model->rounding);
            }
        ],
    ],
]);
