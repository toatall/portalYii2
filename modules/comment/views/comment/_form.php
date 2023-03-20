<?php

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Comment $model */
/** @var yii\widgets\ActiveForm $form */
/** @var string $textPlaceholder */
/** @var string $hash */
/** @var string $url */
/** @var string $idContainer */
/** @var string $idParent */

$userModel = Yii::$app->user->identity;
$idForm = 'form-comment-' . md5(time());
$idPjaxComments = 'pjax-comment-' . $hash;
$idComments = 'container-comment-index-' . $hash;
?>

<div class="comment-form row">
    <div class="col">

        <?php $form = ActiveForm::begin([
            'id' => $idForm,
            'options' => [],
        ]); ?>
        
        <div class="row align-items-start">
            <div style="width: auto;">
                <a href="/@<?= $userModel->username ?>" target="_blank">
                    <img src="<?= $userModel->getPhotoProfile() ?>" class="img-thumbnail ml-3" style="height: 2.3rem;"
                    data-bs-content="<?= $userModel->fio ?>" data-bs-toggle="popover" data-bs-trigger="hover" />
                </a>
            </div>
            <div class="col">
                <div class="text-left">
                    <p class="lead emoji-picker-container">
                        <?= Html::activeTextarea($model, 'text', [
                            'class' => 'form-control textarea-control rounded',
                            'rows' => 1,
                            'placeholder' => $textPlaceholder,
                            'data-emojiable' => 'true',
                        ]) ?>
                    </p>
                </div>
            </div>            
            <div style="width: 5rem;" class="">
                <?= Html::submitButton('<i class="fas fa-paper-plane"></i>', [
                    'class' => 'btn btn-primary btn-sm mr-3',
                    'data-bs-content' => 'Отправить', 
                    'data-bs-toggle' => 'popover',
                    'data-bs-trigger' => 'hover',
                    'style' => 'height: 35px;',
                ]) ?>
            </div>
        </div>

        <?php ActiveForm::end(); ?>        

    </div>
    <div class="col-auto pt-1">
        <?php if (!$model->isNewRecord || ($model->isNewRecord && $model->id_parent != null)): ?>
            <?= Html::button('', [
                'class' =>  'btn-close btn-close-comment-edit',
                'data-container-id' => $idContainer,
            ]) ?>
        <?php endif; ?>
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

    $('#$idForm').off('submit');
    $('#$idForm').on('submit', function() {        
        var url = $(this).attr('action');
        var data = $(this).serialize();
        var container = $('#$idContainer');
        
        container.html('<span class="fa-1x"><i class="fas fa-circle-notch fa-spin"></i></span>');
       
        $.ajax({
            url: url,
            data: data,
            method: 'post'
        })
        .done(function(data) {
            $('.comment-form *').popover('hide');
            if (data.resultSave) {              
                ajaxLoad('$idComments');
            }
            container.html(data.content);           
        })
        .fail(function(jqXHR) {
            container.html('<div class="alert alert-danger">Url: ' + url + '<br /><strong>' + jqXHR.status + ' ' + jqXHR.statusText + '</strong></div>');
        });

        return false;
    });

JS);
$this->registerCss(<<<CSS
     .emoji-picker-icon {
        font-size: 1.3rem;
    }
CSS);
?>
