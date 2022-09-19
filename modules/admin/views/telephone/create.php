<?php

use yii\bootstrap5\Html;

/** @var yii\web\View $this */
/** @var app\models\Telephone $model */

$this->title = 'Создание справочника';
$this->params['breadcrumbs'][] = ['label' => 'Телефонные справочники', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="telephone-create">

    <h1 class="display-5 border-bottom">
        <?= Html::encode($this->title) ?>
    </h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
