<?php
/** @var \yii\web\View $this */
/** @var \app\modules\meeting\models\Conference $model */

$this->title = $model->theme;
$accessShowAllFields = $model->isViewerAllFields();

$this->params['breadcrumbs'][] = ['label' => $model->getTypeLabel(), 'url' => ['/meeting/' . $model->getType()]];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('/shared/_view', [
    'model' => $model,
    'title' => $this->title,
    'accessShowAllFields' => $model->isViewerAllFields(),
    'columns' => [
        'place',        
        'members_people',
        'note',
    ],
]) ?>