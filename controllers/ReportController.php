<?php

namespace app\controllers;

use app\models\Order;
use app\models\OrderSearch;
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
}
