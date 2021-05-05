<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model \app\models\mentor\MentorPost */
/* @var $modelWay \app\models\mentor\MentorWays */

$this->title = 'Новость';
$this->params['breadcrumbs'][] = ['label' => 'Наставничество', 'url' => ['mentor/normative']];
$this->params['breadcrumbs'][] = ['label' => $modelWay->name, 'url' => ['mentor/way', 'id'=>$modelWay->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="news-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
