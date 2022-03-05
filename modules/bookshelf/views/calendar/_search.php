<?php

use yii\bootstrap4\Html;
use kartik\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\modules\bookshelf\models\BookCalendarSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="book-shelf-calendar-search">

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
                        'type' => ActiveForm::TYPE_INLINE,
                        'fieldConfig' => [
                            'options' => [
                                'class' => 'form-group mb-3 mr-2 col-10',
                            ],
                        ],
                        'options' => [
                            'data-pjax' => true,
                        ],
                    ]); ?>                    
                    <?= $form->field($model, 'writer')->textInput(['class' => 'w-100']) ?>                                           
                    <div class="btn-group form-group mb-3">
                        <?= Html::submitButton('Поиск', ['class' => 'btn btn-primary']) ?>
                        <?= Html::resetButton('Сброс', ['class' => 'btn btn-outline-secondary']) ?>
                    </div>
                    <?php ActiveForm::end(); ?>
                </div>                
            </div>            
        </div>        
    </div>

</div>
