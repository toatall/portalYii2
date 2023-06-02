<?php
/** @var \yii\web\View $this */
/** @var \app\modules\meeting\models\VksFns $model */

use app\modules\meeting\models\VksFns;

$this->title = $model->theme;
$accessShowAllFields = VksFns::isViewerAllFields();

$this->params['breadcrumbs'][] = ['label' => VksFns::getTypeLabel(), 'url' => ['/meeting/vks-fns']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('/shared/_view', [
    'model' => $model,
    'title' => $this->title,
    'accessShowAllFields' => VksFns::isViewerAllFields(),
    'columns' => [
        'place',
        'members_people',
        'note',
    ],
]) ?>