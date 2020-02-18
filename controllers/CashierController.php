<?php

namespace app\controllers;

use app\models\Category;

class CashierController extends \yii\web\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionOrderIndex()
    {
        $this->layout = 'order';

        $categories = Category::find()->all();

        return $this->render('_order', ['categories' => $categories]);
    }

    public function actionCashier()
    {
        return $this->render('_cashier');
    }

    public function actionGetOrders()
    { }
}
