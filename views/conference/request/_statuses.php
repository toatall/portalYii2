<?php
/** @var yii\web\View $this */
/** @var app\models\conference\Conference $model */


use app\models\conference\Conference;
?>


<?php if ($model->status == Conference::STATUS_COMPLETE): ?>
    <div class="alert alert-success">
        <strong>Заявка согласована!</strong>
        <br />Автор: <?= $model->approve_author ?>
    </div>    
<?php endif; ?>

<?php if ($model->status == Conference::STATUS_DENIED): ?>
    <div class="alert alert-danger">
        <strong>Откзано в согласовании!</strong>
        <br />Автор: <?= $model->approve_author ?>
        <br />Причина: <?= $model->denied_text ?>        
    </div>    
    <div class="alert alert-info mt-2">
        Для повторного согласования перейдите в режим редактирования, выполните необходимые корректировки и сохраните заявку!
    </div>
<?php endif; ?>