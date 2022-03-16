<?php

use app\models\ExecuteTasks;
use yii\bootstrap4\Html;


/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Исполнение задач';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="execute-tasks-index">

    <div class="col border-bottom mb-2">
        <p class="display-4">
            <?= $this->title ?>
        </p>    
    </div>

    <?php if (ExecuteTasks::isModerator()): ?>
    <p>
        <?= Html::a('Управление данными по исполнению задач', ['manage'], ['class' => 'btn btn-outline-success']) ?>
    </p>
    <?php endif; ?>

    


</div>
