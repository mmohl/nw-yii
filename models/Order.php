<?php

namespace app\models;

use Carbon\Carbon;
use Tightenco\Collect\Support\Collection;
use Yii;
use yii\db\Query;

/**
 * This is the model class for table "orders".
 *
 * @property int $id
 * @property string $date
 * @property string $ordered_by
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property int $is_paid
 * @property int $total_payment
 * @property int $rounding
 */
class Order extends RootModel
{
    private $total;

    const TOTAL_SALES_TAG_DAY = 'day';
    const TOTAL_SALES_TAG_WEEK = 'week';
    const TOTAL_SALES_TAG_MONTH = 'month';
    const TOTAL_SALES_TAG_YEAR = 'year';

    public function setTotal($val)
    {
        $this->total = $val;
    }

    public function getTotal($val)
    {
        return $this->total;
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'orders';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['date', 'ordered_by'], 'required'],
            [['id', 'total_payment', 'is_paid', 'rounding'], 'integer'],
            [['date', 'created_at', 'updated_at', 'order_code', 'total'], 'safe'],
            [['ordered_by'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'date' => 'Date',
            'total_payment' => 'Total Bayar',
            'is_paid' => 'Dibayar',
            'rounding' => 'Rounding',
            'ordered_by' => 'Ordered By',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'total' => 'Total',
            'tax' => 'Pajak',
            'beforeRounding' => 'Sebelum Rounding',
            'changes' => 'Kembali'
        ];
    }

    public function getItems()
    {
        return $this->hasMany(OrderDetail::class, ['order_id' => 'id']);
    }

    public static function makeOrderCode()
    {
        return date('d/m/y') . "-" . str_pad((Order::find()->where(['date' => date('Y-m-d')])->count() + 1), 3, '0', STR_PAD_LEFT);
    }

    public static function pembulatan($nominal)
    {
        $ratusan = substr($nominal, -3);
        if ($ratusan < 500)
            $akhir = $nominal - $ratusan;
        else
            $akhir = $nominal + (1000 - $ratusan);

        return $akhir;
    }

    public function getOrderAmount()
    {
        $items = Collection::wrap($this->items);

        return $items->reduce(fn($prev, $item) => $prev += ($item->qty * $item->price) , 0);
    }

    public function getOrderTax() 
    {
        return floor($this->getOrderAmount() * 0.1);
    }

    public static function getTotalSales($tag) {
        $query = Order::find();
        $firstDate = '';
        $lastDate = '';
        $format = 'Y-m-d';

        if ($tag == Order::TOTAL_SALES_TAG_DAY) {
            $firstDate = Carbon::now()->format($format);
            $lastDate = $firstDate;
        } else if ($tag == Order::TOTAL_SALES_TAG_WEEK) {
            $firstDate = Carbon::now()->startOfWeek()->format($format);
            $lastDate = Carbon::now()->endOfWeek()->format($format);
        } else if  ($tag == Order::TOTAL_SALES_TAG_MONTH) {
            $firstDate = Carbon::now()->firstOfMonth()->format($format);
            $lastDate = Carbon::now()->endOfMonth()->format($format);
        } else if ($tag == Order::TOTAL_SALES_TAG_YEAR) {
            $firstDate = Carbon::now()->firstOfYear()->format($format);
            $lastDate = Carbon::now()->endOfYear()->format($format);
        }

        $query = $query->where(['BETWEEN', 'date', $firstDate, $lastDate]);

        return $query->count();
    }

    public static function getTotalOmzet($tag) {
        $query = Order::find();
        $firstDate = '';
        $lastDate = '';
        $format = 'Y-m-d';

        if ($tag == Order::TOTAL_SALES_TAG_DAY) {
            $firstDate = Carbon::now()->format($format);
            $lastDate = $firstDate;
        } else if ($tag == Order::TOTAL_SALES_TAG_WEEK) {
            $firstDate = Carbon::now()->startOfWeek()->format($format);
            $lastDate = Carbon::now()->endOfWeek()->format($format);
        } else if  ($tag == Order::TOTAL_SALES_TAG_MONTH) {
            $firstDate = Carbon::now()->firstOfMonth()->format($format);
            $lastDate = Carbon::now()->endOfMonth()->format($format);
        } else if ($tag == Order::TOTAL_SALES_TAG_YEAR) {
            $firstDate = Carbon::now()->firstOfYear()->format($format);
            $lastDate = Carbon::now()->endOfYear()->format($format);
        }

        $query = $query->where(['BETWEEN', 'date', $firstDate, $lastDate]);

        $data = Collection::wrap($query->all());

        $data = $data->reduce(function($prev, $order){
            $prev += $order->getOrderAmount();
            return $prev;
        }, 0);

        return $data;
    }

    public static function getChartDatasets() {
        $format = 'Y-m-d';
        $firstDate = Carbon::now()->startOfWeek()->format($format);
        $lastDate = Carbon::now()->endOfWeek()->format($format);
        $items = (new Query())->select(['name','COUNT(name) as total'])
        ->from('orders')
        ->join('JOIN', 'order_details', 'order_details.order_id = orders.id')
        ->where(['BETWEEN', 'date', $firstDate, $lastDate])
        ->groupBy('order_details.name')
        ->all();

        $items = Collection::wrap($items);

        $chart = Collection::wrap([]);
        $labels = $items->pluck('name')->map(function($name){
            $name = strtolower($name);
            return ucfirst($name); 
        });

        $dataset = ['data' => $items->pluck('total'), 'label' => 'Item', 'fill' => false];
        $chart->put('datasets', [$dataset]);
        $chart->put('labels', $labels);

        return $chart;
    }
}
