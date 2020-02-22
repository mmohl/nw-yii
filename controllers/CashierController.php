<?php

namespace app\controllers;

use app\models\Category;
use app\models\Menu;
use app\models\Order;
use app\models\OrderDetail;
use Yii;
use yii\helpers\Json;

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

    public function actionMakeOrder()
    {
        $payload = Yii::$app->request->getRawBody();
        $payload = Json::decode($payload);

        $orderedBy = $payload['orderedBy'];
        $items = $payload['items'];

        $order = new Order();
        $order->date = date('Y-m-d');
        $order->ordered_by = $orderedBy;
        $order->order_code = Order::makeOrderCode();

        if ($order->save()) {
            foreach ($items as $item) {
                $orderedItem = new OrderDetail();
                $menuItem = Menu::findOne(['id' => $item['id']]);

                $orderedItem->order_id = $order->id;
                $orderedItem->name = $menuItem->name;
                $orderedItem->price = $menuItem->price;
                $orderedItem->qty = $item['qty'];

                $orderedItem->save();
            }

            return $this->asJson(['message' => 'berhasil memebuat pesanan']);
        }

        Yii::$app->response->statusCode = 400;
        return $this->asJson(['message' => 'gagal membuat pesanan']);
    }

    public function actionCashierDatatable()
    {
        $search = Yii::$app->request->getQueryParam('search'); //$_POST['search']['value']; // Ambil data yang di ketik user pada textbox pencarian
        $limit = Yii::$app->request->getQueryParam('length'); // Ambil data limit per page
        $start = Yii::$app->request->getQueryParam('start'); // Ambil data sta
        $order = Yii::$app->request->getQueryParam('order');
        // echo '<pre>';
        // var_dump($limit, $start, $order);
        // die;
        $order_field = $order[0]['column']; // Untuk mengambil nama field yg menjadi acuan untuk sorting
        $order_ascdesc = $order[0]['dir']; // Untuk menentukan order by "ASC" atau "DESC"
        $draw = Yii::$app->request->getQueryParam('draw');
        $page = 1;
        $offset = ($page - 1) * $limit;
        $totalData = Order::find()->where(['date' => date('Y-m-d')])->count();

        $query = Order::find()->with('items')
            ->where(['date' => date('Y-m-d')])
            // ->orderBy($order_field, $order_ascdesc)
            ->limit($limit)
            ->offset($offset);

        $data = $query->all();

        $response = [
            'draw' => intval($draw),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => count($data),
            "data" => $data
        ];

        return $this->asJson($response);
    }

    public function actionCashier()
    {
        return $this->render('_cashier');
    }

    public function actionGetOrders()
    { }
}
