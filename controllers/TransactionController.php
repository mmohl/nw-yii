<?php

namespace app\controllers;

use app\models\Category;
use app\models\Menu;
use app\models\Order;
use app\models\OrderDetail;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\FilePrintConnector;
use Mike42\Escpos\Printer;
use Tightenco\Collect\Support\Collection;
use Yii;
use yii\helpers\Json;
use yii\helpers\VarDumper;

class TransactionController extends \yii\web\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionOrder()
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

        $query = Order::find()->joinWith(['items'])
            ->where(['date' => date('Y-m-d')])
            // ->orderBy($order_field, $order_ascdesc)
            ->limit($limit)
            ->asArray()
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

    public function actionGetOrder($orderCode)
    {
        $order = Order::find()->joinWith(['items'])->where(['order_code' => $orderCode])->asArray()->one();
        $items = Collection::wrap($order['items']);
        $tax = 10;

        $subtotal = $items->reduce(function ($prev, $item) {
            return $prev + ($item['qty'] * $item['price']);
        }, 0);
        $taxTotal = ceil(($subtotal * $tax) / 100);
        $total = $subtotal + $taxTotal;
        $rounded = Order::pembulatan($total) - $total;

        $order['subtotal'] = $subtotal;
        $order['taxTotal'] = $taxTotal;
        $order['rounded'] = $rounded;
        $order['total'] = $total;

        return $this->asJson($order);
    }

    public function actionPayOrder($orderCode, $payment, $rounding)
    {
        $order = Order::find()->where(['order_code' => $orderCode])->one();

        $order->is_paid = 1;
        $order->total_payment = $payment;
        $order->rounding = $rounding;

        $order->save(false);

        return $this->actionPrint($orderCode);
    }

    public function actionPrint($orderCode)
    {
        $order = Order::find()->with(['items'])->where(['order_code' => $orderCode])->one();
        $items = Collection::wrap($order->items);

        $connector = '';
        $os = PHP_OS;

        if ($os == 'Linux') {
            $connector = "/dev/usb/lp1";
        } else if ($os == 'Windows') {
            $connector = '';
        }

        $webroot = Yii::getAlias('@webroot');
        $logo = EscposImage::load("$webroot/images/app/logo_resize.png", false);

        $connector = new FilePrintConnector($connector);
        $printer = new Printer($connector);
        // print logo
        $printer->setJustification(Printer::JUSTIFY_CENTER);
        $printer->bitImage($logo);

        /* Name of shop */
        $printer->setJustification(Printer::JUSTIFY_CENTER);
        // $printer->selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
        // $printer->text(strtoupper('Nandjung Wangi') . "\n");
        // $printer->text(ucwords('Jl cisondari no 11') . "\n");
        // $printer->text(ucwords('pasir jambu ciwidey') . "\n");

        /* Title of receipt */
        $printer->text("\n");
        $printer->setEmphasis(false);

        // order detail
        $printer->selectPrintMode();
        $printer->text("Pemesan" . str_pad($order->ordered_by, 32 - strlen('Pemesan'), ' ', STR_PAD_LEFT) . "\n");
        $printer->text("Kode Pesanan" . str_pad($order->order_code, 32 - strlen('Kode Pesanan'), ' ', STR_PAD_LEFT) . "\n");
        $printer->feed();

        /* Items */
        foreach ($order->items as $item) {
            $price = number_format($item->price, 0, ',', '.');
            $name = strtoupper($item->name);
            $printer->text("{$item->qty}    {$name}" . str_pad($price, 32 - strlen("{$item->qty}    {$name}"), ' ', STR_PAD_LEFT));
        }

        $printer->text("================================\n");

        /* Result */
        $printer->setEmphasis(true);
        $printer->setEmphasis(false);

        $tax = 10;
        $subtotal = $items->reduce(function ($prev, $item) {
            return $prev + ($item->qty * $item->price);
        }, 0);
        $taxTotal = ceil(($subtotal * $tax) / 100);
        $total = $subtotal + $taxTotal;
        $rounded = Order::pembulatan($total) - $total;
        $totalItems = $items->reduce(function ($prev, $item) {
            return $prev += $item->qty;
        }, 0);
        $printer->text("Items: $totalItems" . str_pad(number_format($subtotal, 0, ',', '.'), 32 - strlen("Items: $totalItems"), ' ', STR_PAD_LEFT) . "\n");
        $printer->text('Tax 10%' . str_pad(number_format($taxTotal, 0, ',', '.'), 32 - strlen('Tax 10%'), ' ', STR_PAD_LEFT) . "\n");
        $printer->text('Before Rounding' . str_pad(number_format($total, 0, ',', '.'), 32 - strlen('Before Rounding'), ' ', STR_PAD_LEFT) . "\n");
        $printer->text('Rounding' . str_pad($rounded > 0 ? "+" . number_format($rounded, 0, ',', '.') : number_format($rounded, 0, ',', '.'), 32 - strlen('Rounding'), ' ', STR_PAD_LEFT) . "\n");
        $printer->text('Total' . str_pad(number_format($total + $rounded, 0, ',', '.'), 32 - strlen('Total'), ' ', STR_PAD_LEFT) . "\n");

        /* Footer */
        $printer->text("================================\n");
        $printer->feed();
        $printer->setJustification(Printer::JUSTIFY_CENTER);
        $printer->text("Terima Kasih\n");
        $printer->text("Atas Kunjungan Anda\n");
        $printer->feed(2);
        $printer->pulse();

        $printer->close();

        return $this->asJson(['print' => 1]);
    }
}
