<?php

use yii\bootstrap5\Html;

/** @var yii\web\View $this */
/** @var app\models\conference\Conference $model */

$this->title = 'Создать';
$this->params['breadcrumbs'][] = ['label' => 'Заявки для проведения мероприятий', 'url' => ['conference/request']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="conference-create">

    <div class="col border-bottom mb-2">
        <p class="display-4">
            <?= Html::encode($this->title) ?>
        </p>    
    </div>    

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
