<?php

use app\models\ChangeLegislation;
use kartik\date\DatePicker;
use kartik\grid\ActionColumn;
use yii\bootstrap4\ActiveForm;
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */
/** @var app\models\ChangeLegislationSearch $searchModel */
/** @var array $urlCreate */

?>

<div class="mt-4">

    <?php if (ChangeLegislation::isRoleModerator()): ?>
        <p>
            <?= Html::a('Добавить', $urlCreate, ['class' => 'btn btn-success']) ?>
        </p>
    <?php endif; ?>

    <?php Pjax::begin([
        'id'=>'ajax-change-legislation-index-tab-' . count($urlCreate),
        'timeout'=>false,
        'enablePushState'=>false,
        'options'=>[
            'data-pjax'=>true,
        ],
    ]); ?>

    <div class="card mb-4">
        <div class="card-header">Поиск</div>
        <div class="card-body">
            <?php $form = ActiveForm::begin([
                'action' => ['index'],
                'method' => 'get',
                'options' => ['data-pjax' => true, 'autocomplete' => 'off'],
            ]); ?>
            <div class="row">
                <div class="col">
                    <div class="row">
                        <div class="col-5">
                            <div class="row">
                                <div class="col">
                                    <?= $form->field($searchModel, 'searchDate1')->widget(DatePicker::class, [
                                        'pluginOptions' => [
                                            'todayHighlight' => true,
                                            'todayBtn' => true,
                                            'autoclose' => true,
                                        ],
                                        'options' => [
                                            'placeholder'=>'Поиск по дате от...',
                                            'id' => 'date-search-1-' . count($urlCreate),
                                        ],
                                    ])->label(false) ?>
                                </div>
                                <div class="col">
                                    <?= $form->field($searchModel, 'searchDate2')->widget(DatePicker::class, [
                                        'pluginOptions' => [
                                            'todayHighlight' => true,
                                            'todayBtn' => true,
                                            'autoclose' => true,
                                        ],
                                        'options' => [
                                            'placeholder'=>'Поиск по дате по...',
                                            'id' => 'date-search-2-' . count($urlCreate),
                                        ],
                                    ])->label(false) ?>
                                </div>
                            </div>                                                        
                        </div>
                        <div class="col">
                            <?= $form->field($searchModel, 'searchText')->textInput(['placeholder'=>'Поиск по тексту...'])->label(false) ?>
                        </div>
                    </div>
                </div>
                <div class="col-1 text-center">
                    <?= Html::submitButton('<i class="fas fa-search"></i>', ['class' => 'btn btn-primary', 'title' => 'Поиск']) ?>
                </div>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            
            'id',
            'type_doc',
            'date_doc',
            'number_doc',
            'name',        
            [
                'label' => 'Даты',
                'format' => 'raw',
                'value' => function($model) {
                    /** @var ChangeLegislation $model */
                    $res = '';
                    if ($model->date_doc_1 !==  null) {
                        $res .= $model->getAttributeLabel('date_doc_1') . ': ' . $model->date_doc_1 . '<br />';
                    }
                    if ($model->date_doc_2 !==  null) {
                        $res .= $model->getAttributeLabel('date_doc_2') . ': ' . $model->date_doc_2 . '<br />';
                    }
                    if ($model->date_doc_3 !==  null) {
                        $res .= $model->getAttributeLabel('date_doc_3') . ': ' . $model->date_doc_3 . '<br />';
                    }
                    return $res;
                },
            ],
            'status_doc',                       

            [
                'format' => 'raw',
                'value' => function($model) {
                    /** @var ChangeLegislation $model */
                    return Html::a('Подробнее', ['view', 'id' => $model->id], ['class' => 'btn btn-primary btn-sm mv-link']);
                },
            ],

            [
                'class' => ActionColumn::class,
                'visibleButtons' => [
                    'view' => false,
                    'update' => ChangeLegislation::isRoleModerator(),
                    'delete' => ChangeLegislation::isRoleModerator(),
                ],
            ],
            
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>

