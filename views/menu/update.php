<?php

use yii\helpers\Html;
use app\models\Category;

/* @var $this yii\web\View */
/* @var $model app\models\Menu */

$this->title = 'Ubah Menu: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => "Menu " . ucfirst(Category::getTranslateCategoryName($category)), 'url' => ['index', 'category' => $category]];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Ubah';
?>
<div class="menu-update">
    <?= $this->render('_form', [
        'model' => $model,
        'category' => $model->category
    ]) ?>

</div>