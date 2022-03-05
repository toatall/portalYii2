<?php

use app\modules\bookshelf\models\BookShelfPlace;
use kartik\select2\Select2;
use yii\bootstrap4\Html;
use kartik\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

/** @var yii\web\View $this */
/** @var app\modules\bookshelf\models\BookShelfSearch $model */
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
                        'type' => ActiveForm::TYPE_INLINE,
                        'fieldConfig' => [
                            'options' => [
                                'class' => 'form-group mb-3 mr-2 col-3',
                            ],
                        ],
                        'options' => [
                            'data-pjax' => true,
                        ],
                    ]); ?>                    
                    <?= $form->field($model, 'searchText')->textInput(['class' => 'w-100']) ?>
                    <?= $form->field($model, 'place')->widget(Select2::class, [
                        'data' => ['' => 'Все'] + ArrayHelper::map(BookShelfPlace::find()->all(), 'place', 'place'),
                        'pluginOptions' => ['width' => '100%'],
                    ]) ?>
                    <?= $form->field($model, 'searchIsNew')->checkbox([
                        'custom' => true, 'switch' => true,
                    ]) ?>
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
