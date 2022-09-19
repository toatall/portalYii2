<?php

use yii\bootstrap5\Html;
use yii\bootstrap5\Tabs;
use app\modules\admin\models\Role;

/** @var yii\web\View $this */
/** @var Role $model */

$this->title = 'Состав роли ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Роли', 'url' => ['/admin/role/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="role-index">

    <h1 class="display-5 border-bottom">
        <?= Html::encode($this->title) ?>
    </h1>

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
