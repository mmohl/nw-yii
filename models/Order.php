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
class Order extends \yii\db\ActiveRecord
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
            [['id', 'date', 'ordered_by'], 'required'],
            [['id'], 'integer'],
            [['date', 'created_at', 'updated_at'], 'safe'],
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
}
