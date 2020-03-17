<?php

namespace app\models;

use Tightenco\Collect\Support\Collection;
use Yii;
use yii\helpers\Html;

/**
 * This is the model class for table "menus".
 *
 * @property int $id
 * @property string $name
 * @property string $category
 * @property int $price
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property array|null $types
 */
class Menu extends RootModel
{
    public $imageFile;
    public $types;

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
            [['created_at', 'updated_at', 'types'], 'safe'],
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
            'types' => 'Jenis',
            'imageFile' => 'Gambar'
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

    public function getTags()
    {
        return $this->hasMany(MenuTags::class, ['menu_id' => 'id']);
    }

    public function renderTagsAsHtml()
    {
        $tags = Collection::wrap($this->tags)->map(function ($tag) {
            return Html::tag('span', $tag->name, ['class' => 'label label-primary']);
        })->implode('&nbsp');

        return Html::tag('p', $tags);
    }

    public function renderTagsAsSelectData()
    {
        $tags = Collection::wrap($this->tags)->pluck('name')->toArray();

        return $tags;
    }

    public function loadTags()
    {
        $this->types = $this->renderTagsAsSelectData();
    }
}
