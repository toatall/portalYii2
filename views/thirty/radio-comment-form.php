<?php
/** @var \yii\web\View $this */
/** @var \app\models\thirty\ThirtyRadioComment $model */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
use app\assets\EmojiAsset;

EmojiAsset::register($this);

?>
<div class="panel panel-default">
    <div class="panel-body">
        <?php $form = ActiveForm::begin(['id' => 'radio-comment-form']); ?>

        <?= $form->errorSummary($model); ?>
        <div class="lead emoji-picker-container" id="container-radio-comment-id">
            <?= $form->field($model, 'comment')->textarea(['data-emojiable'=>'true', 'data-emoji-input'=>'image', 'placeholder'=>'Комментарий', 'style'=>'height:150px;'])->label(false) ?>
        </div>
        <div class="form-group">
            <?= Html::submitButton('Сохранить <i class="fas fa-circle-notch fa-spin" style="display: none;"></i>', ['class' => 'btn btn-success', 'id' => 'btn-radio-form-save']) ?>
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
            popupButtonClasses: 'far fa-smile text-secondary'
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
    
    $('#radio-comment-form').off('submit').on('submit', function() {
        
        $('#btn-radio-form-save').attr('disabled', 'disabled');
        $('#btn-radio-form-save i').show();
        
        let comment_form_container = '#radio-comment-form-container'; 
        let form = $('#radio-comment-form');
        
        $.ajax({
            url: form.attr('action'),
            method: 'post',            
            data: form.serialize(),
            success: function(data) {
                if (data === "OK") {
                    $('#btn-radio-comment-refresh').trigger('click');
                    $('#tab-comments-radio a[data-tab="index"]').tab('show');
                    runAjaxGetRequest($('#radio-comment-form-container')); 
                }
                else {
                    $(comment_form_container).html(data);
                }
            },
            error: function(jqXHR) {
                $(comment_form_container).html('<div class="alert alert-danger">' + jqXHR.status + ' ' + jqXHR.statusText + '</div>');
            },
            complete: function() {
                $('#btn-radio-form-save').removeAttr('disabled');
            },
        });
        
        return false; 
    });
JS
);
?>