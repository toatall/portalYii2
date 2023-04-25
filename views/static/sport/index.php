<?php
/** @var \yii\web\View $this */

use yii\bootstrap5\Tabs;

$this->title = 'Спорт';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="col border-bottom mb-2">
    <p class="display-5">
        <?= $this->title ?>
    </p>    
</div>

<?= Tabs::widget([
    'items' => [
        ['label' => 'Новости', 'content' => $this->render('_news')],
        ['label' => 'Отвественные за спорт', 'content' => $this->render('_responsibles')],
        ['label' => 'Рассписание тренировок', 'content' => $this->render('_schedule')],
        ['label' => 'Спортивный план', 'content' => $this->render('_plan')],
    ],
]) ?>