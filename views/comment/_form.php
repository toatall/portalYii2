<?php

use yii\bootstrap4\Html;
use yii\bootstrap4\ActiveForm;

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
            'options' => [
                //'data-pjax' => true,
            ],
        ]); ?>

        <div class="row">
            <div>
                <a href="/@<?= $userModel->username ?>" target="_blank">
                    <img src="<?= $userModel->getPhotoProfile() ?>" class="img-thumbnail rounded-circle ml-3" style="max-height: 35px;"
                    data-content="<?= $userModel->fio ?>" data-toggle="popover" data-trigger="hover" />
                </a>
            </div>
            <div class="col">
                <?= $form->field($model, 'text')->textarea([
                    'data-emojiable'=>'true',
                    'data-emoji-input'=>'image',
                    'placeholder'=>$textPlaceholder,
                    'class' => 'rounded',
                    'style'=>'height: auto;',
                ])->label(false) ?>
            </div>
            <div>
                <div class="align-self-center">
                    <?= Html::submitButton('<i class="fas fa-paper-plane"></i>', [
                        'class' => 'btn btn-primary btn-sm mr-3',
                        'data-content' => 'Отправить', 
                        'data-toggle' => 'popover',
                        'data-trigger' => 'hover',
                        'style' => 'height: 35px;',
                        //'pjax' => false,
                    ]) ?>
                </div>
            </div>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
    <div class="mr-4 align-items-center">
        <?php if (!$model->isNewRecord || ($model->isNewRecord && $model->id_parent != null)): ?>
            <?= Html::button('<span>&times;</span>', [
                'class' =>  'close btn-close-comment-edit',
                'data-container-id' => $idContainer,        
            ]) ?>
        <?php endif; ?>
    </div>

</div>
<?php $this->registerJS(<<<JS
    
    $('*').popover('hide');
    $('[data-toggle="popover"]').popover();
    
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
            $('*').popover('hide');
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
        right: 30px;
        top: 8px;
        font-size: 20px;
    }
    .emoji-wysiwyg-editor {
        height: auto !important;
        min-height: 35px !important;
        max-height: 10rem !important;
    }
CSS);
?>
