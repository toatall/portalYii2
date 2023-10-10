<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\AutomationRoutine $model */

$this->title = 'Добавить ПМ';
$this->params['breadcrumbs'][] = ['label' => 'Автоматизация рутиных операций', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="automation-routine-create">

    <h1 class="display-5 border-bottom"><?= Html::encode($this->title) ?></h1>
    
    <div class="card card-body">
        <?= $this->render('_form', [
            'model' => $model,
        ]) ?>
    </div>

</div>
