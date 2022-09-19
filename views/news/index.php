<?php

use yii\bootstrap5\Html;
use yii\widgets\ListView;
use yii\widgets\Pjax;
use yii\bootstrap5\ActiveForm;
use kartik\date\DatePicker;
use yii\bootstrap5\LinkPager;

/** @var yii\web\View $this */
/** @var app\models\news\NewsSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->params['breadcrumbs'][] = $this->title;
?>

<div class="news-index row">

    <?php if (!Yii::$app->request->isAjax): ?>    
        <div class="col border-bottom mb-2">
            <p class="display-4">
                <?= $this->title ?>
            </p>    
        </div>
    <?php endif; ?>

    <div class="col-12">
    <?php Pjax::begin(['id'=>'ajax-news-ifns', 'timeout'=>false, 'enablePushState'=>false, 'scrollTo'=>0]); ?>
    
        <div class="card">
            <div class="card-body">
                <?php $form = ActiveForm::begin([
                    'options' => ['data-pjax' => true, 'autocomplete' => 'off'],
                    'method' => 'get',
                    'id' => 'form-news-ifns',
                ]); ?>

                <br />
                <div class="row">
                    <div class="col-5">
                        <?= $form->field($searchModel, 'searchText')->textInput(['placeholder'=>'Поиск по тексту...'])->label(false) ?>
                    </div>
                    <div class="col-3">
                        <?= $form->field($searchModel, 'searchDate1')->widget(DatePicker::class, [
                            'pluginOptions' => [
                                'todayHighlight' => true,
                                'todayBtn' => true,
                                'autoclose' => true,
                            ],
                            'options' => [
                                'placeholder'=>'Поиск по дате от...',
                            ],
                        ])->label(false) ?>
                    </div>

                    <div class="col-3">
                        <?= $form->field($searchModel, 'searchDate2')->widget(DatePicker::class, [
                            'pluginOptions' => [
                                'todayHighlight' => true,
                                'todayBtn' => true,
                                'autoclose' => true,
                            ],
                            'options' => [
                                'placeholder'=>'Поиск по дате до...',
                            ],
                        ])->label(false) ?>
                    </div>

                    <div class="col-1">
                        <?= Html::submitButton('Поиск', ['class' => 'btn btn-primary col-12', 'style' => 'float: right;']) ?>
                    </div>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>  

        <?= ListView::widget([
            'dataProvider' => $dataProvider,
            'itemView' => '_list',
            'layout' => "{items}\n{pager}",
            'pager' => [
                'class' => LinkPager::class,
                'options' => [
                    'class' => 'pt-2',
                ],
            ],
        ]) ?>

    <?php Pjax::end(); ?>
    </div>

</div>
