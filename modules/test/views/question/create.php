<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\test\models\TestQuestion */
/* @var $modelTest \app\modules\test\models\Test */

$this->title = 'Создание вопроса';
$this->params['breadcrumbs'][] = ['label' => 'Тесты', 'url' => ['/test']];
$this->params['breadcrumbs'][] = ['label' => $modelTest->name, 'url' => ['/test/view', 'id'=>$modelTest->id]];
$this->params['breadcrumbs'][] = ['label' => 'Вопросы', 'url' => ['index', 'idTest' => $model->id_test]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="test-question-create">

    <h1><?= Html::encode($this->title) ?></h1>
    <hr />

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
