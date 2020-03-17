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

    <?= Html::img($model->img != '' ? "@web/images/{$model->category}/{$model->img}" : "@web/images/app/default.jpg", ['class' => 'img-responsive img-thumbnail', 'style' => 'width: 300px; height: 300px; object-fit: cover; margin-bottom: 1em;']) ?>

    <?= $model->renderTagsAsHtml() ?>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'name',
            [
                'label'  => 'Kategori',
                'value'  => $translate_category
            ],
            'price:currency'
        ],
    ]) ?>

</div>