<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\conference\VksUfns */

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
