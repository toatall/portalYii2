<?php

use yii\helpers\Html;
use yii\widgets\ListView;
use yii\widgets\Pjax;
use yii\bootstrap\ActiveForm;
use kartik\date\DatePicker;

/* @var $this yii\web\View */
/* @var $searchModel \app\models\news\NewsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->params['breadcrumbs'][] = $this->title;
?>

<div class="news-index row">

    <?php if (!Yii::$app->request->isAjax): ?>
    <h2 class="" style="font-weight: bolder;"><?= $this->title ?></h2>
    <hr />
    <?php endif; ?>

    <?php Pjax::begin(['id'=>'ajax-news-ifns', 'timeout'=>false, 'enablePushState'=>false, 'scrollTo'=>1]); ?>

    <?php if (!Yii::$app->request->isAjax): ?>
    <div class="left-panel">
        <div class="panel panel-default">
            <div class="panel-body">
                <?php $form = ActiveForm::begin([
                    'options' => ['data-pjax' => true, 'autocomplete' => 'off'],
                    'method' => 'get',
                    'id' => 'form-news-ifns',
                ]); ?>

                <br />
                <div class="row">
                    <div class="col-sm-5">
                        <?= $form->field($searchModel, 'searchText')->textInput(['placeholder'=>'Поиск по тексту...'])->label(false) ?>
                    </div>
                    <div class="col-sm-3">
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

                    <div class="col-sm-3">
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

                    <div class="col-sm-1">
                        <?= Html::submitButton('Поиск', ['class' => 'btn btn-primary col-sm-12', 'style' => 'float: right;']) ?>
                    </div>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <?= ListView::widget([
        'dataProvider' => $dataProvider,
        'itemView' => '_list',
        'layout' => "{items}\n{pager}",
    ]) ?>

    <?php Pjax::end(); ?>

</div>
