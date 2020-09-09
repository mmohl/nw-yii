<?php

use app\models\Category;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Menu */

$translate_category = ucfirst(Category::getTranslateCategoryName($category));
$this->title = "Buat $translate_category";
$this->params['breadcrumbs'][] = ['label' => "Menu $translate_category", 'url' => ['index', 'category' => $category]];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col-lg-8 offset-lg-2">
        <div class="card">
            <div class="card-body">
                <?= $this->render('_form', [
                    'model' => $model,
                    'category' => $category
                ]) ?>
            </div>
        </div>
    </div>
</div>