<?php

use app\models\Order;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use kartik\date\DatePicker;
use yii\web\View;

$this->title = 'Laporan Penjualan';

$this->params['breadcrumbs'] = 'Sales';

$this->registerJsFile(
    '@web/js/report_print.js',
    ['depends' => [\yii\web\JqueryAsset::class]]
);

$this->registerJsFile(
    '@web/js/report/sales.js',
    ['depends' => [\yii\web\JqueryAsset::class]]
);

echo GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'showPageSummary' => true,
    'pjax' => true,
    'pjaxSettings' => [
        'neverTimeout' => true,
        'options' => [
            'id' => 'w0',
        ]
    ],
    'striped' => false,
    'hover' => true,
    'panel' => ['type' => 'default', 'heading' => 'Data Penjualan'],
    'toggleDataContainer' => ['class' => 'btn-group mr-2'],
    'export' => false,
    'toolbar' => [
        [
            'content' =>
            Html::dropDownList('year-selector', date('Y'), Order::generateYearSelector(), [
                'id' => 'year-selector',
                'title' => 'Pilih tahun',
                'class' => 'form-control'

            ]) . '&nbsp;'  . Html::dropDownList('year-selector', date('Y'), ['Pilih Bulan'], [
                'id' => 'month-selector',
                'title' => 'Pilih bulan',
                'class' => 'form-control',
                'disabled' => true
            ])  . '&nbsp;' .
                Html::button('<i class="glyphicon glyphicon-print"></i> Cetak Laporan', [
                    'type' => 'button',
                    'title' => 'Cetak Laporan',
                    'class' => 'btn btn-primary',
                    'id' => 'btn-print-report'
                ]),
            'options' => ['class' => '', 'style' => 'display: flex;']
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
                    'todayHighlight' => true,
                    'endDate' => date('Y-m-d')
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
                    if ($model->is_ignored == 0) $total += ($item->qty * $item->price);
                }
                if ($model->is_ignored == '1') $total = 0;

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
                    if ($model->is_ignored == 0) $total += ($item->qty * $item->price);
                }

                $tax = round(($total * 10) / 100);
                if ($model->is_ignored == '1') $tax = 0;

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
                    if ($model->is_ignored == 0) $total += ($item->qty * $item->price);
                }

                $tax = round(($total * 10) / 100);
                if ($model->is_ignored == '1') $tax = 0;

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
                if ($model->is_ignored == '1') $tax = 0;

                return $model->total_payment - ($total + $tax + $model->rounding);
            }
        ],
        [
            'class' => '\kartik\grid\CheckboxColumn',
            // 'attribute' => 'is_ignored',
            'checkboxOptions' => function ($model, $key, $index, $widget) {
                $options = ["value" => $model->is_ignored, 'data-id' => $model->id, 'title' => 'Abaikan transaksi ini?'];
                if ($model->is_ignored == 1) $options['checked'] = 'checked';

                return $options;
            },
        ],
    ],
]);
