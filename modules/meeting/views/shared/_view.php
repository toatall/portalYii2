<?php
/** @var \yii\web\View $this */
/** @var \app\modules\meeting\models\Meeting $model */
/** @var string $title */
/** @var bool $accessShowAllFields */
/** @var array $columns */
/** @var array $extensionColumns */

use yii\widgets\DetailView;
use app\modules\meeting\models\ar\ARMeeting;
use yii\bootstrap5\Html;
use yii\widgets\Pjax;

?>
<?php Pjax::begin(['timeout' => false, 'enablePushState' => false]) ?>

<h2 class="mv-hide title border-bottom"<?= Yii::$app->request->isAjax ? ' style="display:none;"' : '' ?>>       
    <?= $this->title ?>    
</h2>

<?php if ($model::isEditor()): ?>
    <div class="mb-2">
        <?= Html::a('<i class="fas fa-pencil"></i> Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary btn-sm']) ?>
    </div>
<?php endif; ?>

<span class="badge meeting-bg-<?= $model::getType() ?> fs-5"><?= $model::getTypeLabel() ?></span>

<div class="mt-2">
    <?= DetailView::widget([
            'model' => $model,
            'attributes' => \yii\helpers\ArrayHelper::merge(
                [
                    'id:text',
                    'date_start:date:Дата начала',
                    'time_start:text:Время начала',
                    'duration:duration',
                    [
                        'attribute' => 'time_end',
                        'label' => 'Время завершения',
                        'value' => function(ARMeeting $model) {
                            return Yii::$app->formatter->asTime($model->time_end, 'short');
                        },
                    ],
                    [
                        'attribute' => 'org_code',
                        'visible' => in_array('org_code', $columns),
                    ],
                    [
                        'attribute' => 'place',
                        'visible' => in_array('place', $columns),
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
                        'visible' => $accessShowAllFields && in_array('members_organization', $columns),
                    ],                                                                            
                ],
                $extensionColumns ?? [],
                [
                    [
                        'attribute' => 'note',
                        'visible' => $accessShowAllFields && in_array('note', $columns),
                    ],                        
                    'date_create:datetime',
                    'date_update:datetime',
                    'author',
                    'authorModel.fio:text:Автор (ФИО)',
                ],
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
                ], 
            ),
    ]) ?>
</div>

<?php Pjax::end() ?>