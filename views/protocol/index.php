<?php

use app\models\Protocol;
use kartik\grid\ActionColumn;
use yii\bootstrap5\Html;
use kartik\grid\GridView;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Протоколы';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="protocol-index">

    <h1 class="display-5 border-bottom">
        <?= Html::encode($this->title) ?>
    </h1>

    <?php if (Protocol::isRoleModerator()): ?>
    <p>
        <?= Html::a('Добавить', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?php endif; ?>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [          

            'id',
            // 'date',
            // 'number',
            [
                'label' => 'Дата и номер',
                'format' => 'text',
                'value' => function(Protocol $model) {
                    return 'от ' . $model->date . ' №' . $model->number;
                },
            ],
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
            //'date_create',
            //'date_update',
            //'author',
            [
                'format' => 'html',
                'value' => function(Protocol $model) {
                    return Html::a('Подробнее', ['view', 'id'=>$model->id], ['class'=>'btn btn-primary btn-sm mv-link']);
                },
            ],

            [
                'class' => ActionColumn::class,
                'visibleButtons' => [
                    'view' => false,
                    'update' => Protocol::isRoleModerator(),
                    'delete' => Protocol::isRoleModerator(),
                ],
            ],
        ],
    ]); ?>


</div>
