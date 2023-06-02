<?php
/** @var \yii\web\View $this */
/** @var \app\modules\meeting\models\VksFns $model */
/** @var \app\modules\meeting\models\VksExternalExtension $modelExtension */

use app\modules\meeting\models\VksExternalExtension;
use kartik\datetime\DateTimePicker;
use kartik\select2\Select2;
use kartik\touchspin\TouchSpin;
use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
?>

<div class="card card-body">

    <?php $form = ActiveForm::begin([
        'enableClientScript' => false,
        'encodeErrorSummary' => false,
    ]); ?>

    <?= $form->errorSummary([$model, $modelExtension]) ?>

    <div class="row">
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
        <div class="col">
            <?= $this->render('/shared/form/_place', [
                'form' => $form,
                'model' => $model,
            ]) ?>                
        </div>
    </div>
    
    <div class="row">    
        <div class="col-4">
            <?= $form->field($model, 'responsible')->textInput(['maxlength' => true]) ?>
        </div>             
        <div class="col-4">
            <?= $form->field($modelExtension, 'format_holding')->widget(Select2::class, [
                'data' => $modelExtension->dropDownListFormatHolding(),
            ]) ?>
        </div>
        <div class="col-4">
            <?= $form->field($modelExtension, 'person_head')->textInput(['maxlength' => true]) ?>
        </div>
    </div>

    <div class="row">
        <div class="col">
            <?= $form->field($modelExtension, 'members_count')->widget(TouchSpin::class, []) ?>
        </div>
        <div class="col">
            <?= $form->field($modelExtension, 'members_count_ufns')->widget(TouchSpin::class, []) ?>
        </div>
        <div class="col">
            <?= $form->field($modelExtension, 'material_translation')->widget(Select2::class, [
                'data' => VksExternalExtension::dropDownListMaterialTranslation(),
            ]) ?>
        </div>
    </div>

    <hr />

    <div class="row">
        <div class="col">
            <?= $form->field($modelExtension, 'link_event')->textInput() ?>
        </div>
    </div>

    <div class="row">
        <?= $form->field($modelExtension, 'is_connect_vks_fns')->checkbox([
            'template' => '<div class="form-check form-switch">{input} {label}</div><div>{error}</div>',
        ]) ?>
    </div>

    <div class="row">
        <div class="col">
            <?= $form->field($modelExtension, 'platform')->textInput() ?>
        </div>
        <div class="col">
            <?= $form->field($modelExtension, 'full_name_support_ufns')->textInput() ?>
        </div>
        <div class="col-3">
            <?= $form->field($modelExtension, 'date_test_vks_str')->widget(DateTimePicker::class, [
                'pluginOptions' => [
                    'todayHighlight' => true,
                    'todayBtn' => true,
                    'autoclose' => true,
                    'format' => 'dd.mm.yyyy hh:ii'
                ],
            ]) ?>
        </div>
        <div class="col-2">
            <?= $form->field($modelExtension, 'count_notebooks')->widget(TouchSpin::class, []) ?>
        </div>
    </div>

    <hr />

    <div class="row">
        <div class="col-12">
            <?= $form->field($model, 'members_organization')->textarea(['rows' => 5]) ?>
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