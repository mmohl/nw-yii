<?php

use app\models\Category;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\money\MaskMoney;

/* @var $this yii\web\View */
/* @var $model app\models\Menu */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="menu-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true, 'placeholder' => 'Masukan nama', 'value' => $model->isNewRecord ?  '' : $model->name]) ?>

    <?= $form->field($model, 'category')->dropDownList([
        '' => 'Pilih kategori',
        Category::CATEGORY_FOOD => ucfirst('makanan'),
        Category::CATEGORY_BEVERAGE => ucfirst('minuman'),
        Category::CATEGORY_SNACK => ucfirst('cemilan'),
        Category::CATEGORY_PACKAGE => ucfirst('paket')
    ], ['value' => $category, 'readonly' => true]) ?>

    <?= $form->field($model, 'price')->widget(MaskMoney::class, [
        'value' => $model->isNewRecord ?  0 : $model->price,
        'pluginOptions' => [
            'prefix' => 'Rp ',
            'precision' => 0
        ],
    ]) ?>

    <?= $form->field($model, 'imageFile')->fileInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>