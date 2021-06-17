<?php

use yii\widgets\Pjax;
use skyline\yii\metronic\widgets\GridView;
use skyline\yii\metronic\widgets\Portlet;
use skyline\yii\metronic\widgets\Badge;
use skyline\yii\user\models\User;

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
            [
                'attribute' => 'name',
                'contentOptions' => [
                    'class' => 'td-user-name'
                ]
            ],
            [
                'attribute' => 'status',
                'format' => 'html',
                'value' => function ($model) {
                    return $model->status === User::STATUS_ACTIVE
                        ? Badge::widget(['content' => 'Active', 'style' => 'success'])
                        : Badge::widget(['content' => 'Inactive', 'style' => 'danger']);
                },
                'filter' => [
                    User::STATUS_ACTIVE => 'Active',
                    User::STATUS_INACTIVE => 'Inactive',
                ],
                'contentOptions' => [
                    'class' => 'td-user-status'
                ]
            ],
            [
                'attribute' => 'lastLogin',
                'format' => 'datetime',
                'filter' => false,
                'contentOptions' => [
                    'class' => 'td-user-lastLogin'
                ]
            ],
            // 'dateCreated',
            [
                 'attribute' => 'lastModified',
                 'format' => 'datetime',
                 'contentOptions' => [
                     'class' => 'td-user-lastModified'
                 ],
                 'filter' => false,
            ],
            // 'modifiedBy',
        ],
    ]); ?>
    <?php Pjax::end(); ?>

<?php Portlet::end(); ?>
