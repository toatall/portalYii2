<?php

use kartik\grid\ActionColumn;
use yii\bootstrap4\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $modelQuestion \app\modules\test\models\TestQuestion */

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
                        //['class' => 'yii\grid\SerialColumn'],

                        'id',
                        'id_test_question',
                        'name',
                        'attach_file',
                        'weight',                       

                        ['class' => ActionColumn::class],
                    ],
                ]); ?>
            </div>
        </div>
    </div>
</div>
