<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\modules\rookie\modules\fortboyard\models\FortBoyard $model */

$this->title = 'Добавление';
$this->params['breadcrumbs'][] = ['label' => 'Fort Boyards', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="container bg-light mt-4 rounded p-4">

    <div class="fort-boyard-create">

        <h1 class="font-weight-bolder border-bottom"><?= Html::encode($this->title) ?></h1>

        <?= $this->render('_form', [
            'model' => $model,
        ]) ?>

    </div>

</div>