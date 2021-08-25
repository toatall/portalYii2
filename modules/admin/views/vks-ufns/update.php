<?php

use yii\bootstrap4\Html;

/** @var yii\web\View $this */
/** @var app\models\conference\VksUfns $model */

$this->title = 'Изменить ВКС с УФНС: #' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'ВКС с УФНС', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Изменить';
?>
<div class="vks-ufns-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
