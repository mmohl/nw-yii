<?php

use app\models\Category;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\money\MaskMoney;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model app\models\Menu */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="menu-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true, 'placeholder' => 'Masukan nama', 'value' => $model->isNewRecord ?  '' : $model->name]) ?>


    <?= $form->field($model, 'price')->widget(MaskMoney::class, [
        'value' => $model->isNewRecord ?  0 : $model->price,
        'pluginOptions' => [
            'prefix' => 'Rp ',
            'precision' => 0
        ],
    ]) ?>

    <?= $form->field($model, 'imageFile')->fileInput() ?>

    <?= $form->field($model, 'types')->widget(Select2::class, [
        'pluginOptions' => [
            'tags' => true,
            'tokenSeparators' => [','],
            'maximumInputLength' => 10,
            'multiple' => true
        ]
    ]) ?>

    <?= $form->field($model, 'category')->hiddenInput(['value' => $category])->label('') ?>
    <div class="form-group">
        <?= Html::submitButton('Simpan', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>