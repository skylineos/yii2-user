<?php

use yii\widgets\Pjax;
use skyline\yii\metronic\widgets\GridView;
use skyline\yii\metronic\widgets\Portlet;
use skyline\yii\metronic\widgets\Badge;

/* @var $this yii\web\View */
/* @var $searchModel skyline\yii\user\models\search\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Users';
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['#']];

$this->params['pageOptions']['links'] = ['create'];

?>

<?php Portlet::begin([
    'title' => 'User Accounts',
    'titleIcon' => '<i class="fal fa-users"></i>'
]); ?>

    <?php Pjax::begin(); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            [
                'class' => 'skyline\yii\metronic\widgets\ActionColumn',
                'template' => '{update} {delete}',
            ],

            'email:email',
            'name',
            [
                'attribute' => 'status',
                'format' => 'html',
                'value' => function ($model) {
                    return $model->status === skyline\yii\user\models\User::STATUS_ACTIVE
                        ? Badge::widget(['content' => 'Active', 'style' => 'success'])
                        : Badge::widget(['content' => 'Inactive', 'style' => 'danger']);
                },
                'filter' => skyline\yii\cms\components\CommonConstants::STATUS_LIST,
            ],
            [
                'attribute' => 'lastLogin',
                'format' => 'datetime',
                'filter' => false,
            ],
            // 'dateCreated',
             'lastModified:datetime',
            // 'modifiedBy',
        ],
    ]); ?>
    <?php Pjax::end(); ?>

<?php Portlet::end(); ?>
