<?php
/** @var yii\web\View $this */
/** @var app\models\department\Department $model */

$this->params['breadcrumbs'][] = ['label' => $model->department_name, 'url' => ['view', 'id'=>$model->id]];
?>

<div class="news-index">

    <div class="row">
        <div class="col border-bottom mb-2">
            <p class="display-5">
            <?= $this->title ?>
            </p>    
        </div> 
    </div>

    <div class="row">
        <div class="col">
            <div class="alert alert-info">Нет данных</div>
        </div>
    </div>    

</div>
