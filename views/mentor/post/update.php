<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model \app\models\mentor\MentorPost */

$this->title = 'Изменение новости: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Наставничество', 'url' => ['mentor/normative']];
$this->params['breadcrumbs'][] = ['label' => $model->mentorWays->name, 'url' => ['mentor/way', 'id'=>$model->mentorWays->id]];
$this->params['breadcrumbs'][] = 'Изменить';
?>
<div class="news-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
