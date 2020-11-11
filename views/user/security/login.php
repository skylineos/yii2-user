<?php

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $model app\models\LoginForm */

use app\modules\metronic\widgets\ActiveForm;

$this->title = 'Citizen Administrative Login';
?>
    
<div class="kt-login__container">
    <div class="kt-login__logo">
        <a href="#">
            <img src="/images/citizenLogo.png">
        </a>
    </div>
    <div class="kt-login__signin">
        <div class="kt-login__head">
            <h3 class="kt-login__title">Sign In To Citizen</h3>
        </div>
        
        <?php if (\Yii::$app->session->hasFlash('recoverySent')) { ?>
            <div class="alert alert-info"><?=\Yii::$app->session->getFlash('recoverySent')?></div>
        <?php } ?>

        <?php if (\Yii::$app->session->hasFlash('resetFailed')) { ?>
            <div class="alert alert-danger"><?=\Yii::$app->session->getFlash('resetFailed')?></div>
        <?php } ?>

        <?php $form = ActiveForm::begin([
            'id' => 'login-form',
            'enableClientValidation'=> false,
            'options' => [
                'class' => 'kt-form',
            ]
        ]) ?>

            <div class="input-group">
                <?= $form->field($model, 'username')->textInput(['class' => 'form-control', 'autocomplete' => 'off', 'placeholder' => 'Email', 'required'])->label(false)?>
            </div>
            <div class="input-group">
                <?= $form->field($model, 'password')->passwordInput(['class' => 'form-control form-control-lg', 'autocomplete' => 'off', 'placeholder' => 'Password', 'required'])->label(false) ?>
            </div>
            <div class="row kt-login__extra">
                <div class="col">
                    <label class="kt-checkbox">
                        <input type="checkbox" name="LoginForm[rememberMe]" id="loginform-rememberme" value="<?=$model->rememberMe == true ? 1 : 0?>" <?=$model->rememberMe == true ? 'checked' : ''?>> Remember me
                        <span></span>
                    </label>
                </div>
                <!--
                <div class="col kt-align-right">
                    <a href="javascript:;" id="kt_login_forgot" class="kt-login__link">Forget Password ?</a>
                </div>
                -->
            </div>
            <div class="kt-login__actions">
                <button id="kt_login_signin_submit" class="btn btn-brand btn-elevate kt-login__btn-primary" type="submit">Sign In</button>
            </div>
        <?php ActiveForm::end(); ?>
    </div>
    <div class="kt-login__signup">
        <div class="kt-login__head">
            <h3 class="kt-login__title">Sign Up</h3>
            <div class="kt-login__desc">Enter your details to create your account:</div>
        </div>
        <form class="kt-form" action="">
            <div class="input-group">
                <input class="form-control" type="text" placeholder="Fullname" name="fullname">
            </div>
            <div class="input-group">
                <input class="form-control" type="text" placeholder="Email" name="email" autocomplete="off">
            </div>
            <div class="input-group">
                <input class="form-control" type="password" placeholder="Password" name="password">
            </div>
            <div class="input-group">
                <input class="form-control" type="password" placeholder="Confirm Password" name="rpassword">
            </div>
            <div class="row kt-login__extra">
                <div class="col kt-align-left">
                    <label class="kt-checkbox">
                        <input type="checkbox" name="agree">I Agree the <a href="#" class="kt-link kt-login__link kt-font-bold">terms and conditions</a>.
                        <span></span>
                    </label>
                    <span class="form-text text-muted"></span>
                </div>
            </div>
            <div class="kt-login__actions">
                <button id="kt_login_signup_submit" class="btn btn-brand btn-elevate kt-login__btn-primary">Sign Up</button>&nbsp;&nbsp;
                <button id="kt_login_signup_cancel" class="btn btn-light btn-elevate kt-login__btn-secondary">Cancel</button>
            </div>
        </form>
    </div>
    <div class="kt-login__forgot">
        <div class="kt-login__head">
            <h3 class="kt-login__title">Forgotten Password ?</h3>
            <div class="kt-login__desc">Enter your email to reset your password:</div>
        </div>
        <form class="kt-form" action="">
            <div class="input-group">
                <input class="form-control" type="text" placeholder="Email" name="email" id="kt_email" autocomplete="off">
            </div>
            <div class="kt-login__actions">
                <button id="kt_login_forgot_submit" class="btn btn-brand btn-elevate kt-login__btn-primary">Request</button>&nbsp;&nbsp;
                <button id="kt_login_forgot_cancel" class="btn btn-light btn-elevate kt-login__btn-secondary">Cancel</button>
            </div>
        </form>
    </div>
</div>

