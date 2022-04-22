<?php

/** @var yii\web\View $this */
/** @var app\models\Comment $model */

?>
<div class="comment-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
