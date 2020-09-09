<?php

use app\models\Category;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\MenuSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$translate_category = ucfirst(Category::getTranslateCategoryName($category));
$this->title = "Menu $translate_category";
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="menu-index">
    <div class="card">
        <div class="card-header d-flex">
            <div class="toolbar ml-auto">
                <?= Html::a("<i class='fas fa-plus'></i>&nbsp;$translate_category", Url::to(['create', 'category' => $category]), ['class' => 'btn btn-success']) ?>
            </div>
        </div>
        <div class="card-body">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],

                    // 'id',
                    'name',
                    // 'category',
                    'price:currency',
                    // 'created_at',
                    //'updated_at',

                    [
                        'class' => 'yii\grid\ActionColumn',
                        'buttons' => [
                            'update' => function ($url, $model, $key) {
                                return Html::a(Html::tag('span', '', ['class' => 'fas fa-pencil-alt']), Url::to(['update', 'id' => $model->id, 'category' => $model->category]));
                            },
                            'view' => function ($url, $model, $key) {
                                return Html::a(Html::tag('span', '', ['class' => 'fas fa-eye']), Url::to(['view', 'id' => $model->id, 'category' => $model->category]));
                            }
                        ]
                    ],
                ],
            ]); ?>
        </div>
    </div>


</div>