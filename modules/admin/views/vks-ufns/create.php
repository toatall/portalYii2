<?php

use yii\bootstrap4\Html;

/** @var yii\web\View $this */
/** @var app\models\conference\VksUfns $model */

$this->title = 'Создать';
$this->params['breadcrumbs'][] = ['label' => 'ВКС с УФНС', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="vks-ufns-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
