<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "categories".
 *
 * @property int $id
 * @property string $name
 * @property string|null $created_at
 * @property string|null $updated_at
 */
class Category extends RootModel
{
    const CATEGORY_PACKAGE = 'package';
    const CATEGORY_BEVERAGE = 'beverage';
    const CATEGORY_OTHER = 'other';
    const CATEGORY_FOOD = 'food';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'categories';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
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
            'name' => 'Name',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    // public function behaviors()
    // {
    //     return [
    //         [
    //             'class' => TimestampBehavior::className(),
    //             'createdAtAttribute' => 'created_at',
    //             'updatedAtAttribute' => 'updated_at',
    //             'value' => new Expression('NOW()'),
    //         ],
    //     ];
    // }

    public static function getTranslateCategoryName($category)
    {
        switch ($category) {
            case Category::CATEGORY_PACKAGE:
                return 'paket';
            case Category::CATEGORY_FOOD:
                return 'makanan';
            case Category::CATEGORY_BEVERAGE:
                return 'minuman';
            case Category::CATEGORY_OTHER:
                return 'lain - lain';
            default:
                return 'kategori salah';
        }
    }
}
