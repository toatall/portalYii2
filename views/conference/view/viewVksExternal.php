<?php
/** @var yii\web\View $this */
/** @var app\models\conference\AbstractConference $model */
/** @var string $action */

use yii\widgets\DetailView;
use app\models\conference\AbstractConference;
use kartik\editable\Editable;
use kartik\select2\Select2;
use yii\bootstrap4\Html;
use app\models\conference\VksExternal;

$isAjax = Yii::$app->request->isAjax;
$this->title = $model->theme;
$accessShowAllFields = $model->accessShowAllFields();

$this->params['breadcrumbs'][] = ['label' => VksExternal::getTypeLabel(), 'url' => ['/conference/vks-external']];
$this->params['breadcrumbs'][] = $this->title;
?>

<h1 class="mv-hide">
    <?php if ($model->isEditor()): ?>
        <?= Editable::widget([
            'asPopover' => false,
            'model' => $model,
            'attribute' => 'theme',
            'formOptions' => ['action' => $action], 
            'submitOnEnter' => false,
            'inputType' => Editable::INPUT_TEXTAREA,
            'options' => [
                'rows' => 5,
                'style' => 'width:100em;',
            ],
        ]); ?>
    <?php else: ?>
        <div class="col border-bottom mb-2">
            <p class="display-4">
                <?= Html::encode($this->title) ?>
            </p>    
        </div>           
    <?php endif; ?>
    <br />
    <small><?= $model->typeLabel() ?></small>
</h1>

<?php if ($isAjax && $model->isEditor()): ?>
<?= Html::a('<i class="fas fa-external-link-alt"></i> Редактировать', ['/conference/view', 'id'=>$model->id], ['class' => 'btn btn-secondary', 'target'=>'_blank']) ?>
<?php endif; ?>

