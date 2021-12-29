<?php

use yii\bootstrap4\Html;
use yii\widgets\ListView;
use yii\widgets\Pjax;
use yii\bootstrap4\ActiveForm;
use kartik\date\DatePicker;
use yii\bootstrap4\LinkPager;

/** @var yii\web\View $this */
/** @var app\models\news\NewsSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->params['breadcrumbs'][] = $this->title;
?>

<div class="news-index">

    <?php Pjax::begin([
        'id'=>'ajax-news',
        'timeout'=>false,
        'enablePushState'=>false,
        'options'=>[
            'data-pjax'=>true,
        ],
        'scrollTo'=>0,
    ]); ?>
   
    <div class="card">        
        <div class="card-body">                        
            <?php $form = ActiveForm::begin([
                'options' => ['data-pjax' => true, 'autocomplete' => 'off'],
                'method' => 'get',
                'id' => 'form-news',
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
                            'id' => 'datepicker_search_date1_ufns',
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
                            'id' => 'datepicker_search_date2_ufns',
                            'placeholder'=>'Поиск по дате до...',
                        ],
                    ])->label(false) ?>
                </div>
                <div class="col-sm-1">
                    <?= Html::submitButton('Поиск', ['class' => 'btn btn-primary col-12', 'style' => 'float: right;']) ?>
                </div>
            </div> 
            <div class="row">
                <div class="col-auto">
                    <?= $form->field($searchModel, 'onlyUfns')->checkbox([
                        'template' => '<div class="custom-control custom-switch">{input} {label}</div><div>{error}</div>',
                    ])->label('Новости Управления') ?>                
                </div>
                <div class="col-auto">
                    <?= $form->field($searchModel, 'onlyIfns')->checkbox([
                        'template' => '<div class="custom-control custom-switch">{input} {label}</div><div>{error}</div>',
                    ])->label('Новости Инспекций') ?>
                </div>
<?php 
$idOnlyIfns = Html::getInputId($searchModel, 'onlyIfns');
$idOnlyUfns = Html::getInputId($searchModel, 'onlyUfns');
$this->registerJs(<<<JS
    $('#$idOnlyIfns').on('change', function() {
        $('#$idOnlyUfns').prop('checked', false);
        $('#form-news').submit();
    });
    $('#$idOnlyUfns').on('change', function() {
        $('#$idOnlyIfns').prop('checked', false);
        $('#form-news').submit();
    });
JS); ?>
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
