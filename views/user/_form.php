<?php

use yii\helpers\ArrayHelper;
use skyline\yii\metronic\widgets\ActiveForm;
use skyline\yii\metronic\widgets\Portlet;
use yii\rbac\Item;

/* @var $this yii\web\View */
/* @var $model skyline\yii\user\models\User */
/* @var $form yii\widgets\ActiveForm */

$form = ActiveForm::begin([
    'id' => 'user-form',
    ]);

Portlet::begin([
    'showFooter' => true,
    'footerItems' => [
        [
            'type' => 'submitButton',
            'label' => $model->isNewRecord ? 'Create' : 'Update',
            'options' => ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']
        ],
        [
            'type' => 'a',
            'label' => 'Cancel',
            'url' => ['index'],
            'options' => ['class' => 'btn btn-secondary'],
        ],
    ]
]);

?>

    <div class="form-group row">
        <div class="col">
            <?= $form->field($model, 'name')->textInput(['maxlength' => true, 'placeholder' => 'John Doe']) ?>
        </div>
        <div class="col">
            <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
        </div>
    </div>

    <div class="form-group row">
        <div class="col">
            <?= $form->field($model, 'status')->dropDownList(['1' => 'Active', '0' => 'Inactive']) ?>
        </div>
    </div>

    <?php if ($model->isNewRecord) { ?>
    <div class="alert alert-outline-success" role="alert">
        <div class="alert-icon"><i class="fas fa-exclamation-circle"></i></div>
        <div class="alert-text">An email containing instructions for completing the account registration will be sent
            to the user when you click 'Create'</div>
    </div>
    <?php } ?>

<?php Portlet::end() ?>

<?php ActiveForm::end(); ?>
