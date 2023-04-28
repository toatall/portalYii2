<?php
use yii\bootstrap5\Html;
use kartik\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\modules\bookshelf\models\BookCalendarSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="book-shelf-calendar-search mt-3">
       
    <div class="card bg-secondary text-white">
        <div class="card-header">
            <i class="fas fa-search"></i> Поиск
        </div>
        <div class="card-body">
            <?php $form = ActiveForm::begin([
                'action' => ['index'],
                'method' => 'get',                
                'options' => [
                    'data-pjax' => true,
                ],
            ]); ?>         
            <div class="row">
                <div class="col">
                    <?= $form->field($model, 'writer')->textInput(['class' => 'w-100', 'placeholder'=>$model->getAttributeLabel('writer')])->label(false) ?> 
                </div>
                <div class="col-auto">
                    <?= Html::submitButton('Поиск', ['class' => 'btn btn-primary']) ?> 
                </div>
            </div>                               
            <?php ActiveForm::end(); ?>
        </div>                
    </div>                    

</div>
