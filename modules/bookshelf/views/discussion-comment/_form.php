<?php

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;
use app\assets\EmojiAsset;

EmojiAsset::register($this);

/** @var yii\web\View $this */
/** @var app\modules\bookshelf\models\BookShelfDiscussionComment $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="book-shelf-discussion-comment-form">

    <?php $form = ActiveForm::begin(['id' => 'comment-form']); ?>  
    
    <?= $form->errorSummary($model) ?>
    
    <div class="lead emoji-picker-container" id="container-comment-id">
        <?= $form->field($model, 'comment')->textarea([
            'style'=>'height:20rem;',
            'data-emojiable'=>'true', 
            'data-emoji-input'=>'image', 
            'placeholder'=>'Комментарий', 
        ])->label(false) ?>
    </div>
    <div class="form-group">
        <?= Html::submitButton('Сохранить <i class="fas fa-circle-notch fa-spin" style="display: none;"></i>', ['class' => 'btn btn-success', 'id' => 'btn-form-save']) ?>
    </div>
    <?php ActiveForm::end(); ?>
    

</div>
<?php $this->registerJS(<<<JS
    
    // Emoji
    $(function () {
        // Initializes and creates emoji set from sprite sheet        
        window.emojiPicker = new EmojiPicker({
            emojiable_selector: '[data-emojiable=true]',
            assetsPath: '/public/vendor/emoji-picker/lib/img/',
            popupButtonClasses: 'far fa-smile text-primary'
        });
        window.emojiPicker.discover();        
    });
        
    $('#comment-form').on('afterValidate', function(event, fields, errors) {
        if (errors.length > 0) {
            return false;
        }
        
        $('#btn-form-save').attr('disabled', 'disabled');
        $('#btn-form-save i').show();
        
        let comment_form_container = '#container-comment-form'; 
        let form = $('#comment-form');
        
        // div to textarea
        $('#container-comment-id textarea').val($('#container-comment-id div.form-control').html());
        
        $.ajax({
            url: form.attr('action'),
            method: 'post',            
            data: form.serialize(),
            success: function(data) {
                if (data.toUpperCase() == "OK") {
                    $('#btn-comment-refresh').trigger('click');
                    $('#tab-comments a[data-tab="index"]').tab('show');
                    runAjaxGetRequest($('#container-comment-form')); 
                }
                else {
                    $(comment_form_container).html(data);
                }
            },
            error: function(jqXHR) {
                $(comment_form_container).html('<div class="alert alert-danger">' + jqXHR.status + ' ' + jqXHR.statusText + '</div>');
            },
            complete: function() {
                $('#btn-form-save').removeAttr('disabled');
            },
        });        
    });
    
    $('#comment-form').submit(function(e) {
        return false;        
    });     
        
JS
);
?>