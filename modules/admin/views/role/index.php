<?php

use yii\bootstrap4\Html;
use kartik\grid\GridView;
use app\modules\admin\models\Role;
use kartik\grid\ActionColumn;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Роли';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="role-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Добавить роль', ['create'], ['class' => 'btn btn-success']) ?>
    </p>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'name:text:Наименование',
            'description:text:Описание',
            'rule_name:text:Правило',
            'created_at:datetime:Дата создания',
            'updated_at:datetime:Дата изменения',
            [                
                'format'=>'raw',
                'value'=>function(Role $model) {
                    return Html::a('Состав', ['/admin/role/admin', 'id'=>$model['name']], ['class'=>'btn btn-primary']);
                },
            ],

            ['class' => ActionColumn::class],
        ],
    ]); ?>


</div>
