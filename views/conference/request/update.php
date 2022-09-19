<?php

use yii\bootstrap5\Html;

/** @var yii\web\View $this */
/** @var app\models\conference\Conference $model */

$this->title = 'Изменить заявку: #' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Заявки для проведения мероприятий', 'url' => ['request']];
$this->params['breadcrumbs'][] = 'Изменение';
?>
<div class="conference-update">

    <div class="col border-bottom mb-2">
        <p class="display-4">
            <?= Html::encode($this->title) ?>
        </p>    
    </div>    

    
    <?= $this->render('_statuses', ['model' => $model]) ?>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
