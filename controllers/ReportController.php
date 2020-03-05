<?php

namespace app\controllers;

use app\models\Order;
use app\models\OrderSearch;
use Carbon\Carbon;
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

    public function actionPrint($month, $year)
    {
        // get your HTML raw content without any layouts or scripts
        $days = Carbon::now()->daysInMonth;
        $tmp = Order::find()->where(['EXTRACT(MONTH from date)' => $month, 'EXTRACT(YEAR from date)' => $year])->orderBy('date')->all();
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

        // dd($tmp, $orders);
        return $this->render('_print', ['orders' => $orders]);


        // // setup kartik\mpdf\Pdf component
        // $pdf = new Pdf([
        //     // set to use core fonts only
        //     'mode' => Pdf::MODE_CORE,
        //     // A4 paper format
        //     'format' => Pdf::FORMAT_A4,
        //     // portrait orientation
        //     'orientation' => Pdf::ORIENT_PORTRAIT,
        //     // stream to browser inline
        //     'destination' => Pdf::DEST_BROWSER,
        //     // your html content input
        //     'content' => $content,
        //     // format content from your own css file if needed or use the
        //     // enhanced bootstrap css built by Krajee for mPDF formatting 
        //     // 'cssFile' => '@vendor/kartik-v/yii2-mpdf/src/assets/kv-mpdf-bootstrap.min.css',
        //     // any css to be embedded if required
        //     // 'cssInline' => '.kv-heading-1{font-size:18px}', 
        //     // set mPDF properties on the fly
        //     'options' => ['title' => 'Krajee Report Title'],
        //     // call mPDF methods on the fly
        //     'methods' => [
        //         'SetHeader' => ['Laporan Penjualan Nandjung Wangi'],
        //         'SetFooter' => ['{PAGENO}'],
        //     ]
        // ]);

        // // return the pdf output as per the destination setting
        // return $pdf->render();
    }
}
