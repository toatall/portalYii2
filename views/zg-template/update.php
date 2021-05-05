<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\zg\ZgTemplate */

$this->title = 'Update Zg Template: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Zg Templates', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="zg-template-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
