<?php

/** @var yii\web\View $this */
/** @var app\models\ChangeLegislation $model */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Изменение в законодательстве', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="change-legislation-view">
    
    <div class="card">            
        <div class="card-body">
            <span class="badge badge-success">Статус: <?= $model->status_doc ?></span><br />
            <strong><?= $model->getAttributeLabel('number_doc') ?></strong>: <?= $model->number_doc ?><br />
            <strong><?= $model->getAttributeLabel('date_doc') ?></strong>: <?= $model->date_doc ?><br />
            <strong><?= $model->getAttributeLabel('date_doc_1') ?></strong>: <?= $model->date_doc_1 ?><br />
            <strong><?= $model->getAttributeLabel('date_doc_2') ?></strong>: <?= $model->date_doc_2 ?><br />
            <strong><?= $model->getAttributeLabel('date_doc_3') ?></strong>: <?= $model->date_doc_3 ?><br />
        </div>
    </div>
    <div class="mt-3 card card-body">
        <?= $model->text ?>
    </div>   

</div>
