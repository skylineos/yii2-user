<?php

/* @var $this yii\web\View */
/* @var $model skyline\yii\user\models\User */

$this->title = 'Update User: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';

$this->params['pageOptions'] = [
    'links' => ['create', 'delete'],
    'modelId' => $model->id,
];
?>

<div class="portlet light">
    <div class="portlet-body">
		<?= $this->render('_form', [
            'model' => $model,
        ]) ?>
	</div>
</div>
