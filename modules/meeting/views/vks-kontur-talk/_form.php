<?php
/** @var \yii\web\View $this */
/** @var \app\modules\meeting\models\VksKonturTalk $model */

use app\models\Organization;
use kartik\select2\Select2;
use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
?>

<div class="card card-body">

    <?php $form = ActiveForm::begin([
        'enableClientScript' => false,
        'encodeErrorSummary' => false,
    ]); ?>

    <?= $form->errorSummary($model) ?>



    <div class="row">
        <div class="col-12">
            <?= $form->field($model, 'org_code')->widget(Select2::class, [
                'data' => Organization::getDropDownList()
            ]) ?>
        </div>          
        <div class="col-12">
            <?= $form->field($model, 'theme')->textInput(['maxlength' => true]) ?>
        </div>          
    </div>

    <div class="row">
        <div class="col">
            <?= $this->render('/shared/form/_date_start', [
                'form' => $form,
                'model' => $model,
            ]) ?>
        </div>
        <div class="col">
            <?= $this->render('/shared/form/_duration', [
                'form' => $form,
                'model' => $model,
            ]) ?>
        </div>        
    </div>
    
    <div class="row">            
        <div class="col-12">
            <?= $form->field($model, 'note')->textarea(['rows' => 5]) ?>
        </div>
    </div>
    
    <hr />

    <div class="btn-group mt-2">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>           
    </div>

    <?php ActiveForm::end(); ?>

</div>