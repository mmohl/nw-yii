<?php

use app\models\Category;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Menu */

$translate_category = ucfirst(Category::getTranslateCategoryName($category));
$this->title = "Buat $translate_category";
$this->params['breadcrumbs'][] = ['label' => 'Menu', 'url' => ['index', 'category' => $category]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="menu-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'category' => $category
    ]) ?>

</div>