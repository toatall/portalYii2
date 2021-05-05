<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\conference\VksFns */

$this->title = 'Создать';
$this->params['breadcrumbs'][] = ['label' => 'ВКС с ФНС', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="vks-fns-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
