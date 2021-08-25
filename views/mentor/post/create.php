<?php

/** @var yii\web\View $this */
/** @var \app\models\mentor\MentorPost $model */
/** @var \app\models\mentor\MentorWays $modelWay */

$this->title = 'Новый пост';
$this->params['breadcrumbs'][] = ['label' => 'Наставничество', 'url' => ['mentor/normative']];
$this->params['breadcrumbs'][] = ['label' => $modelWay->name, 'url' => ['mentor/way', 'id'=>$modelWay->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="news-create">

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
