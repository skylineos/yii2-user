<?php

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $model app\models\LoginForm */

use skyline\yii\metronic\widgets\ActiveForm;

$this->title = 'Please Sign In';
?>

<div class="kt-login__container">
    <div class="kt-login__logo">
        <a href="#">
            <i class="flaticon-lock" style="font-size: 6em"></i>
        </a>
    </div>
    <div class="kt-login__signin">
        <div class="kt-login__head">
            <h3 class="kt-login__title">Please Sign In</h3>
        </div>

        <?php if (\Yii::$app->session->hasFlash('accountNotFound')) { ?>
        <div class="alert alert-danger"><?=\Yii::$app->session->getFlash('accountNotFound')?>
        </div>
        <?php } ?>

        <?php if (\Yii::$app->session->hasFlash('recoverySent')) { ?>
        <div class="alert alert-info"><?=\Yii::$app->session->getFlash('recoverySent')?>
        </div>
        <?php } ?>

        <?php if (\Yii::$app->session->hasFlash('resetFailed')) { ?>
        <div class="alert alert-danger"><?=\Yii::$app->session->getFlash('resetFailed')?>
        </div>
        <?php } ?>

        <?php $form = ActiveForm::begin([
            'id' => 'login-form',
            'enableClientValidation'=> true,
            'options' => [
                'class' => 'kt-form',
            ]
        ]) ?>

        <div class="input-group">
            <?= $form
                    ->field($model, 'username')
                    ->textInput([
                        'class' => 'form-control',
                        'autocomplete' => 'off',
                        'placeholder' => 'Email',
                        'required'
                        ])
                    ->label(false)?>
        </div>
        <div class="input-group">
            <?= $form
                    ->field($model, 'password')
                    ->passwordInput([
                        'class' => 'form-control form-control-lg',
                        'autocomplete' => 'off',
                        'placeholder' => 'Password',
                        'required'
                        ])
                    ->label(false) ?>
        </div>
        <div class="row kt-login__extra">
            <div class="col">
                <label class="kt-checkbox">
                    <input type="checkbox" name="LoginForm[rememberMe]" id="loginform-rememberme"
                        value="<?=$model->rememberMe == true ? 1 : 0?>"
                        <?=$model->rememberMe == true ? 'checked' : ''?>>
                    Remember me
                    <span></span>
                </label>
            </div>

            <div class="col kt-align-right">
                <a href="javascript:;" id="kt_login_forgot" class="kt-login__link">Forget Password ?</a>
            </div>
        </div>
        <div class="kt-login__actions">
            <button id="kt_login_signin_submit" class="btn btn-brand btn-elevate kt-login__btn-primary"
                type="submit">Sign In</button>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
    <div class="kt-login__forgot">
        <div class="kt-login__head">
            <h3 class="kt-login__title">Forgotten Password ?</h3>
            <div class="kt-login__desc">Enter your email to reset your password:</div>
        </div>

        <?php $form = ActiveForm::begin([
            'id' => 'forgot-password-form',
            'enableClientValidation'=> true,
            'action' => ['/user/user/password-reset-request'],
            'options' => [
                'class' => 'kt-form',
            ]
        ]) ?>
        <div class="input-group">
            <?= $form
                    ->field($forgotPasswordModel, 'email')
                    ->textInput([
                        'class' => 'form-control form-control-lg',
                        'autocomplete' => 'off',
                        'placeholder' => 'Email Address',
                        'required'
                        ])
                    ->label(false) ?>
        </div>
        <div class="kt-login__actions">
            <button id="kt_login_forgot_submit"
                class="btn btn-brand btn-elevate kt-login__btn-primary">Request</button>&nbsp;&nbsp;
            <button id="kt_login_forgot_cancel"
                class="btn btn-light btn-elevate kt-login__btn-secondary">Cancel</button>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>