<?php
/** @var yii\web\View $this */
/** @var \app\models\mentor\MentorWays[] $models */

use yii\bootstrap5\Html;

$this->title = 'Наставничество';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col border-bottom mb-2">
        <p class="display-5">
        <?= $this->title ?>
        </p>    
    </div>    
</div>


<div class="list-group">
    <?php foreach ($models as $model): ?>
        <?= Html::a($model->name . ' <span class="badge badge-primary">' . count($model->mentorPosts) . '</span>', ['/mentor/way', 'id'=>$model->id], ['class'=>'list-group-item list-group-item-action']) ?>
    <?php endforeach; ?>
</div>
