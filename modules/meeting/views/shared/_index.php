<?php
/** @var \yii\web\View $this */
/** @var \yii\data\ActiveDataProvider $dataProvider */
/** @var \app\modules\meeting\models\search\MeetingSearch $searchModel */

/** @var string $title */
/** @var bool $accessShowAllFields */
/** @var bool $roleEditor */
/** @var string $typeMeeting */
/** @var array $grantAccessUnique */
/** @var array $columns */
/** @var bool $showBtnLocation */
/** @var array $extensionColumns */

use app\modules\admin\modules\grantaccess\widgets\GrantAccessWidget;
use app\modules\meeting\assets\MeetingAsset;
use app\modules\meeting\models\ar\ARMeeting;
use yii\bootstrap5\Html;
use kartik\grid\GridView;
use yii\helpers\Url;
use yii\widgets\Pjax;

MeetingAsset::register($this);

$this->title = $title;
$this->params['breadcrumbs'][] = $this->title;
?>

<?php Pjax::begin(['id' => 'pjax-meeting-' . $typeMeeting . '-index', 'timeout' => false]) ?>
<div class="<?= $typeMeeting ?>-index">

    <div class="col border-bottom mb-2">
        <p class="display-5">
            <?= Html::encode($this->title) ?>
        </p>
    </div>
    
    <?= GrantAccessWidget::widget([
        'uniques' => $grantAccessUnique,
    ]) ?>

    <div class="mb-3">
        <?= $this->render('index/_search', ['searchModel' => $searchModel]) ?>
    </div>

    <div style="font-size:1.2rem;">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'tableOptions' => ['class' => 'table table-bordered'],
        'options' => [
            'style' => 'table-layout:fixed',
        ],
        'rowOptions' => function($model): array {
            return $model->isFinished() ? ['class' => 'finished'] : [];
        },
        'columns' => \yii\helpers\ArrayHelper::merge([    
                require __DIR__ . '/index/_grid-column-date_start.php',                
                require __DIR__ . '/index/_grid-column-time_start.php',
                [
                    'attribute' => 'org_code',
                    'visible' => in_array('org_code', $columns),
                ],                             
                [
                    'attribute' => 'theme',
                    'visible' => $accessShowAllFields && in_array('theme', $columns),
                ], 
                [
                    'attribute' => 'responsible',
                    'visible' => $accessShowAllFields && in_array('responsible', $columns),
                ], 
                [
                    'attribute' => 'members_people',                
                    'visible' => $accessShowAllFields && in_array('members_people', $columns),
                ],
                [
                    'attribute' => 'members_organization',
                    'visible' =>  in_array('members_organization', $columns),
                ],
                [
                    'attribute' => 'place',
                    'visible' =>  in_array('place', $columns),
                ],
                'duration:duration',                
            ],
            // дополнительные колонки
            $extensionColumns ?? [],
            // кнопки просмотра, редактирования и удаления
            [
                [
                    'label' => 'Статус',
                    'format' => 'raw',
                    'value' => function(ARMeeting $model) {
                        if ($model->isFinished()) {
                            return '<span class="text-success"><i class="far fa-check-circle"></i> Завершено</span>';
                        }
                        if ($model->isUnderway()) {
                            return '<span class="text-primary text-nowrap"><i class="far fa-clock fa-spin"></i> Проводится...</span>';
                        }
                        return '<i class="far fa-clock"></i> Не проводилось';
                    },
                ],
                [                
                    'headerOptions' => [
                        'style' => 'width: 10rem;'
                    ],  
                    'value' => function(ARMeeting $model) use ($roleEditor): string {
                        $res = "";
                        $res .= Html::beginTag('div', ['class' => 'btn-group']);
                        $res .= Html::a('<i class="fas fa-list"></i>', ['view', 'id'=>$model->id], [
                            'class' => 'btn btn-primary mv-link',
                            'data-bs-toggle' => 'tooltip',
                            'data-bs-trigger' => 'hover',
                            'data-bs-title' => 'Подробнее',
                        ]);
                        if ($roleEditor) {
                            $res .= Html::a('<i class="fas fa-pencil"></i>', ['update', 'id' => $model->id], [
                                'class' => 'btn btn-primary btn-update',
                                'data-bs-toggle' => 'tooltip',
                                'data-bs-trigger' => 'hover',
                                'data-bs-title' => 'Редактировать',
                            ]);
                            $res .= Html::button('<i class="fas fa-trash"></i>', [
                                'class' => 'btn btn-danger btn-delete',
                                'data-url' => Url::to(['delete', 'id' => $model->id]),
                                'data-bs-toggle' => 'tooltip',
                                'data-bs-trigger' => 'hover',
                                'data-bs-title' => 'Удалить',
                            ]);
                        }
                        $res .= Html::endTag('div');
                        return $res;
                    },
                    'format' => 'raw',
                ],
        ]),
        'toolbar' => [
            $showBtnLocation && Yii::$app->user->can('admin') ?
                Html::a('<i class="fas fa-door-open"></i> Кабинеты', ['/meeting/location/index'], ['class' => 'btn btn-primary mv-link me-2'])
                : null,
            $roleEditor ? Html::a('<i class="fas fa-plus"></i> Добавить', ['create'], ['id' => 'btn-create', 'class' => 'btn btn-success me-2']) : null,
            '{export}',
            '{toggleData}',            
        ],
        'export' => [
            'showConfirmAlert' => false,
        ],
        'panel' => [
            'type' => GridView::TYPE_DEFAULT,       
        ],
    ]); ?>
    </div>

</div>

<?= $this->render('index/_js', [
    'typeMeeting' => $typeMeeting,
]) ?>

<?php

$this->registerCss(<<<CSS
    .bootstrap-dialog {
        z-index: 1056 !important;
    }
CSS);
?>

<?php Pjax::end() ?>