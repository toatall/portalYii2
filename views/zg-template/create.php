<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\zg\ZgTemplate */

$this->title = 'Create Zg Template';
$this->params['breadcrumbs'][] = ['label' => 'Zg Templates', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="zg-template-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
