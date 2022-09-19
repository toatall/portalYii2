<?php

use yii\bootstrap5\Html;

/** @var yii\web\View $this */
/** @var app\modules\admin\models\Role $model */

$this->title = 'Добавление роли';
$this->params['breadcrumbs'][] = ['label' => 'Роли', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="role-create">

    <h1 class="display-5 border-bottom">
        <?= Html::encode($this->title) ?>
    </h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
