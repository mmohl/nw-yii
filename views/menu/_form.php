<?php

use app\models\Category;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\money\MaskMoney;
use kartik\select2\Select2;

$this->registerCssFile("@web/js/tagify/dist/tagify.css");
$this->registerJsFile('@web/js/tagify/dist/tagify.min.js');
$this->registerJsFile('@web/libraries/lodash/dist/lodash.min.js');
$this->registerJsFile('@web/js/form/variant.js', ['depends' => [\yii\web\JqueryAsset::class]]);

$this->registerCss("
    .tagify {
        height: 40px;
    }

    .sub {
        margin-top: 1em;
    }
");
?>

<div class="pills-regular">
    <ul class="nav nav-pills mb-1" id="pills-tab" role="tablist">
        <li class="nav-item">
            <a class="nav-link active show" id="pills-home-tab" data-toggle="pill" href="#pills-home" role="tab" aria-controls="home" aria-selected="true">Form</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="pills-profile-tab" data-toggle="pill" href="#pills-profile" role="tab" aria-controls="profile" aria-selected="false">Varian</a>
        </li>
    </ul>
    <div class="tab-content" id="pills-tabContent">
        <div class="tab-pane fade active show" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
            <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
            <?= $form->field($model, 'name')->textInput(['maxlength' => true, 'placeholder' => 'Masukan nama', 'value' => $model->isNewRecord ?  'ayam' : $model->name, 'autocomplete' => 'off']) ?>

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

            <?= $form->field($model, 'description')->textarea(['rows' => 4]) ?>

            <?= $form->field($model, 'category')->hiddenInput(['value' => $category])->label('') ?>
            <div class="form-group">
                <?= Html::submitButton('Simpan', ['class' => 'btn btn-success']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
        <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
            <div class="row">
                <div class="col-lg-8">
                    <input type="text" id="variant0" class="tag-data" data-level="0">
                </div>
                <div class="col-lg-4">
                    <div class="btn-group mr-2" role="group" aria-label="First group">
                        <button data-index="0" type="button" data-toggle="tooltip" data-placement="top" title data-original-title="Tambah Sub Varian" type="button" class="btn btn-primary variant-add-sub"><span class="fas fa-plus"></span></button>
                        <button type="button" data-toggle="tooltip" data-placement="top" title data-original-title="Hapus semua varian" type="button" class="btn btn-warning"><span class="fas fa-eraser"></span></button>
                    </div>
                </div>
            </div>
            <div style="margin-top: 3em;"></div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <table class="table" id="table-variants">
                                <thead>
                                    <tr>
                                        <th>Nama</th>
                                        <th>Harga</th>
                                        <th>Aktif</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>