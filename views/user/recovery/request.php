<?php

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\widgets\ActiveForm;

$this->title = 'Lost Password';
?>

<!-- BEGIN FORGOT PASSWORD FORM -->
<?php $form = ActiveForm::begin([
    'id' => 'password-recovery-form',
    'class' => 'login-form'
]); ?>

<h3 class="font-green">Forgot Password ?</h3>
<p> Enter your e-mail address below to recover your password. </p>

<?php if (\Yii::$app->session->hasFlash('accountNotFound')) { ?>
    <br><br><br><div class="alert alert-danger"><?=\Yii::$app->session->getFlash('accountNotFound')?></div>
<?php } ?>

<?= $form->field($model, 'email')->textInput(['autofocus' => true]) ?>

<div class="form-actions">
    <a href="/" id="back-btn" class="btn green btn-outline">Back</a>
    <button type="submit" class="btn btn-success uppercase pull-right">Submit</button>
</div>

<?php ActiveForm::end(); ?>
<!-- END FORGOT PASSWORD FORM -->
