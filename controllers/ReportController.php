<?php

namespace app\controllers;

use app\models\Order;
use app\models\OrderSearch;
use Carbon\Carbon;
use Dompdf\Dompdf;
use kartik\mpdf\Pdf;
use Tightenco\Collect\Support\Collection;
use Yii;

class ReportController extends \yii\web\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionSales()
    {
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
}
