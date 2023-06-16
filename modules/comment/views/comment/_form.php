<?php

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;
use app\modules\comment\models\Emoji;
use yii\bootstrap5\Tabs;

/** @var yii\web\View $this */
/** @var app\modules\comment\models\Comment $model */
/** @var yii\widgets\ActiveForm $form */
/** @var string $textPlaceholder */
/** @var string $hash */
/** @var string $url */
/** @var string $idContainer */
/** @var string $idParent */


$userModel = Yii::$app->user->identity;
$idForm = uniqid('form-comment-');
$idPjaxComments = 'pjax-comment-' . $hash;
$idComments = 'container-comment-index-' . $hash;
?>

<div class="comment-form row">
    <div class="col">

        <?php $form = ActiveForm::begin([
            'id' => $idForm,            
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
                    <div class="input-group">
                        <?= Html::activeTextarea($model, 'text', ['class' => 'form-control rounded-left', 'rows' => 1]) ?>
                        <button class="btn btn-light border text-secondary dropdown-toggle" data-bs-auto-close="outside" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="far fa-smile-wink"></i>
                        </button>
                        <div class="dropdown-menu" style="width: 30rem;">
                            <div class="p-2">
                                <?= Tabs::widget([
                                    'id' => 'tabs-' . $idForm,
                                    'items' => Emoji::prepareDataAsTabs(),
                                    'itemOptions' => ['class' => 'overflow-auto', 'style' => 'height: 20em;'],
                                    'headerOptions' => ['style' => 'font-size: 1.5rem;'],
                                ]) ?>                             
                            </div>
                        </div>
                        <?= Html::submitButton('<i class="fas fa-paper-plane"></i> Отправить', [
                            'class' => 'btn btn-light border text-secondary',                            
                        ]) ?>
                    </div>                    
                </div>                
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

<?php 
$this->registerCss(<<<CSS
    .btn-smiley {
        font-size: 1.3rem;
        width: 2.5em;
    }        
CSS); 
$this->registerJS(<<<JS
        
    // вставка текста в input в выделеную позицию
    function typeInTextarea(el, newText) {
        let start = el.prop("selectionStart")
        let end = el.prop("selectionEnd")
        let text = el.val()
        console.log(el)
        let before = text.substring(0, start)
        let after  = text.substring(end, text.length)
        el.val(before + newText + after)
        el[0].selectionStart = el[0].selectionEnd = start + newText.length
        el.focus()
        return false
    }
    
    // вставка смайлика
    $('.btn-smiley').off();
    $('.btn-smiley').on('click', function() {
        $('button[data-bs-toggle="dropdown"]').dropdown('hide');
        const input = $(this).parents('.comment-form').find('[name="Comment[text]"]')
        typeInTextarea(input, $(this).html());
    });

    $('#$idForm [name="Comment[text]"]').keydown(function(e){
        if (e.keyCode == 13 && e.ctrlKey) {
            $(this).parents('form').submit()
        }      
    })

    // отправка формы комментария
    $('#$idForm').off('submit');
    $('#$idForm').on('submit', function() {
        const url = $(this).attr('action')
        const data = $(this).serialize()
        const container = $('#$idContainer')
        
        container.html('<span class="fa-1x"><i class="fas fa-circle-notch fa-spin"></i></span>')
       
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
            container.html('<div class="alert alert-danger">Url: ' + url + '<br /><strong>' + jqXHR.status + ' ' + jqXHR.statusText + '</strong></div>')
        })
        return false
    });

JS);
?>
