<?php

use app\models\Protocol;
use yii\bootstrap5\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Protocol $model */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Протоколы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="protocol-view">

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'date',
            'number',
            'name',
            [
                'attribute' => 'uploadMainFiles',
                'format' => 'raw',
                'value' => function(Protocol $model) {                    
                    $html = '';
                    foreach($model->getFilesMain() as $file) {
                        $html .= Html::a('<i class="far fa-file-alt"></i> ' . basename($file), $file, ['target' => '_blank']) . '<br />';
                    }
                    return $html;
                },
            ],
            'executor',
            [
                'attribute' => 'uploadExecuteFiles',
                'format' => 'raw',
                'value' => function(Protocol $model) {
                    $html = '';
                    foreach($model->getFilesExecute() as $file) {
                        $html .= Html::a('<i class="far fa-file-alt"></i> ' . basename($file), $file, ['target' => '_blank']) . '<br />';
                    }
                    return $html;
                },
            ],
            //'execute_description',
            'date_create:datetime',
            'date_update:datetime',
            'authorModel.fio',
        ],
    ]) ?>

</div>
