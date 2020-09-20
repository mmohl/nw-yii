<?php

namespace app\controllers;

use app\helpers\Str;
use app\models\Order;
use app\models\OrderSearch;
use app\models\Report;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Dompdf\Dompdf;
use kartik\mpdf\Pdf;
use Tightenco\Collect\Support\Collection;
use Yii;

class ReportController extends \yii\web\Controller
{
    public $layout = 'concept/main';

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionSales()
    {
        $this->layout = 'concept/main';

        $searchModel = new OrderSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('_sales', ['dataProvider' => $dataProvider, 'searchModel' => $searchModel]);
    }

    public function actionExpand()
    {
        if (isset($_POST['expandRowKey'])) {
            $model = Order::findOne($_POST['expandRowKey']);
            return $this->renderPartial('_expand-row', ['model' => $model]);
        } else {
            return '<div class="alert alert-danger">No data found</div>';
        }
    }

    public function actionGetMonths($year)
    {
        $order = Order::find()->select('date')->where(["EXTRACT(YEAR FROM date)" => "$year"])->distinct()->orderBy('date desc')->one();
        Carbon::setLocale('id');
        $carbon = Carbon::createFromFormat('Y-m-d', $order->date);
        $holder = collect();
        $currentYear = date('Y');

        for ($i = 1; $i <= 12; $i++) {
            $tmpCarbon = Carbon::createFromFormat('Y-m-d', date("Y-$i-1"));
            $monthName = $tmpCarbon->monthName;
            if ($currentYear > $year) {
                $isEnabled = true;
                $isSelected = false;
            } else {
                $isEnabled = $carbon->month >= $i;
                $isSelected = $carbon->month == $i;
            }

            $holder->add(['name' => $monthName, 'value' => $i, 'isEnabled' => $isEnabled, 'isSelected' => $isSelected]);
        }

        return $this->asJson(['months' => $holder->toArray()]);
    }

