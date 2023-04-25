<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\modules\admin\models\FooterType $model */

$this->title = 'Редактирование раздела: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Ссылки в подвале Портала', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Редактирование';
?>
<div class="footer-type-update">

    <h1 class="title mv-hide"><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
