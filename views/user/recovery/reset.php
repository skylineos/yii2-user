<?php

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\widgets\ActiveForm;

$this->title = 'Reset Your Password';
?>

<!-- BEGIN FORGOT PASSWORD FORM -->
<?php $form = ActiveForm::begin([
    'id' => 'reset-password-form',
    'class' => 'login-form'
]); ?>

<p> Welcome back. Use the form below to reset your password. </p>

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

<div class="form-actions">
    <a href="/" id="back-btn" class="btn green btn-outline">Cancel</a>
    <button type="submit" class="btn btn-success uppercase pull-right">Reset Password</button>
</div>

<?php ActiveForm::end(); ?>
<!-- END FORGOT PASSWORD FORM -->