    public function actionPrint($month, $year)
    {
        // get your HTML raw content without any layouts or scripts
        Carbon::setLocale('id');
        $tmpCarbon = Carbon::createFromFormat('Y-m', "$year-$month");
        $days = $tmpCarbon->daysInMonth;

        $tmp = Order::find()->where(['EXTRACT(MONTH from date)' => $month, 'EXTRACT(YEAR from date)' => $year])->andWhere(['is_ignored' => 0])->orderBy('date')->all();
        $tmp = Collection::wrap($tmp)->groupBy('date')->mapWithKeys(function ($group, $key) {
            $date = explode('-', $key);
            return [intval($date[2]) => $group];
        });
        // $tmp = $tmp;
        $orders = Collection::wrap([]);

        for ($i = 1; $i <= $days; $i++) {
            if ($tmp->get($i)) {
                $orders->put($i, $tmp->get($i));
            } else {
                $orders->put($i, null);
            }
        }

        $this->layout = 'print';
        $content =  $this->render('_print', ['orders' => $orders, 'month' => $tmpCarbon->monthName, 'year' => $year]);

        // instantiate and use the dompdf class
        $dompdf = new Dompdf();
        $dompdf->loadHtml($content);

        // (Optional) Setup the paper size and orientation
        $dompdf->setPaper('A4', 'potrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser
        // $dompdf->output();
        ob_end_clean();
        return $dompdf->stream("Laporan-Penjualan-{$tmpCarbon->monthName}-$year.pdf");
    }

    public function actionChart()
    {
        $options = collect([]);

        return $this->render('_chart', ['options' => $options]);
    }

    public function actionChartOptions($type)
    {
        Carbon::setLocale('id');
        $payload = collect([]);

        if ($type == Report::REPORT_TYPE_DAY) {
            $currentMonth = intval(date('m'));

            for ($i = 1; $i <= 12; $i++) {
                $monthName = Carbon::parse(date("Y-$i-1"))->translatedFormat('F');
                $payload->add(['label' => $monthName, 'value' => $i, 'isEnabled' => $i <= $currentMonth, 'isSelected' => $currentMonth == $i]);
            }
        } else if ($type == Report::REPORT_TYPE_MONTH) {
            $currentYear = intval(date('Y'));
            $fromYear = $currentYear - 10;

            for ($year = $fromYear; $year <= $currentYear; $year++) {
                $payload->add(['label' => $year, 'value' => $year, 'isEnabled' => true, 'isSelected' => $currentYear == $year]);
            }
        } else if ($type == Report::REPORT_TYPE_ANNUAL) {
        }

        return $this->asJson($payload->toArray());
    }

    public function actionGetChart($type)
    {
        Carbon::setLocale('id');

        $label = '';
        $datasets = collect();
        $labels = collect([]);

        $values = Yii::$app->request->getQueryParam('values');

        if ($type == Report::REPORT_TYPE_DAY) {
            // map inputs so month have zero leading
            $values = collect($values)->map(function ($month) {
                $month = intval($month);
                return $month < 10 ? str_pad($month, 2, '0', STR_PAD_LEFT) : $month;
            })->sort()->values();

            // iterate for calculate selected month
            foreach ($values as $month) {
                $label = Carbon::parse(date("Y-$month-1"))->translatedFormat('F Y');
                $orders = collect(Order::find()->where(['=', 'extract(month from date)', $month])->andWhere(['=', 'extract(year from date)', date('Y')])->orderBy('date')->all())->groupBy('date');
                $lastDayOnThisMonth = intval(Carbon::createFromFormat('Y-m-d', date("Y-$month-1"))->endOfMonth()->format('d'));

                $datesHolder = collect();

                for ($startDate = 1; $startDate <= $lastDayOnThisMonth; $startDate++) {
                    $totalOrder = 0;
                    $startDatePad = str_pad($startDate, 2, '0', STR_PAD_LEFT);

                    if ($orders->get(date("Y-$month-$startDatePad"))) {
                        $totalOrder = $orders->get(date("Y-$month-$startDatePad"))->map(function ($order) {
                            $total = collect($order->items)->reduce(function ($init, $val) {
                                $init += $val->qty * $val->price;
                                return $init;
                            }, 0);

                            return $total;
                        })->reduce(function ($init, $val) {
                            $init += $val;
                            return $init;
                        }, 0);
                    }

                    $datesHolder->put($startDatePad, $totalOrder);
                }

                $labels = $labels->merge($datesHolder->keys());

                $datasets->add(['fill' => false, 'data' => $datesHolder->values(), 'label' => $label, 'borderColor' => [Str::rand_color()], 'pointRadius' => 3]); //$datesHolder;
            }

            $labels = $labels->unique()->sort()->values();
        } else if ($type == Report::REPORT_TYPE_MONTH) {
            foreach ($values as $year) {
                $label = $year;
                $monthHolder = collect();
                $orders = collect(Order::find()->select(['date', 'id'])->where(['=', 'extract(year from date)', $year])->orderBy('date')->all())->map(function ($order) {
                    $order->month = Carbon::parse($order->date)->translatedFormat('F');
                    return $order;
                })->groupBy('month');

                for ($i = 1; $i <= 12; $i++) {
                    $totalOrder = 0;
                    $monthName = Carbon::parse(date("$year-$i-1"))->translatedFormat('F');

                    if ($orders->get($monthName)) {
                        $totalOrder = collect($orders->get($monthName))->map(function ($order) {
                            $total = collect($order->items)->reduce(function ($init, $val) {
                                $init += $val->qty * $val->price;
                                return $init;
                            }, 0);

                            return $total;
                        })->reduce(function ($init, $val) {
                            $init += $val;
                            return $init;
                        });
                    }

                    $monthHolder->put($monthName, $totalOrder);
                }

                $labels = $labels->merge($monthHolder->keys());
                $datasets->add(['fill' => false, 'data' => $monthHolder->values(), 'label' => $label, 'borderColor' => [Str::rand_color()], 'pointRadius' => 3]); //$datesHolder;
            }

            $labels = $labels->unique()->values();
        } else if ($type == Report::REPORT_TYPE_ANNUAL) {
            $label = '1 Dasawarsa';
            $yearHolder = collect();
            $currentYear = intval(Carbon::now()->format('Y'));
            $fromYear = Carbon::now()->subYears(10)->format('Y');

            $orders = collect(Order::find()->select(['date', 'id'])->where(['between', 'date', date("$fromYear-1-1"), date("$currentYear-12-31")])->orderBy('date')->all())->map(function ($order) {
                $order->year = Carbon::parse($order->date)->format('Y');
                return $order;
            })->groupBy('year');

            for ($year = $fromYear; $year <= $currentYear; $year++) {
                $totalOrder = 0;

                if ($orders->get($year)) {
                    $totalOrder = collect($orders->get($year))->map(function ($order) {
                        $total = collect($order->items)->reduce(function ($init, $val) {
                            $init += $val->qty * $val->price;
                            return $init;
                        }, 0);

                        return $total;
                    })->reduce(function ($init, $val) {
                        $init += $val;
                        return $init;
                    });
                }

                $yearHolder->put($year, $totalOrder);
            }

            $labels = $labels->merge($yearHolder->keys());
            $datasets->add(['fill' => false, 'data' => $yearHolder->values(), 'label' => $label, 'borderColor' => [Str::rand_color()], 'pointRadius' => 3]);
        }

        return $this->asJson([
            'labels' => $labels,
            'datasets' => $datasets
        ]);
    }
}
