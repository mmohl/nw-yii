<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "menu_variants".
 *
 * @property int $id
 * @property int|null $menu_id
 * @property int|null $parent_id
 * @property int|null $level
 * @property int|null $price
 * @property string|null $label
 * @property int|null $is_enabled
 * @property string|null $updated_at
 * @property string $created_at
 */
class MenuVariant extends RootModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'menu_variants';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['menu_id', 'parent_id', 'level', 'price', 'is_enabled'], 'integer'],
            [['updated_at', 'created_at'], 'safe'],
            [['label'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'menu_id' => 'Menu ID',
            'parent_id' => 'Parent ID',
            'level' => 'Level',
            'price' => 'Price',
            'label' => 'Label',
            'is_enabled' => 'Is Enabled',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
        ];
    }
}
