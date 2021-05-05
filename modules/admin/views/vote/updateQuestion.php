<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model \app\models\vote\VoteQuestion */

$this->title = 'Изменить голосование: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Голосование', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->main->name, 'url' => ['index-question', 'idMain'=>$model->id_main]];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view-question', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Изменить';
?>
<div class="vote-main-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_formQuestion', [
        'model' => $model,
    ]) ?>

</div>
