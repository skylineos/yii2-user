<?php

/* @var $this yii\web\View */
/* @var $model skyline\yii\user\models\User */

$this->title = 'Create User';
$this->params['breadcrumbs'][] = ['label' => 'User Accounts', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

echo  $this->render('_form', [
    'model' => $model,
]);
