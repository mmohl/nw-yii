<?php

namespace app\models;

use Tightenco\Collect\Support\Collection;
use Yii;

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
        return date('ymd') . "-" . str_pad((Order::find()->where(['date' => date('Y-m-d')])->count() + 1), 3, '0', STR_PAD_LEFT);
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
}
