<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\conference\VksFns */

$this->title = 'Изменить ВКС с ФНС: #' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'ВКС с ФНС', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Изменить';
?>
<div class="vks-fns-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
