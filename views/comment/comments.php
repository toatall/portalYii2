<?php

/** @var yii\web\View $this */

use yii\bootstrap4\Html;

/** @var array $comments */
/** @var string $hash */
/** @var string $url */

?>

<div class="mt-3">

    <?php if ($comments && count($comments) > 0): ?>
        <div class="card card-body">
        <?php foreach($comments as $comment): ?>

            <?= $this->render('_comment', [
                'model'=>$comment['modelComment'],
                'hash'=>$hash,
                'url'=>$url,
            ]) ?>
            
            <?php if ($comment['subComment'] && count($comment['subComment']) > 0): ?>
                <div class="ml-5">
                    <?php foreach($comment['subComment'] as $commentSub): ?>
                        <?= $this->render('_comment', [
                            'model'=>$commentSub['modelComment'],
                            'hash'=>$hash,
                            'url'=>$url,
                        ]) ?>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

        <?php endforeach; ?>
        </div>
    <?php else: ?>
        <!-- <div class="alert lead">Нет комментариев</div> -->
    <?php endif; ?>

</div>

<?php 
//$formData = Html::csrfMetaTags();
//echo $formData;
$this->registerJs(<<<JS
    // ссылка добавления комментария
    $('.link-create').on('click', function() {
        var container = $('#' + $(this).data('container'));
        var url = $(this).attr('href');

        container.html('<span class="fa-1x"><i class="fas fa-circle-notch fa-spin"></i></span>');
        
        $.get(url)
        .done(function(data) {            
            container.html(data.content);
        })
        .fail(function(jqXHR) {
            container.html('<div class="alert alert-danger">Url: ' + url + '<br /><strong>' + jqXHR.status + ' ' + jqXHR.statusText + '</strong></div>');
        });

        return false;
    });

    // ссылка перенаправления коментария
    $('.link-reply').on('click', function() {
        var parentDiv = $(this).parent('div.reply');
        var childDiv = parentDiv.children('div');
        childDiv.toggle('show');
        $(this).remove();
        return false;
    });

    // закрытие формы редактирования комментария (только для ответов и при изменении комментария)
    $(document).on('click', '.btn-close-comment-edit', function() {
        var con = $('#' + $(this).data('container-id'));
        con.html('');
        return false;
    });

    $('.link-update').on('click', function() {

        var container = $('#' + $(this).data('container'));
        var url = $(this).attr('href');

        container.html('<span class="fa-1x"><i class="fas fa-circle-notch fa-spin"></i></span>');
        
        $.get(url)
        .done(function(data) {            
            container.html(data.content);
        })
        .fail(function(jqXHR) {
            container.html('<div class="alert alert-danger">Url: ' + url + '<br /><strong>' + jqXHR.status + ' ' + jqXHR.statusText + '</strong></div>');
        });

        return false;
    });

    $('.link-delete').on('click', function() {
        
        if (!confirm('Вы уверены, что хотите удалить?')) {
            return false;
        }

        var container = $(this).data('container');
        var url = $(this).attr('href');

        $.ajax({
            url: url,            
            method: 'post'
        })
        .done(function(data) {           
            if (data.resultDeleted) {              
                ajaxLoad(container);
            }
            else {                
                alert('При удалении произошла ошибка!');
            }
        })
        .fail(function(jqXHR) {
            container.html('<div class="alert alert-danger">Url: ' + url + '<br /><strong>' + jqXHR.status + ' ' + jqXHR.statusText + '</strong></div>');
        });

        return false;
    });

JS); ?>