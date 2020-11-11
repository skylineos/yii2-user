<?php

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\widgets\ActiveForm;

$this->title = 'Complete Your Account Registration';
?>

<!-- BEGIN FORGOT PASSWORD FORM -->
<?php $form = ActiveForm::begin([
    'id' => 'reset-password-form',
    'class' => 'login-form'
]); ?>

<div class="kt-login__container">
    <div class="kt-login__logo">
        <a href="#">
            <img src="/images/citizenLogo.png">
        </a>
    </div>
    <div class="kt-login__signin">
        <div class="kt-login__head">
            <h3 class="kt-login__title">To complete your registration, set your password.</h3>
        </div>

		<?= $form->field($model, 'email')->hiddenInput()->label(false) ?>
		<?= $form->field($model, 'token')->hiddenInput()->label(false) ?>

		<div class="row">
			<div class="col-md-6">
				<?= $form->field($model, 'password')->passwordInput(['autofocus' => true])->label('New Password') ?>
			</div>
			<div class="col-md-6">
				<?= $form->field($model, 'verifyPassword')->passwordInput()->label('Confirm Your Password') ?>
			</div>
		</div>

		<br>

        <div class="kt-login__actions">
		    <a href="/" id="back-btn" class="btn btn-default">Cancel</a>
		    <button type="submit" class="btn btn-brand btn-elevate kt-login__btn-primary pull-right">Compete Registration</button>
        </div>

		<?php ActiveForm::end(); ?>
		<!-- END FORGOT PASSWORD FORM -->
	</div>
</div>
