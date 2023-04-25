<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\modules\admin\models\FooterType $model */

$this->title = 'Создание раздела';
$this->params['breadcrumbs'][] = ['label' => 'Footer Types', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="footer-type-create">

    <h1 class="title mv-hide"><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
