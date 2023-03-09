<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\modules\beginner\models\Beginner $model */

$this->title = 'Create Beginner';
$this->params['breadcrumbs'][] = ['label' => 'Beginners', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="beginner-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
