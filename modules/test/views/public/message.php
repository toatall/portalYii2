<?php
/** @var yii\web\View $this */

use yii\bootstrap4\Html;

/** @var string $message */
/** @var string $typeMessage */
/** @var app\modules\test\models\Test $model */

if (!isset($typeMessage)) {
    $typeMessage = 'alert-info';
}
?>

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <?= Html::a('Главная', ['/test/']) ?>
        </li>
        <li class="breadcrumb-item">
            <?= $model->name ?>
        </li>
    </ol>
</nav>

<div class="alert <?= $typeMessage ?>">
    <strong><?= $message ?></strong>    
    <br /><br />
    <?= Html::a('Назад', ['/test'], ['class' => 'btn btn-primary']) ?>
</div>