<?php

/** @var yii\web\View $this */
/** @var \app\models\mentor\MentorPost $model */

$this->title = 'Изменение новости: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Наставничество', 'url' => ['mentor/normative']];
$this->params['breadcrumbs'][] = ['label' => $model->mentorWays->name, 'url' => ['mentor/way', 'id'=>$model->mentorWays->id]];
$this->params['breadcrumbs'][] = 'Изменить';
?>
<div class="news-update">

    <div class="row">
        <div class="col border-bottom mb-2">
            <p class="display-4">
            <?= $this->title ?>
            </p>    
        </div>    
    </div>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
