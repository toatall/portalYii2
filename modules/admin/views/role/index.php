<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\modules\admin\models\Role;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

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
                    return yii\bootstrap\Html::a('Состав', ['/admin/role/admin', 'id'=>$model['name']], ['class'=>'btn btn-primary']);
                },
            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
