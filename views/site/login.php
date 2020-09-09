<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \mdm\admin\models\form\Login */

$this->title = 'Halaman Masuk';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="splash-container">
    <div class="card ">
        <div class="card-header text-center">
            <a href="<?= Url::base() ?>">
                <img class="logo-img" src="<?= Url::base() ?>/images/app/logo_resize.png" alt="logo">
            </a>
        </div>
        <div class="card-body">
            <?= Html::beginForm(Url::to(['site/login']), 'post') ?>
            <div class="form-group">
                <!-- <input class="" id="username" type="text" placeholder="Username" autocomplete="off"> -->
                <?= Html::activeInput('text', $model, 'username', ['class' => 'form-control form-control-lg', 'placeholder' => 'Nama Pengguna', 'autocomplete' => 'off']) ?>
            </div>
            <div class="form-group">
                <!-- <input class="" id="password" type="password" placeholder="Password"> -->
                <?= Html::activeInput('password', $model, 'password', ['class' => 'form-control form-control-lg', 'placeholder' => 'Kata Sandi']) ?>
            </div>
            <button type="submit" class="btn btn-primary btn-lg btn-block">Masuk</button>
            <?= Html::endForm() ?>
        </div>
    </div>
</div>