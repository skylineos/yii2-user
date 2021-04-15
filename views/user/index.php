<?php

use yii\widgets\Pjax;
use app\modules\metronic\widgets\GridView;
use app\modules\metronic\widgets\Portlet;
use app\modules\metronic\widgets\Badge;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\user\models\search\UserSearch */
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
                'class' => 'app\modules\metronic\widgets\ActionColumn',
                'template' => '{update} {delete}',
            ],

            'email:email',
            'name',
            [
                'attribute' => 'status',
                'format' => 'html',
                'value' => function ($model) {
                    return $model->status === 1
                        ? Badge::widget(['content' => 'Active', 'style' => 'success'])
                        : Badge::widget(['content' => 'Inactive', 'style' => 'danger']);
                }
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
