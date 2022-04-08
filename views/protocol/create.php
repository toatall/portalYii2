<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Protocol $model */

$this->title = 'Создание';
$this->params['breadcrumbs'][] = ['label' => 'Протоколы ФНС', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="protocol-create">

    <h1 class="display-4 border-bottom">
        <?= Html::encode($this->title) ?>
    </h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
