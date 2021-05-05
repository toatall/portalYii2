<?php

use yii\helpers\Html;
use yii\bootstrap\Tabs;
use app\modules\admin\models\Role;

/* @var $this yii\web\View */
/* @var $model Role */

$this->title = 'Состав роли ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Роли', 'url' => ['/admin/role/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="role-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= Tabs::widget([
        'items' => [
            [
                'label' => 'Пользователи',
                'content' => $this->render('_subUser', ['model'=>$model]),
                'active' => true,
            ],
            [
                'label' => 'Роли',
                'content' => $this->render('_subRole', ['model'=>$model]),
            ],
        ],
    ]) ?>







</div>
