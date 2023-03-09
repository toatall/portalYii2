<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\modules\beginner\models\Beginner $model */

$this->title = 'Update Beginner: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Beginners', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="beginner-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
