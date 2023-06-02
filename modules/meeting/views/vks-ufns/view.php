<?php
/** @var \yii\web\View $this */
/** @var \app\modules\meeting\models\VksUfns $model */

use app\modules\meeting\models\VksUfns;

$this->title = $model->theme;
$accessShowAllFields = VksUfns::isViewerAllFields();

$this->params['breadcrumbs'][] = ['label' => VksUfns::getTypeLabel(), 'url' => ['/meeting/vks-ufns']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('/shared/_view', [
    'model' => $model,
    'title' => $this->title,
    'accessShowAllFields' => VksUfns::isViewerAllFields(),
    'columns' => [
        'place',
        'responsible',
        'members_people',
        'members_organization',
        'note',
    ],
]) ?>