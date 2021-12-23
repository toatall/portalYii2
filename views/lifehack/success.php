<?php
/** @var yii\web\View $this */
/** @var string $message */
?>
<div class="alert alert-success">
    <?= $message ?>
</div>
<button class="btn btn-primary" id="btn001" data-dismiss="modal">Закрыть</button>
<?php  $this->registerJs(<<<JS
    $('#btn001').on('click', function() {
        modalViewer.closeModal();
        $.pjax.reload({ container: '#pjax-lifehack-index' });                
    });
JS); ?>