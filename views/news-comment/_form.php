<?php
/** @var yii\web\View $this */
/** @var app\models\news\NewsComment $model */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
use app\assets\EmojiAsset;

EmojiAsset::register($this);
?>

<div class="card">
    <div class="card-body">
        <?php $form = ActiveForm::begin(['id' => 'comment-form']); ?>

        <?= $form->errorSummary($model); ?>
        
        <div class="lead emoji-picker-container" id="container-comment-id">
            <?= $form->field($model, 'comment')->textarea([                
                'data-emojiable'=>'true', 
                'data-emoji-input'=>'image', 
                'placeholder'=>'Комментарий', 
                'style'=>'height:150px;',
            ])->label(false) ?>
        </div>
            
        <div class="form-group">
            <?= Html::submitButton('Сохранить <i class="fas fa-circle-notch fa-spin" style="display: none;"></i>', ['class' => 'btn btn-success', 'id' => 'btn-form-save']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
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
    
    function runAjaxGetRequest(container) 
     {
        container.html('<img src="/img/loader_fb.gif" style="height: 100px;">');
        $.get(container.attr('data-ajax-url'))
        .done(function(data) {
            container.html(data);
        })
        .fail(function (jqXHR) {
            container.html('<div class="alert alert-danger">' + jqXHR.status + ' ' + jqXHR.statusText + '</div>');
        });    
    }
    
   
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