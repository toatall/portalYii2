<?php
/** @var yii\web\View $this */
/** @var app\models\Calendar $model */
/** @var string $date */
?>
<div class="calendar-create">
    
    <?= $this->render('_form', [
        'model' => $model,
        'date' => $date,
    ]) ?>

</div>
