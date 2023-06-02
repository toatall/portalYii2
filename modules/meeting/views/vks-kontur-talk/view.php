<?php
/** @var \yii\web\View $this */
/** @var \app\modules\meeting\models\VksKonturTalk $model */

use app\modules\meeting\models\VksKonturTalk;

$this->title = $model->theme;
$accessShowAllFields = VksKonturTalk::isViewerAllFields();

$this->params['breadcrumbs'][] = ['label' => VksKonturTalk::getTypeLabel(), 'url' => ['/meeting/vks-kontur-talk']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('/shared/_view', [
    'model' => $model,
    'title' => $this->title,
    'accessShowAllFields' => VksKonturTalk::isViewerAllFields(),
    'columns' => [
        'org_code',
        'note',
    ],
]) ?>