<div class="mt-2">
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [            
                'attribute' => 'date_start',
                'value' => function(AbstractConference $model) use ($action, $isAjax) {
                    if (!$isAjax && $model->isEditor()) {
                        return Editable::widget([
                            'model' => $model,                       
                            'attribute' => 'date_start',                        
                            'formOptions' => ['action' => $action],
                            'inputType' => Editable::INPUT_DATETIME,
                            'options' => [
                                'pluginOptions' => [
                                    'todayHighlight' => true,
                                    'todayBtn' => true,
                                    'autoclose' => true,
                                    'format' => 'dd.mm.yyyy hh:ii'
                                ],
                            ],                        
                        ]);
                    }
                    return Yii::$app->formatter->asDatetime($model->date_start);
                },
                'format' => 'raw',
            ],
                        
            [
                'attribute' => 'duration',
                'value' => function(AbstractConference $model) use ($action, $isAjax) {
                    if (!$isAjax && $model->isEditor()) {
                        return Editable::widget([
                            'model' => $model,                       
                            'attribute' => 'duration',                        
                            'formOptions' => ['action' => $action],
                            'inputType' => Editable::INPUT_TIME,
                            'options' => [
                                'pluginOptions' => [
                                    'showMeridian' => false,
                                    'defaultTime' => '01:00',
                                ],
                            ],                        
                        ]);
                    }
                    return $model->duration;
                },
                'format' => 'raw',            
            ],
            [
                'attribute' => 'place',
                'value' => function(AbstractConference $model) use ($action, $isAjax) {
                    if (!$isAjax && $model->isEditor()) {
                        return Editable::widget([
                            'model' => $model,                        
                            'attribute' => 'place',
                            'formOptions' => ['action' => $action],
                            'inputType' => Editable::INPUT_HIDDEN,
                            'afterInput' => function($form, $widget) {
                                return $form->field($widget->model, 'arrPlace')->widget(Select2::class, [
                                    'data' => $widget->model->dropDownListLocation(),
                                    'options' => [
                                        'multiple' => true,
                                    ],
                                    'hashVarLoadPosition' => \yii\web\View::POS_READY,
                                ])->label(false);
                            },
                        ]);                    
                    }
                    return $model->place;
                },
                'format' => 'raw',
            ],
            [
                'attribute' => 'responsible',
                'value' => function(AbstractConference $model) use ($action, $isAjax) {
                    if (!$isAjax && $model->isEditor()) {
                        return Editable::widget([
                            'model' => $model,
                            'attribute' => 'responsible',
                            'formOptions' => ['action' => $action],                        
                        ]);                    
                    }
                    return $model->responsible;
                },
                'format' => 'raw',
                'visible' => $accessShowAllFields,
            ],   
            [
                'attribute' => 'format_holding',
                'value' => function(AbstractConference $model) use ($action, $isAjax) {
                    if (!$isAjax && $model->isEditor()) {
                        return Editable::widget([
                            'model' => $model,
                            'attribute' => 'format_holding',
                            'formOptions' => ['action' => $action],                        
                            'inputType' => Editable::INPUT_SELECT2,
                            'options' => [
                                'data' => $model->dropDownListFormat(),
                            ],
                        ]);                    
                    }
                    return $model->format_holding;
                },
                'format' => 'raw',
                'visible' => $accessShowAllFields,
            ], 
            [
                'attribute' => 'members_count',
                'value' => function(AbstractConference $model) use ($action, $isAjax) {
                    if (!$isAjax && $model->isEditor()) {
                        return Editable::widget([
                            'model' => $model,
                            'attribute' => 'members_count',
                            'formOptions' => ['action' => $action],                        
                        ]);                    
                    }
                    return $model->members_count;
                },
                'format' => 'raw',
                'visible' => $accessShowAllFields,
            ],
            [
                'attribute' => 'material_translation',
                'value' => function(AbstractConference $model) use ($action, $isAjax) {
                    if (!$isAjax && $model->isEditor()) {
                        return Editable::widget([
                            'model' => $model,
                            'attribute' => 'material_translation',
                            'formOptions' => ['action' => $action],                        
                            'inputType' => Editable::INPUT_SELECT2,
                            'options' => [
                                'data' => $model->dropDownListMaterials(),
                            ],
                        ]);                    
                    }
                    return $model->material_translation;
                },
                'format' => 'raw',
                'visible' => $accessShowAllFields,
            ], 
            [
                'attribute' => 'members_count_ufns',
                'value' => function(AbstractConference $model) use ($action, $isAjax) {
                    if (!$isAjax && $model->isEditor()) {
                        return Editable::widget([
                            'model' => $model,
                            'attribute' => 'members_count_ufns',
                            'formOptions' => ['action' => $action],                        
                        ]);                    
                    }
                    return $model->members_count_ufns;
                },
                'format' => 'raw',
                'visible' => $accessShowAllFields,
            ],
            [
                'attribute' => 'person_head',
                'value' => function(AbstractConference $model) use ($action, $isAjax) {
                    if (!$isAjax && $model->isEditor()) {
                        return Editable::widget([
                            'model' => $model,
                            'attribute' => 'person_head',
                            'formOptions' => ['action' => $action],                        
                        ]);                    
                    }
                    return $model->person_head;
                },
                'format' => 'raw',
                'visible' => $accessShowAllFields,
            ],
            [
                'attribute' => 'link_event',
                'value' => function(AbstractConference $model) use ($action, $isAjax) {
                    if (!$isAjax && $model->isEditor()) {
                        return Editable::widget([
                            'model' => $model,
                            'attribute' => 'link_event',
                            'formOptions' => ['action' => $action],                        
                        ]);                    
                    }
                    return $model->link_event;
                },
                'format' => 'raw',
                'visible' => $accessShowAllFields,
            ],
            [
                'attribute' => 'is_connect_vks_fns',
                'value' => function(AbstractConference $model) use ($action, $isAjax) {
                    if (!$isAjax && $model->isEditor()) {
                        return Editable::widget([
                            'model' => $model,
                            'attribute' => 'is_connect_vks_fns',
                            'formOptions' => ['action' => $action],
                            'inputType' => Editable::INPUT_CHECKBOX,
                            'options' => [
                                'label' => 'Подключение к ВКС ЦА ФНС России',//$model->getAttributeLabel($model->is_connect_vks_fns),
                            ],
                            'displayValueConfig' => [
                                0 => 'Нет',
                                1 => 'Да',
                            ],
                        ]);                    
                    }
                    return Yii::$app->formatter->asBoolean($model->is_connect_vks_fns);
                },
                'format' => 'raw',
                'visible' => $accessShowAllFields,
            ],
            [
                'attribute' => 'platform',
                'value' => function(AbstractConference $model) use ($action, $isAjax) {
                    if (!$isAjax && $model->isEditor()) {
                        return Editable::widget([
                            'model' => $model,
                            'attribute' => 'platform',
                            'formOptions' => ['action' => $action],                        
                        ]);                    
                    }
                    return $model->platform;
                },
                'format' => 'raw',
                'visible' => $accessShowAllFields,
            ],
            [
                'attribute' => 'full_name_support_ufns',
                'value' => function(AbstractConference $model) use ($action, $isAjax) {
                    if (!$isAjax && $model->isEditor()) {
                        return Editable::widget([
                            'model' => $model,
                            'attribute' => 'full_name_support_ufns',
                            'formOptions' => ['action' => $action],                        
                        ]);                    
                    }
                    return $model->full_name_support_ufns;
                },
                'format' => 'raw',
                'visible' => $accessShowAllFields,
            ],
            [            
                'attribute' => 'date_test_vks',
                'value' => function(AbstractConference $model) use ($action, $isAjax) {
                    if (!$isAjax && $model->isEditor()) {
                        return Editable::widget([
                            'model' => $model,                       
                            'attribute' => 'date_test_vks',                        
                            'formOptions' => ['action' => $action],
                            'inputType' => Editable::INPUT_DATETIME,
                            'options' => [
                                'pluginOptions' => [
                                    'todayHighlight' => true,
                                    'todayBtn' => true,
                                    'autoclose' => true,
                                    'format' => 'dd.mm.yyyy hh:ii'
                                ],
                            ],
                        ]);
                    }
                    return Yii::$app->formatter->asDatetime($model->date_test_vks);
                },
                'format' => 'raw',
                'visible' => $accessShowAllFields,
            ],
            [
                'attribute' => 'count_notebooks',
                'value' => function(AbstractConference $model) use ($action, $isAjax) {
                    if (!$isAjax && $model->isEditor()) {
                        return Editable::widget([
                            'model' => $model,
                            'attribute' => 'count_notebooks',
                            'formOptions' => ['action' => $action],                        
                        ]);                    
                    }
                    return $model->count_notebooks;
                },
                'format' => 'raw',
                'visible' => $accessShowAllFields,
            ],
            [
                'attribute' => 'members_organization',
                'value' => function(AbstractConference $model) use ($action, $isAjax) {
                    if (!$isAjax && $model->isEditor()) {
                        return Editable::widget([
                            'model' => $model,
                            'attribute' => 'members_organization',
                            'formOptions' => ['action' => $action],  
                            'inputType' => Editable::INPUT_TEXTAREA,
                        ]);                    
                    }
                    return $model->members_organization;
                },
                'format' => 'raw',
                'visible' => $accessShowAllFields,
            ],
            [
                'attribute' => 'is_change_time_gymnastic',
                'value' => function(AbstractConference $model) use ($action, $isAjax) {
                    if (!$isAjax && $model->isEditor()) {
                        return Editable::widget([
                            'model' => $model,
                            'attribute' => 'is_change_time_gymnastic',
                            'formOptions' => ['action' => $action],
                            'inputType' => Editable::INPUT_CHECKBOX,
                            'options' => [
                                'label' => 'Требуется перенос проведения зарядки (требуется согласование с приемной)',
                            ],
                            'displayValueConfig' => [
                                '0' => 'Нет',
                                '1' => 'Да',
                            ],
                        ]);                    
                    }
                    return Yii::$app->formatter->asBoolean($model->is_change_time_gymnastic);
                },
                'format' => 'raw',
                'visible' => $accessShowAllFields,
            ],
            [
                'attribute' => 'note',
                'value' => function(AbstractConference $model) use ($action, $isAjax) {
                    if (!$isAjax && $model->isEditor()) {
                        return Editable::widget([
                            'model' => $model,
                            'attribute' => 'note',
                            'formOptions' => ['action' => $action],  
                            'inputType' => Editable::INPUT_TEXTAREA,
                            'options' => [
                                'rows' => 10,                            
                            ],
                        ]);                    
                    }
                    return $model->note;
                },
                'format' => 'raw',
                'visible' => $accessShowAllFields,
            ],                        
        ],
    ]) ?>
</div>