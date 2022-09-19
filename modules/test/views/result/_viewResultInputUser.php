<?php

use yii\bootstrap5\Html;

/** @var yii\web\View $this */

?>
<div class="alert alert-info">
    Тест завершен!
    <br /><?= Html::a('На главную страницу', ['/test/test/index'], ['class' => 'btn btn-primary']) ?>
</div>