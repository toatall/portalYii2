<?php

/** @var yii\web\View $this */
/** @var app\modules\kadry\models\Award $model */

use kartik\widgets\Typeahead;
use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
use yii\helpers\Url;
use yii\web\JsExpression;

?>

<?php $form = ActiveForm::begin([ 
    'options' => [
        'class' => 'mv-form',
        'autocomplete' => 'off',
    ],
]); 
$inputDepIndex = Html::getInputId($model, 'dep_index');
$inputDepName = Html::getInputId($model, 'dep_name');
$inputPost = Html::getInputId($model, 'post');

$inputAwDoc = Html::getInputId($model, 'aw_doc');
$inputAwDocNum = Html::getInputId($model, 'aw_doc_num');
$inputAwDocDate = Html::getInputId($model, 'aw_date_doc');


?>

    <div class="card">
        <div class="card-header">
            Информация о сотруднике
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <?= Html::errorSummary($model, ['class' => 'alert alert-danger']) ?>
                </div>
                <div class="col-12">

                    <?php
                    $templatePerson = '<div>'
                            . '<p><span class="lead">{{value}}</span>'
                            . ' <snap class="float-end">{{post}}</span></p>'
                            . ' <p class="description">{{dep_name}} ({{dep_index}})</p>'
                        . '</div>';

                    echo $form->field($model, 'fio')->widget(Typeahead::class, [
                        'dataset' => [
                            [
                                'remote' => [
                                    'url' => Url::to(['list-fio', 'org'=>$model->org_code]) . '&q=%QUERY%',
                                    'wildcard' => '%QUERY%',
                                ],
                                'display' => 'value',
                                'templates' => [
                                    'suggestion' => new JsExpression("Handlebars.compile('{$templatePerson}')"),
                                ],
                            ],                            
                        ],
                        'pluginOptions' => [
                            'highlight' => true,
                        ],
                        'pluginEvents' => [
                            'typeahead:select' => new JsExpression(<<<JS
                                function(e, data) { 
                                    $('#$inputDepIndex').val(data.dep_index);    
                                    $('#$inputDepName').val(data.dep_name);
                                    $('#$inputPost').val(data.post);                                    
                                }
                            JS),
                        ],
                    ]) ?>
                </div>
                
                <div class="col-4">
                    <?= $form->field($model, 'dep_index')->textInput(['maxlength' => true]) ?>
                </div>

                <div class="col-4">
                    <?= $form->field($model, 'dep_name')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-4">
                    <?= $form->field($model, 'post')->textInput(['maxlength' => true]) ?>
                </div>                                             
            
            </div>
        </div>
    </div>

    <div class="card mt-2">
        <div class="card-header">
            Информация о награде
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12">                    
                    <?php
                    $templateAward = '<div>'
                            . '<p><span class="lead">{{value}}</span></p>'
                            . ' <p class="description">'
                            . ' <snap>{{aw_doc}}</span> {{aw_doc_num}} от {{aw_date_doc}}</p>'
                        . '</div>';

                    echo $form->field($model, 'aw_name')->widget(Typeahead::class, [
                        'dataset' => [
                            [
                                'remote' => [
                                    'url' => Url::to(['list-awards', 'org'=>$model->org_code]) . '&q=%QUERY%',
                                    'wildcard' => '%QUERY%',
                                ],
                                'display' => 'value',
                                'templates' => [
                                    'suggestion' => new JsExpression("Handlebars.compile('{$templateAward}')"),
                                ],
                            ],                            
                        ],
                        'pluginOptions' => [
                            'highlight' => true,
                        ],
                        'pluginEvents' => [
                            'typeahead:select' => new JsExpression(<<<JS
                                function(e, data) { 
                                    $('#$inputAwDoc').val(data.aw_doc);    
                                    $('#$inputAwDocNum').val(data.aw_doc_num);
                                    $('#$inputAwDocDate').val(data.aw_date_doc);                                    
                                },
                            JS),
                        ],
                    ]) ?>
                </div>

                <div class="col-4">
                    <?= $form->field($model, 'aw_doc')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-4">
                    <?= $form->field($model, 'aw_doc_num')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-4">
                    <?= $form->field($model, 'aw_date_doc')->textInput(['maxlength' => true]) ?>
                </div>
            </div>
        </div>
    </div>
           
    <div>
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary mt-3']) ?>
    </div>

<?php ActiveForm::end(); ?>

