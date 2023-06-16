<?php
/** @var yii\web\View $this */
/** @var array $comments */
/** @var string $hash */
/** @var string $url */
/** @var string $modelName */
/** @var int $modelId */
?>

<div class="mt-3">

    <?php if ($comments && count($comments) > 0): ?>
        <?php foreach($comments as $comment): ?>

            <?= $this->render('_comment', [
                'model'=>$comment['modelComment'],
                'hash'=>$hash,
                'url'=>$url,
                'modelName' => $modelName,
                'modelId' => $modelId,
            ]) ?>
            
            <?php if ($comment['subComment'] && count($comment['subComment']) > 0): ?>
                <div class="ms-5">
                    <?php foreach($comment['subComment'] as $commentSub): ?>
                        <?= $this->render('_comment', [
                            'model'=>$commentSub['modelComment'],
                            'hash'=>$hash,
                            'url'=>$url,
                            'modelName' => $modelName,
                            'modelId' => $modelId,
                        ]) ?>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

        <?php endforeach; ?>
    <?php else: ?>
        <!-- <div class="alert lead">Нет комментариев</div> -->
    <?php endif; ?>

</div>

<?php
$this->registerJs(<<<JS
   
    // ссылка добавления комментария
    $('.link-create').on('click', function() {
        let container = $('#' + $(this).data('container'));
        let url = $(this).attr('href');

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
        let parentDiv = $(this).parent('div.reply');
        let childDiv = parentDiv.children('div');
        childDiv.toggle('show');
        $(this).remove();
        return false;
    });

    // закрытие формы редактирования комментария (только для ответов и при изменении комментария)
    $(document).on('click', '.btn-close-comment-edit', function() {
        let con = $('#' + $(this).data('container-id'));
        con.html('');
        return false;
    });

    // привязка кнопок изменения комментария
    $('.link-update').on('click', function() {

        let container = $('#' + $(this).data('container'));
        let url = $(this).attr('href');

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

    // привязка кнопок удаления комментария
    $('.link-delete').on('click', function() {
        
        if (!confirm('Вы уверены, что хотите удалить?')) {
            return false;
        }

        let container = $(this).data('container');
        let url = $(this).attr('href');

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
