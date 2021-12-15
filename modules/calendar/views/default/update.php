<?php

/** @var yii\web\View $this */
/** @var app\modules\calendar\models\Calendar $model */
?>
<div class="calendar-update">

    <?= $this->render('_form', [
        'model' => $model,
        'date' => $model->date,
    ]) ?>

</div>
