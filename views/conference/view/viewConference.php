<?php
/* @var $this \yii\web\View */
/* @var $model app\models\conference\AbstractConference */
/* @var $action string */

use yii\widgets\DetailView;
use app\models\conference\AbstractConference;
use kartik\editable\Editable;
use kartik\select2\Select2;
use yii\helpers\Html;
use app\models\conference\Conference;

$isAjax = Yii::$app->request->isAjax;
$this->title = $model->theme;
$accessShowAllFields = $model->accessShowAllFields();

$this->params['breadcrumbs'][] = ['label' => Conference::getTypeLabel(), 'url' => ['/conference/vks-fns']];
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
    <?= Html::encode($this->title) ?>        
    <?php endif; ?>
    <br /><small><?= $model->typeLabel() ?></small>
</h1>

<?php if ($isAjax && $model->isEditor()): ?>
<?= Html::a('<i class="fas fa-external-link-alt"></i> Редактировать', ['/conference/view', 'id'=>$model->id], ['class' => 'btn btn-default', 'target'=>'_blank']) ?><br /><br />
<?php endif; ?>

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
            'attribute' => 'members_people',
            'value' => function(AbstractConference $model) use ($action, $isAjax) {
                if (!$isAjax && $model->isEditor()) {
                    return Editable::widget([
                        'model' => $model,
                        'attribute' => 'members_people',
                        'formOptions' => ['action' => $action],  
                        'inputType' => Editable::INPUT_TEXTAREA,
                    ]);                    
                }
                return $model->members_people;
            },
            'format' => 'raw',
            'visible' => $accessShowAllFields,
        ],
        [
            'attribute' => 'is_confidential',
            'value' => function(AbstractConference $model) use ($action, $isAjax) {
                if (!$isAjax && $model->isEditor()) {
                    return Editable::widget([
                        'model' => $model,
                        'attribute' => 'is_confidential',
                        'formOptions' => ['action' => $action],
                        'inputType' => Editable::INPUT_CHECKBOX,
                        'options' => [
                            'label' => $model->getAttributeLabel('is_confidential'),
                        ],
                        'displayValueConfig' => [
                            '0' => 'Нет',
                            '1' => 'Да',
                        ],
                    ]);                    
                }
                return Yii::$app->formatter->asBoolean($model->is_confidential);
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