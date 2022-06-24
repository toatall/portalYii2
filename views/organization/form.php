<?php

/** @var yii\web\View $this */

use kartik\file\FileInput;
use mihaildev\ckeditor\CKEditor;
use mihaildev\elfinder\ElFinder;
use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;

?>

<?php $form = ActiveForm::begin([ 
    'options' => [
        'class' => 'mv-form',
    ],
]); ?>

    <?= Html::errorSummary($model, ['class' => 'alert alert-danger']) ?>

    <div class="font-20px">
    <?= $form->field($model, 'description')->widget(CKEditor::class, [
        'editorOptions' => ElFinder::ckeditorOptions('elfinder', [
            'preset' => 'full',
            'fontSize_sizes' => '20/20px;22/22px;24/24px;26/26px;28/28px;36/36px;48/48px;72/72px',
        ]),        
    ]) ?>
    </div>

    <div class="card">
        <div class="card-header">Загрузка изображений</div>
        <div class="card-body">
            <?= $form->field($model, 'uploadImages[]')->widget(FileInput::class, [
                'options' => [
                    'accept' => 'images/*',
                    'multiple' => true,
                ],
                'pluginOptions' => [
                    'showUpload' => false,
                ],
            ]) ?>
            <?php if (!$model->isNewRecord && count($model->getImages())): ?>
                <hr />
                <?= $form->field($model, 'deleteImages', [])
                    ->checkboxList($model->getImages(), [
                        'item' => function($index, $label, $name, $checked, $value) {
                            return Html::tag('div', 
                                Html::label(
                                    Html::checkbox($name, false, ['value'=>$value]) . ' ' . basename($label)
                                    . ' ' . Html::a('(просмотр)', \Yii::$app->storage->getFileUrl($label), ['class' => 'gallery-item', 'target' => '_blank'])
                                ), 
                            ['class'=>'checkbox']);                            
                        },
                    ])->label(null, ['class'=>'font-weight-bolder']) ?>
            <?php endif; ?>
        </div>
    </div>

    <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary mt-3']) ?>

<?php ActiveForm::end(); ?>
<?php $this->registerJs(<<<JS
    $('.checkbox').popover();

JS); ?>