<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "order_details".
 *
 * @property int $id
 * @property int $order_id
 * @property string $name
 * @property int $qty
 * @property int $price
 * @property string|null $created_at
 * @property string|null $updated_at
 */
class OrderDetail extends RootModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'order_details';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['order_id', 'name', 'qty', 'price'], 'required'],
            [['id', 'order_id', 'qty', 'price'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'order_id' => 'Order ID',
            'name' => 'Name',
            'qty' => 'Qty',
            'price' => 'Price',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function order()
    {
        return $this->hasOne(Order::class, ['id' => 'order_id']);
    }
}
