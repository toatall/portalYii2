<?php

use app\modules\bookshelf\models\BookShelfPlace;
use kartik\select2\Select2;
use yii\bootstrap5\Html;
use kartik\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

/** @var yii\web\View $this */
/** @var app\modules\bookshelf\models\BookShelfSearch $model */
/** @var kartik\widgets\ActiveForm $form */
?>

<div class="book-shelf-search mb-3">
    
        
    <div class="card bg-secondary text-white">
        <div class="card-header">
            <i class="fas fa-search"></i> Поиск
        </div>
        <div class="card-body">           
            <?php $form = ActiveForm::begin([
                'action' => ['index'],
                'method' => 'get',
                // 'type' => ActiveForm::TYPE_INLINE,
                // 'fieldConfig' => [
                //     'options' => [
                //         'class' => 'form-group mb-3 mr-2 col',
                //     ],
                // ],
                'options' => [
                    'data-pjax' => true,
                ],
            ]); ?>
            <div class="row">
                <div class="col">
                    <?= $form->field($model, 'searchText')
                        ->textInput(['class' => 'w-100', 'placeholder'=>$model->getAttributeLabel('searchText')])
                        ->label(false) 
                    ?>
                </div>
                <div class="col-3">
                    <?= $form->field($model, 'place')->widget(Select2::class, [
                        'data' => ['' => 'Все'] + ArrayHelper::map(BookShelfPlace::find()->all(), 'place', 'place'),
                        'pluginOptions' => ['width' => '100%'],
                        'options' => ['placeholder'=>$model->getAttributeLabel('place')],
                    ])->label(false) ?>
                </div>
                <div class="col-auto">
                    <?= $form->field($model, 'searchIsNew')->checkbox([
                        'custom' => true, 'switch' => true,
                    ]) ?>
                </div>
                <div class="col-auto">
                    <?= Html::submitButton('Поиск', ['class' => 'btn btn-primary']) ?>  
                </div>               
            </div>
            <?php ActiveForm::end(); ?>            
        </div>                
    </div>            
     
    
</div>
