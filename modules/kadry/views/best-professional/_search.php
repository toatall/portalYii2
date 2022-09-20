<?php

use app\modules\kadry\models\BestProfessionalSearch;
use kartik\select2\Select2;
use yii\bootstrap5\Html;
use kartik\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\modules\kadry\models\BestProfessionalSearch $model */
/** @var kartik\widgets\ActiveForm $form */
?>

<div class="book-shelf-search">
    
    <div class="row mb-4">
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-search"></i> Поиск
                </div>
                <div class="card-body">
                    <?php $form = ActiveForm::begin([
                        'action' => ['index'],
                        'method' => 'get',
                        //'type' => ActiveForm::TYPE_INLINE,
                        'fieldConfig' => [
                            'options' => [
                                //'class' => 'form-group mb-3 mr-2 col-3',
                            ],
                        ],
                        'options' => [
                            'data-pjax' => true,
                        ],
                    ]); ?>

                    <div class="row">
                        <div class="col">
                            <?= $form->field($model, 'org_code')->widget(Select2::class, [
                                'data' => $model->dropDownOrganizations(),
                                'options' => [
                                    'placeholder' => $model->getAttributeLabel('org_code'),
                                ],
                                'pluginOptions' => [
                                    'allowClear' => true,
                                ],
                            ])->label(false) ?>
                        </div>
                        <div class="col">
                            <?= $form->field($model, 'department')->widget(Select2::class, [
                                'data' => $model->dropDownDepartment(),
                                'options' => [
                                    'placeholder' => $model->getAttributeLabel('department'),
                                ],
                                'pluginOptions' => [
                                    'allowClear' => true,
                                ],
                            ])->label(false) ?>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col">
                            <?= $form->field($model, 'period')->widget(Select2::class, [
                                'data' => BestProfessionalSearch::periods(),
                                'options' => [
                                    'placeholder' => $model->getAttributeLabel('period'),
                                ],
                                'pluginOptions' => [
                                    'allowClear' => true,
                                ],
                            ])->label(false) ?>
                        </div>
                        <div class="col">
                            <?= $form->field($model, 'period_year')->widget(Select2::class, [
                                'data' => BestProfessionalSearch::periodsYears(),
                                'options' => [
                                    'placeholder' => $model->getAttributeLabel('period_year'),
                                ],
                                'pluginOptions' => [
                                    'allowClear' => true,
                                ],
                            ])->label(false) ?>
                        </div>
                    </div>
                    
                    
                    <div class="btn-group form-group mb">
                        <?= Html::submitButton('Поиск', ['class' => 'btn btn-primary']) ?>                       
                    </div>
                    <?php ActiveForm::end(); ?>
                </div>                
            </div>            
        </div>        
    </div>
    
</div>
