<?php

use kartik\editable\Editable;
use yii\bootstrap5\Html;
use kartik\grid\GridView;
use kartik\grid\ActionColumn;
use kartik\grid\EditableColumn;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Анкетирование по ГР';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="reg-ecr-index">

    <h1 class="display-5 border-bottom">
        <?= Html::encode($this->title) ?>
    </h1>

    <p class="mt-3 btn-group">
        <?= Html::a('Добавить', ['create'], ['class' => 'btn btn-success']) ?>
    </p>


    <?= GridView::widget([
        'id' => 'grid-regecr-index',
        'pjax' => true,
        'responsive' => false,
        'pager' => [
            'firstPageLabel' => 'Первая',
            'lastPageLabel' => 'Последняя',
        ],
        'dataProvider' => $dataProvider,
        'columns' => [            
            'id',
            'code_org',
            // 'date_reg:date',
            [
                'class' => EditableColumn::class,
                'attribute' => 'date_reg',
                'editableOptions' => [                                   
                    'inputType' => Editable::INPUT_DATE,                
                    'formOptions' => ['action' => ['/admin/regecr/editpage']],                            
                    'options' => [
                        'convertFormat' => true,
                        'pluginOptions' => [
                            'todayHighlight' => true,
                            'todayBtn' => true,
                            'autoclose' => true,                            
                        ],
                    ],
                ],                
                // 'format' => 'date',
                'refreshGrid' => true,
            ],
            'count_create',
            'count_vote',
            'avg_eval_a_1_1',
            'avg_eval_a_1_2',
            'avg_eval_a_1_3',
            //'author',
            //'date_create',
            //'date_update',
            //'date_delete',

            [
                'class' => ActionColumn::class,
                'dropdown' => true,
            ],
        ],
        'toolbar' => [
            '{export}',
            '{toggleData}',
        ],
        'export' => [
            'showConfirmAlert' => false,
        ],
        'panel' => [
            'type' => GridView::TYPE_DEFAULT,       
        ],
    ]); ?>


</div>
