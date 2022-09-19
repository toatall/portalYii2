<?php

use yii\bootstrap5\Html;
use kartik\grid\GridView;
use app\modules\admin\models\Role;
use kartik\grid\ActionColumn;
use kartik\grid\SerialColumn;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Роли';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="role-index">

    <h1 class="display-5 border-bottom">
        <?= Html::encode($this->title) ?>
    </h1>

    <p>
        <?= Html::a('Добавить роль', ['create'], ['class' => 'btn btn-success']) ?>
    </p>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => SerialColumn::class],
            'name:text:Наименование',
            'description:text:Описание',
            'rule_name:text:Правило',
            'created_at:datetime:Дата создания',
            'updated_at:datetime:Дата изменения',
            [                
                'format'=>'raw',
                'value'=>function(Role $model) {
                    return Html::a('Состав', ['/admin/role/admin', 'id'=>$model['name']], ['class'=>'btn btn-outline-primary']);
                },
            ],

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
