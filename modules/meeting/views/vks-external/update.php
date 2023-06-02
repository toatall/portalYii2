<?php
/** @var \yii\web\View $this */
/** @var \app\modules\meeting\models\VksExternal $model */
/** @var \app\modules\meeting\models\VksExternalExtension $modelExtension */

$this->title = 'Редактирование ' . $model->theme;

$this->params['breadcrumbs'][] = ['label' => $model->getTypeLabel(), 'url' => ['/meeting/' . $model->getType()]];
$this->params['breadcrumbs'][] = $this->title;
?>

<h2 class="mv-hide title"<?= Yii::$app->request->isAjax ? ' style="display:none;"' : '' ?>>
    <?= $this->title ?>
</h2>

<?= $this->render('_form', [
    'model' => $model,
    'modelExtension' => $modelExtension,
]) ?>