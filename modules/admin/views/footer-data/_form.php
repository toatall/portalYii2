<?php

use eluhr\aceeditor\widgets\AceEditor;
use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var app\modules\admin\models\FooterData $model */
/** @var app\modules\admin\models\FooterType $modelType */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="footer-type-form">

    <?php $form = ActiveForm::begin([
        'options' => [
            'data-pjax' => true,
        ],
    ]); ?>

    <?= $form->errorSummary($model) ?>

    <?= $form->field($model, 'url')->textInput(['maxlength' => true]) ?>
    
    <?= $form->field($model, 'text')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'target')->dropDownList([
        '' => '', 
        '_blank' => '_blank (отркыть ссылку в новом окне)',
    ]) ?>
    
    <div class="card">
        <div class="card-header">
            <?= $model->getAttributeLabel('options') ?>
        </div>
        <div class="card-body">
            <div class="alert alert-info">
                Задаются настройки ссылки (преимущественно аттрибуты <kbd>class</kbd>, <kbd>style</kbd>)
                <br/>
                Например:
                <br />
                <code>
<pre>
{
    "class": "text-dark",
    "style": "margin-top: 2px;"
}
</pre>
                </code>                
            </div> 
            <?= $form->field($model, 'options')->widget(AceEditor::class, [
                'mode' => 'json',        
                'id' => 'ace_editor_footer_data_form__' . time(),
                'container_options' => [
                    'id' => 'ace_editor_footer_data_form__' . time(),
                    'style' => 'width: 100%; min-height: 10rem'
                ],
            ])->label(false) ?>  
            
        </div>
    </div>
     
    <div class="btn-group mt-3">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success btn-sm']) ?>
        <?= Html::a('Отмена', ['/admin/footer-data/index', 'idType' => $modelType->id], [
            'id' => 'btn-cancel',
            'class' => 'btn btn-secondary btn-sm', 
            'pjax' => 1,
        ]) ?>
    </div>

<?php 
$url = Url::to(['index', 'idType'=>$modelType->id]);
// если все прошло успешно, то переадресовываем на главную (index)
if (Yii::$app->session->getFlash('success')): 
    $this->registerJs(<<<JS
        $.pjax.reload({container: '#pjax-footer-data-gridview', url: '$url', push: false, replace: false, replaceRedirect: false })
    JS);
endif; 

$this->registerCss(<<<CSS
    .ace_editor {
        font-size: 1rem;
    }
CSS);
?>

    <?php ActiveForm::end(); ?>

</div>
