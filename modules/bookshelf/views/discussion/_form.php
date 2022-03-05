<?php

use yii\bootstrap4\Html;
use yii\bootstrap4\ActiveForm;
use app\assets\EmojiAsset;
use mihaildev\ckeditor\CKEditor;
use mihaildev\elfinder\ElFinder;

/** @var yii\web\View $this */
/** @var app\modules\bookshelf\models\BookShelfDiscussion $model */
/** @var yii\widgets\ActiveForm $form */

EmojiAsset::register($this);
?>

<div class="book-shelf-discussion-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->errorSummary($model) ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
    
    <?= $form->field($model, 'note')->widget(CKEditor::class, [
        'editorOptions' => ElFinder::ckeditorOptions('elfinder', [
            'preset' => 'full',
            'fontSize_sizes' => '20/20px;22/22px;24/24px;26/26px;28/28px;36/36px;48/48px;72/72px',      
            'contentsCss' => 'body { font-size: 20px; font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial; padding: 5px 15px; }',                
        ]),            
    ]) ?>    
    
    <div class="btn-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
        <?= Html::a('Отмена', ['index'], ['class' => 'btn btn-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
