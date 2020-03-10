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


<div class="limiter">
    <div class="container-login100">
        <div class="wrap-login100">
            <div class="login100-pic js-tilt" data-tilt>
                <img src="<?= Url::base() ?>/images/app/logo_resize.png" alt="IMG">
            </div>

            <!-- <form class="login100-form validate-form" id="login-form" method="POST"> -->
            <?= Html::beginForm(Url::to(['site/login']), 'post', ['class' => 'login100-form validate-form', 'id' => 'login-form']) ?>
            <span class="login100-form-title">
                <h1><?= Html::encode($this->title) ?></h1>
            </span>
            <?php if (Yii::$app->session->hasFlash('failure')) : ?>
                <p style="color: red; text-align: center;">
                    <?= Yii::$app->session->getFlash('failure') ?>
                </p>
            <?php endif ?>

            <div class="wrap-input100 validate-input" data-validate="Username diperlukan">
                <!-- <input class="input100" type="text" name="email" placeholder="Email"> -->
                <?= Html::activeInput('text', $model, 'username', ['class' => 'input100', 'placeholder' => 'Username']) ?>
                <span class="focus-input100"></span>
                <span class="symbol-input100">
                    <i class="fa fa-envelope" aria-hidden="true"></i>
                </span>
            </div>

            <div class="wrap-input100 validate-input" data-validate="Password diperlukan">
                <!-- <input class="input100" type="password" name="pass" placeholder="Password"> -->
                <?= Html::activeInput('password', $model, 'password', ['class' => 'input100', 'placeholder' => 'Password']) ?>
                <span class="focus-input100"></span>
                <span class="symbol-input100">
                    <i class="fa fa-lock" aria-hidden="true"></i>
                </span>
            </div>

            <div class="container-login100-form-btn">
                <button class="login100-form-btn">
                    Login
                </button>
            </div>
            <!-- </form> -->
            <?= Html::endForm() ?>
        </div>
    </div>
</div>