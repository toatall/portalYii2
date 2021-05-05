<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\test\models\TestAnswer */
/* @var $modelQuestion \app\modules\test\models\TestQuestion */

$this->title = 'Создание ответа';
$this->params['breadcrumbs'][] = ['label' => 'Тесты', 'url' => ['/test']];
$this->params['breadcrumbs'][] = ['label' => $modelQuestion->test->name, 'url' => ['/test/test/view', 'id'=>$modelQuestion->id_test]];
$this->params['breadcrumbs'][] = ['label' => 'Вопросы', 'url' => ['/test/question/index', 'idTest' => $modelQuestion->id_test]];
$this->params['breadcrumbs'][] = ['label' => $modelQuestion->name, 'url' => ['/test/question/view', 'id'=>$modelQuestion->id]];
$this->params['breadcrumbs'][] = ['label' => 'Ответы', 'url' => ['/test/answer/index', 'idQuestion'=>$modelQuestion->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="test-answer-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
