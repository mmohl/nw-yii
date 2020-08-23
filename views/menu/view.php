<?php

use app\models\Category;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Menu */

$translate_category = ucfirst(Category::getTranslateCategoryName($model->category));
$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Menu', 'url' => ['index', 'category' => $model->category]];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="menu-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Ubah', ['update', 'id' => $model->id, 'category' => $model->category], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Hapus', ['delete', 'id' => $model->id, 'category' => $model->category], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => "Apakah anda yakin akan menghapus menu $translate_category \"{$model->name}\" ini?",
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <div class="row">
        <div class="col-lg-3">
            <?= Html::img($model->img != '' ? "@web/images/{$model->img}" : "@web/images/app/default.jpg", ['class' => 'img-responsive img-thumbnail', 'style' => 'width: 300px; height: 300px; object-fit: cover; margin-bottom: 1em;']) ?>
        </div>
        <div class="col-lg-9" style="border: 1px #ddd solid; height: 300px; padding: 5px; border-radius: 5px;">
            <?= $model->description ?>
        </div>
    </div>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'name',
            [
                'label'  => 'Kategori',
                'value'  => $translate_category
            ],
            'price:currency',
            [
                'label' => 'Jenis',
                'format' => 'raw',
                'value' => $model->renderTagsAsHtml()
            ]
        ],
    ]) ?>

</div>