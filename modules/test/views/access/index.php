<?php

use kartik\grid\ActionColumn;
use yii\bootstrap4\Html;
use kartik\grid\GridView;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */
/** @var integer $idTest */
/** @var app\modules\test\models\Test $modelTest */

$this->title = 'Управление доступом';
if ($idTest == 0) {
    $this->title .= ' (для всех тестов)';
}
else {
    $this->title .= " (для теста \"{$modelTest->name}\")";
}
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="test-access-index">
    <div class="card shadow mb-4">
        <div class="card-header">
            <h1><?= Html::encode($this->title) ?></h1>
        </div>
        <div class="card-body">
            <p>
                <?= Html::a('Добавить пользователя', ['add', 'idTest' => $idTest], ['class' => 'btn btn-success']) ?>
            </p>
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'columns' => [                
                    'id:integer:#',
                    'id_test:integer:ИД теста',
                    'username:text:Учетная запись',
                    'fio:text:ФИО',
                    'date_create:datetime:Дата',
                    [
                        'class' => ActionColumn::class,
                        'template' => '{delete}',
                        'buttons' => [
                            'delete' => function($url, $model, $key) {
                                return Html::a('<i class="fas fa-trash-alt"></i>', ['/test/access/delete', 'id'=>$model['id_user'], 'idTest'=>$model['id_test']], [
                                    'data' => [
                                        'method' => 'post',
                                        'confirm' => 'Вы действительно хотите удалить данную запись?',
                                    ],
                                ]);
                            },
                        ],
                    ],
                ],
            ]); ?>
        </div>
    </div>
</div>
