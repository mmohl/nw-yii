<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "menu_tags".
 *
 * @property int $id
 * @property string|null $name
 * @property int|null $menu_id
 * @property string|null $updated_at
 * @property string $created_at
 *
 * @property Menus $menu
 */
class MenuTags extends \app\models\RootModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'menu_tags';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['menu_id'], 'integer'],
            [['updated_at', 'created_at'], 'safe'],
            [['name'], 'string', 'max' => 255],
            [['menu_id'], 'exist', 'skipOnError' => true, 'targetClass' => Menu::class, 'targetAttribute' => ['menu_id' => 'id']],
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
            'menu_id' => 'Menu ID',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
        ];
    }

    /**
     * Gets query for [[Menu]].
     *
     * @return \yii\db\ActiveQuery|MenusQuery
     */
    public function getMenu()
    {
        return $this->hasOne(Menu::class, ['id' => 'menu_id']);
    }

    /**
     * {@inheritdoc}
     * @return MenuTagsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new MenuTagsQuery(get_called_class());
    }
}
