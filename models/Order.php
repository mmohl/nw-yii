<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "orders".
 *
 * @property int $id
 * @property string $date
 * @property string $ordered_by
 * @property string|null $created_at
 * @property string|null $updated_at
 */
class Order extends RootModel
{
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
            [['id'], 'integer'],
            [['date', 'created_at', 'updated_at', 'order_code'], 'safe'],
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
            'ordered_by' => 'Ordered By',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
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
}
