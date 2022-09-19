<?php

use kartik\grid\ActionColumn;
use yii\bootstrap5\Html;
use kartik\grid\GridView;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */
/** @var \app\modules\test\models\TestQuestion $modelQuestion */

$this->title = 'Ответы';
$this->params['breadcrumbs'][] = ['label' => 'Тесты', 'url' => ['/test/test/index']];
$this->params['breadcrumbs'][] = ['label' => $modelQuestion->test->name, 'url' => ['/test/test/view', 'id'=>$modelQuestion->id_test]];
$this->params['breadcrumbs'][] = ['label' => 'Вопросы', 'url' => ['/test/question/index', 'idTest' => $modelQuestion->id_test]];
$this->params['breadcrumbs'][] = ['label' => $modelQuestion->name, 'url' => ['/test/question/view', 'id'=>$modelQuestion->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="test-answer-index">
    <div class="test-question-update">
        <div class="card shadow mb-4">
            <div class="card-header">
                <h1><?= Html::encode($this->title) ?></h1>
            </div>
            <div class="card-body">    
                <p>
                    <?= Html::a('Добавить ответ', ['create', 'idQuestion'=>$modelQuestion->id], ['class' => 'btn btn-success']) ?>
                </p>                
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'columns' => [                     
                        'id',
                        'id_test_question',
                        'name',
                        'attach_file',
                        'weight',                       

                        ['class' => ActionColumn::class],
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
        </div>
    </div>
</div>
