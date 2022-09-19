<?php
/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */
/** @var app\models\User $searchModel */
/** @var integer $idTest */

use kartik\grid\GridView;
use yii\bootstrap5\Html;

$this->title = 'Управление доступом';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="test-access-index">
    <div class="card shadow mb-4">
        <div class="card-header">
            <h1><?= Html::encode($this->title) ?></h1>
        </div>
        <div class="card-body">                        
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    'id',
                    'current_organization',                    
                    'department',
                    'username',
                    'fio',
                    [
                        'value' => function($model) use ($idTest) {
                            /** @var app\models\User $model */
                            return Html::a('Выбрать', ['/test/access/add', 'idTest'=>$idTest], [
                                'class' => 'btn btn-primary',
                                'data-method' => 'post',
                                'data-params' => [
                                    'user_id' => $model->id,
                                ],
                            ]);
                        },
                        'format' => 'raw',
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
            ]) ?>
        </div>
        <div class="card-footer">
            <?= Html::a('Назад', ['/test/access/index', 'idTest'=>$idTest], ['class'=>'btn btn-primary']) ?>
        </div>
    </div>
</div>