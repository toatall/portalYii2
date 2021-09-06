<?php
/** @var yii\web\View $this */
/** @var app\models\conference\Conference $model */

?>

<?= $this->render('_statuses', ['model' => $model]) ?>

<?= $this->render('../view', ['model' => $model]) ?>