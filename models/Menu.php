<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "menus".
 *
 * @property int $id
 * @property string $name
 * @property string $category
 * @property int $price
 * @property string|null $created_at
 * @property string|null $updated_at
 */
class Menu extends RootModel
{
    public $imageFile;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'menus';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'category', 'price'], 'required'],
            [['price'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['name', 'category', 'img'], 'string', 'max' => 255],
            [['imageFile'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Nama',
            'category' => 'Kategori',
            'price' => 'Harga',
            'img' => 'Gambar',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function upload()
    {
        if ($this->validate()) {
            $path = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'web' . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . $this->category;
            if (!file_exists($path)) {
                mkdir($path);
            }

            $this->imageFile->saveAs("@webroot/images/{$this->category}/" . $this->imageFile->baseName . '.' . $this->imageFile->extension);
            return true;
        } else {
            return false;
        }
    }
}
