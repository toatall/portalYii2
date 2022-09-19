<?php

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;
use kartik\widgets\DatePicker;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use app\models\department\Department;
use kartik\widgets\FileInput;
use app\assets\fancybox\FancyboxAsset;

FancyboxAsset::register($this);


/** @var yii\web\View $this */
/** @var app\modules\events\models\ContestArts $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="contest-arts-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="alert alert-info">
        Даты необходимо указывать с учетом того, что время в них представляется как 0:00:00. <br />
        Например, чтобы задать диапазон показа с 01.04.2021 по 02.04.2021 (включительно), 
        то нужно выбрать "дату показа с" = 01.04.2021, "дату показа по" = 03.04.2021
    </div>
    
    <?= $form->field($model, 'date_show')->widget(DatePicker::class, [
        'pluginOptions' => [
            'todayHighlight' => true,
            'todayBtn' => true,
            'autoclose' => true,            
        ],
    ]) ?>
    
    <?= $form->field($model, 'date_show_2')->widget(DatePicker::class, [
        'pluginOptions' => [
            'todayHighlight' => true,
            'todayBtn' => true,
            'autoclose' => true,            
        ],
    ]) ?>

    <?= $form->field($model, 'department_name')->widget(Select2::class, [
        'data' => ArrayHelper::map(Department::find()->all(), 'department_name', 'department_name'),
    ]) ?>
    
     <?= $form->field($model, 'department_ad_group')->textInput(['maxlength' => true]) ?>

    <div class="panel panel-default">
        <div class="panel-heading">Оригинал</div>
        <div class="panel-body">
           
            <?= $form->field($model, 'imageOriginal')->widget(FileInput::class, [
                'options' => [
                    'accept' => 'images/*',                   
                ],
                'pluginOptions' => [
                    'showUpload' => false,
                    'showPreview' => false,
                ],
            ]) ?>
            <?php if (!$model->isNewRecord): ?>
                <div class="panel panel-default">
                    <div class="panel-heading">Отметьте для удаления</div>
                    <div class="panel-body">                    
                        <?= Html::a(Html::img($model->image_original, ['class' => 'thumbnail', 'style' => 'width: 5em']), $model->image_original, ['class' => 'fancybox']) ?>
                        <?= $form->field($model, 'delImageOriginal')->checkbox() ?>
                    </div>
                </div>                
            <?php endif; ?>
            <?= $form->field($model, 'image_original_author')->textInput(['maxlength' => true]) ?> 
            <?= $form->field($model, 'image_original_title')->textInput(['maxlength' => true]) ?> 
            <?= $form->field($model, 'description_original')->textarea(['rows' => 4]) ?>
            <?= $form->field($model, 'imageQrCode')->widget(FileInput::class, [
                'options' => [
                    'accept' => 'images/*',                   
                ],
                'pluginOptions' => [
                    'showUpload' => false,
                    'showPreview' => false,
                ],
            ]) ?>            
            <?php if (!$model->isNewRecord): ?>
            <div class="panel panel-default">
                <div class="panel-heading">Отметьте для удаления</div>
                <div class="panel-body">                    
                    <?= Html::a(Html::img($model->qr_code_file, ['class' => 'thumbnail', 'style' => 'width: 5em']), $model->qr_code_file, ['class' => 'fancybox']) ?>
                    <?= $form->field($model, 'delImageQrCode')->checkbox() ?>
                </div>
            </div>
            <?php endif; ?>            
        </div>
    </div>
    
    <div class="panel panel-default">
        <div class="panel-heading">Репродукция</div>
        <div class="panel-body">                                       
            <?= $form->field($model, 'imageReproduced')->widget(FileInput::class, [
                'options' => [
                    'accept' => 'images/*',                   
                ],
                'pluginOptions' => [
                    'showUpload' => false,
                    'showPreview' => false,
                ],
            ]) ?>            
            <?php if (!$model->isNewRecord): ?>
                <div class="panel panel-default">
                    <div class="panel-heading">Отметьте для удаления</div>
                    <div class="panel-body">                    
                        <?= Html::a(Html::img($model->image_reproduced, ['class' => 'thumbnail', 'style' => 'width: 5em']), $model->image_reproduced, ['class' => 'fancybox']) ?>
                        <?= $form->field($model, 'delImageReproduced')->checkbox() ?>
                    </div>
                </div>                
            <?php endif; ?>
            <?= $form->field($model, 'image_reproduced_title')->textInput(['maxlength' => true]) ?> 
            <?= $form->field($model, 'description_reproduced')->textarea(['rows' => 4]) ?>
        </div>
    </div>

    <div class="btn-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
        <?= Html::a('Отмена', ['admin'], ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<?php $this->registerJs(<<<JS
     $('.fancybox').fancybox();   
JS
); ?>