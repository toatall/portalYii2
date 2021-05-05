<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\vote\VoteMain */

$this->title = 'Создание голосования';
$this->params['breadcrumbs'][] = ['label' => 'Голосование', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="vote-main-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
