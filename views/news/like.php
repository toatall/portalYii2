<?php
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $liked boolean */
/* @var $model \app\models\news\News */
?>

<button class="btn btn-<?= $liked ? 'primary' : 'light' ?>" id="btn-like">
    <i class="<?= $liked ? 'fas' : 'far' ?> fa-thumbs-up" style="font-size: 18px;"></i> Мне нравится <strong><?= $model->count_like ?></strong>
    <i class="fas fa-circle-notch fa-spin" style="display: none;" id="i-load"></i>
</button>

<script type="text/javascript">
   
    $('#btn-like').click(function() {
        
        $(this).attr('disabled', 'disabled');
        $('#i-load').show();
        
        $.ajax({
            url: '<?= Url::to(['news/like', 'idNews'=>$model->id]) ?>',
            method: 'post',
            data: { setLike: 1 },
            success: function(data) {
                $('#container-like').html(data);
            },
            complete: function() {
                $('#btn-like').removeAttr('disabled');
                $('#i-load').hide();
            },
            error: function(jqXHR) {
                $('#container-like').html('<span class="alert-danger">' + jqXHR.status + ' ' + jqXHR.statusText + '</span>');
            }
        });

    });
    
</script>