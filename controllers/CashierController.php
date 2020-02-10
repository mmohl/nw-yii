<?php

namespace app\controllers;

class CashierController extends \yii\web\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionCashier()
    {
        return $this->render('_cashier');
    }

    public function actionGetOrders()
    { }
}
