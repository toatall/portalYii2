<?php
/** @var \yii\web\View $this */
/** @var \app\modules\meeting\models\Conference $model */

$this->title = 'Создание ' . $model->getTypeLabel();

$this->params['breadcrumbs'][] = ['label' => $model->getTypeLabel(), 'url' => ['/meeting/' . $model->getType()]];
$this->params['breadcrumbs'][] = $this->title;
?>

<h2 class="mv-hide title">
    <?= $this->title ?>
</h2>

<?= $this->render('_form', [
    'model' => $model,
]) ?>