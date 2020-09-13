<?php

namespace app\controllers;

use app\models\Category;
use app\models\Menu;
use app\models\Order;
use app\models\OrderDetail;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\FilePrintConnector;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\Printer;
use Tightenco\Collect\Support\Collection;
use Yii;
use yii\helpers\Json;

class TransactionController extends \yii\web\Controller
{
    public $layout = 'concept/main';

    public function beforeAction($action)
    {
        $this->enableCsrfValidation = false;

        return parent::beforeAction($action);
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionOrder()
    {
        $this->layout = 'order';

        $categories = Category::find()->orderBy(['id' => SORT_DESC])->all();

        return $this->render('_order', ['categories' => $categories]);
    }

    public function actionOrderModify($id)
    {
        $order = Order::findOne($id);
        $payload = Yii::$app->request->getRawBody();
        $payload = Json::decode($payload);

        $order->is_ignored = $payload['is_ignored'];

        if ($order->save()) {
            return $this->asJson(['message' => 'berhasil mengubah data']);
        }

        Yii::$app->response->statusCode = 400;
        return $this->asJson(['message' => 'gagal membuat pesanan']);
    }

    public function actionMakeOrder()
    {
        $this->enableCsrfValidation = false;

        $payload = Yii::$app->request->getRawBody();
        $payload = Json::decode($payload);

        $orderedBy = $payload['orderedBy'];
        $items = $payload['items'];
        $tableNumber = $payload['tableNumber'];

        $order = new Order();
        $order->date = date('Y-m-d');
        $order->ordered_by = ucfirst($orderedBy);
        $order->order_code = Order::makeOrderCode();
        $order->table_number = $tableNumber;
        $order->is_ignored = $payload['ignoreTax'] ? 1 : 0;

        if ($order->save()) {
            foreach ($items as $item) {
                $orderedItem = new OrderDetail();
                $menuItem = Menu::findOne(['id' => $item['id']]);

                $orderedItem->order_id = $order->id;
                $orderedItem->name = $menuItem->name;
                $orderedItem->price = $menuItem->price;
                $orderedItem->qty = $item['qty'];
                $orderedItem->menu_id = $menuItem->id;

                $orderedItem->save();
            }

            $items = collect(OrderDetail::find()->where(['order_id' => $order->id])->all());

            $this->actionPrintMenu($order->order_code, $items);
            $this->actionPrint($order->order_code);

            return $this->asJson(['message' => 'berhasil membuat pesanan']);
        }

        Yii::$app->response->statusCode = 400;
        return $this->asJson(['message' => 'gagal membuat pesanan']);
    }

    public function actionAddNewItemsOrder()
    {
        $payload = Yii::$app->request->getRawBody();
        $payload = Json::decode($payload);

        $orderId = $payload['orderId'];
        $items = $payload['items'];
        $order = Order::find()->where(['id' => $orderId])->one();

        foreach ($items as $item) {
            $existingItem = OrderDetail::find()->where(['order_id' => $orderId, 'menu_id' => $item['id']])->one();

            if ($existingItem) {
                $existingItem->qty += $item['qty'];
                $existingItem->save(false);
            } else {
                $orderedItem = new OrderDetail();
                $menuItem = Menu::findOne(['id' => $item['id']]);

                $orderedItem->order_id = $orderId;
                $orderedItem->name = $menuItem->name;
                $orderedItem->price = $menuItem->price;
                $orderedItem->qty = $item['qty'];
                $orderedItem->menu_id = $menuItem->id;

                $orderedItem->save(false);
            }
        }

        if ($payload['ignoreTax']) $order->is_ignored = $payload['ignoreTax'] ? 1 : 0;

        $ids = collect($items)->pluck('id')->toArray();
        $newItems = collect(OrderDetail::find()->where(['order_id' => $orderId, 'menu_id' => $ids])->all());

        foreach ($items as $item) {
            foreach ($newItems as $index => $newItem) {
                if ($newItem->menu_id == $item['id']) {
                    $newItem->qty = $item['qty'];
                    $newItems->put($index, $newItem);
                }
            }
        }

        // $this->actionPrintMenu($order->order_code, $newItems, Order::ORDER_ADDITIONAL);
        // $this->actionPrint($order->order_code);
        Yii::$app->response->statusCode = 200;
        return $this->asJson(['message' => 'berhasil menambahkan pesanan']);
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
            ->orderBy(['is_paid' => SORT_ASC, 'id' => SORT_ASC])
            ->offset($offset);

        if ($search['value'] != '') $query->where(['LIKE', 'ordered_by', $search['value']]);

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
        $taxTotal = floor(($subtotal * $tax) / 100);
        if ($order['is_ignored'] == '1') $taxTotal = 0;
        $total = $subtotal + $taxTotal;
        $rounded = Order::pembulatan($total) - $total;

        $order['subtotal'] = $subtotal;
        $order['taxTotal'] = $taxTotal;
        $order['rounded'] = $rounded;
        $order['total'] = $total;

        return $this->asJson($order);
    }

    public function actionPayOrder($orderCode, $payment, $rounding, $isIgnored)
    {
        $order = Order::find()->where(['order_code' => $orderCode])->one();

        $order->is_paid = 1;
        $order->total_payment = $payment;
        $order->rounding = $rounding;
        $order->is_ignored = $isIgnored == 'true' ? 1 : 0;

        $order->save(false);

        return $this->asJson(['message' => 'success pay the order']);
        // return $this->actionPrint($orderCode);
    }

    public function actionPrintMenu($orderCode, $items, $type = Order::ORDER_NEW)
    {
        $order = Order::find()->where(['order_code' => $orderCode])->one();

        $connector = '';
        $os = PHP_OS;

        if ($os == 'Linux') {
            $connector = new FilePrintConnector("/dev/usb/lp1");
        } else if ($os == 'WINNT') {
            $connector = new WindowsPrintConnector("POS-58");;
        }

        $orderType = ucfirst($type == Order::ORDER_NEW ? 'baru' : 'tambahan');

        $webroot = Yii::getAlias('@webroot');
        $logo = EscposImage::load("$webroot/images/app/logo_resize.png", false);

        $printer = new Printer($connector);

        /* Title of receipt */
        $printer->text("\n");
        $printer->feed(5);
        $printer->setEmphasis(false);

        // order detail
        $printer->selectPrintMode();
        $printer->text("Order" . str_pad($orderType, 32 - strlen('Order'), ' ', STR_PAD_LEFT) . "\n");
        $printer->text("Pemesan" . str_pad($order->ordered_by, 32 - strlen('Pemesan'), ' ', STR_PAD_LEFT) . "\n");
        $printer->text("No. Meja" . str_pad($order->table_number, 32 - strlen('No. Meja'), ' ', STR_PAD_LEFT) . "\n");
        $printer->text("Kode Pesanan" . str_pad($order->order_code, 32 - strlen('Kode Pesanan'), ' ', STR_PAD_LEFT) . "\n");
        $printer->feed();

        /* Items */
        foreach ($items as $item) {
            $name = $item->name . "\n";
            $printer->text("[{$item->qty}] {$name}");
        }

        $printer->feed(3);

        $printer->close();

        return $this->asJson(['print' => 1]);
    }

    public function actionPrint($orderCode)
    {
        $order = Order::find()->with(['items'])->where(['order_code' => $orderCode])->one();
        $items = Collection::wrap($order->items);

        $connector = '';
        $os = PHP_OS;

        if ($os == 'Linux') {
            $connector = new FilePrintConnector("/dev/usb/lp1");
        } else if ($os == 'WINNT') {
            $connector = new WindowsPrintConnector("POS-58");;
        }

        $webroot = Yii::getAlias('@webroot');
        $logo = EscposImage::load("$webroot/images/app/logo_resize.png", false);

        $printer = new Printer($connector);

        for ($repeat = 0; $repeat < 1; $repeat++) {
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
            $printer->text("No. Meja" . str_pad($order->table_number, 32 - strlen('No. Meja'), ' ', STR_PAD_LEFT) . "\n");
            $printer->text("Kode Pesanan" . str_pad($order->order_code, 32 - strlen('Kode Pesanan'), ' ', STR_PAD_LEFT) . "\n");
            $printer->feed();

            /* Items */
            foreach ($order->items as $item) {
                $price = number_format($item->price * $item->qty, 0, ',', '.');
                $name = strlen($item->name) > 15 ? substr(strtoupper($item->name), 0, 15) . '...' : $item->name;
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
            // $printer->text('Before Rounding' . str_pad(number_format($total, 0, ',', '.'), 32 - strlen('Before Rounding'), ' ', STR_PAD_LEFT) . "\n");
            // $printer->text('Rounding' . str_pad($rounded > 0 ? "+" . number_format($rounded, 0, ',', '.') : number_format($rounded, 0, ',', '.'), 32 - strlen('Rounding'), ' ', STR_PAD_LEFT) . "\n");
            $printer->text('Total' . str_pad(number_format($total + $rounded, 0, ',', '.'), 32 - strlen('Total'), ' ', STR_PAD_LEFT) . "\n");

            /* Footer */
            $printer->text("================================\n");
            $printer->feed();
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->text("Terima Kasih\n");
            $printer->text("Atas Kunjungan Anda\n");
            $printer->feed(1);
            $printer->text("Jl. Cisondari no. 11 Pasir Jambu Ciwidey \n");
            $printer->text('Kontak: 0812 1024 5910');
            $printer->feed(3);
            // $printer->pulse();
        }

        $printer->close();

        return $this->asJson(['print' => 1]);
    }

    public function actionDashboard()
    {
        $holder = [];

        $holder['totalSales'] = [
            ['label' => 'day', 'value' => Order::getTotalSales(Order::TOTAL_SALES_TAG_DAY)],
            ['label' => 'week', 'value' => Order::getTotalSales(Order::TOTAL_SALES_TAG_WEEK)],
            ['label' => 'month', 'value' => Order::getTotalSales(Order::TOTAL_SALES_TAG_MONTH)],
            ['label' => 'year', 'value' => Order::getTotalSales(Order::TOTAL_SALES_TAG_YEAR)],
        ];

        $holder['totalOmzet'] = [
            ['label' => 'day', 'value' => Order::getTotalOmzet(Order::TOTAL_SALES_TAG_DAY)],
            ['label' => 'week', 'value' => Order::getTotalOmzet(Order::TOTAL_SALES_TAG_WEEK)],
            ['label' => 'month', 'value' => Order::getTotalOmzet(Order::TOTAL_SALES_TAG_MONTH)],
            ['label' => 'year', 'value' => Order::getTotalOmzet(Order::TOTAL_SALES_TAG_YEAR)],
        ];

        $holder['chartItems'] = Order::getChartDatasets();


        return $this->asJson($holder);
    }

    public function actionUnfinishedOrderToday()
    {
        $orders = Order::find()->where(['date' => date('Y-m-d'), 'is_paid' => Order::NOT_PAID])->orderBy(['id' => SORT_ASC])->asArray()->all();

        return $this->asJson($orders);
    }
}
