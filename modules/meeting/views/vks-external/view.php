<?php
/** @var \yii\web\View $this */
/** @var \app\modules\meeting\models\VksExternal $model */

use app\modules\meeting\models\VksExternal;

$this->title = $model->theme;
$accessShowAllFields = VksExternal::isViewerAllFields();

$this->params['breadcrumbs'][] = ['label' => VksExternal::getTypeLabel(), 'url' => ['/meeting/vks-fns']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('/shared/_view', [
    'model' => $model,
    'title' => $this->title,
    'accessShowAllFields' => VksExternal::isViewerAllFields(),
    'columns' => [
        'place',
        'responsible',
        'note',
    ],
    'extensionColumns' => [        
        [
            'attribute' => 'extension.format_holding',
            'visible' => $accessShowAllFields,
        ],
        [
            'attribute' => 'extension.members_count',
            'visible' => $accessShowAllFields,
        ],
        [
            'attribute' => 'extension.members_count_ufns',
            'visible' => $accessShowAllFields,
        ],
        [
            'attribute' => 'extension.material_translation',
            'visible' => $accessShowAllFields,
        ],
        [
            'attribute' => 'extension.person_head',
            'visible' => $accessShowAllFields,
        ],
        [
            'attribute' => 'extension.link_event',
            'visible' => $accessShowAllFields,
        ],
        [
            'attribute' => 'extension.is_connect_vks_fns',
            'visible' => $accessShowAllFields,
            'format' => 'boolean',
        ],
        [
            'attribute' => 'extension.platform',
            'visible' => $accessShowAllFields,
        ],
        [
            'attribute' => 'extension.full_name_support_ufns',
            'visible' => $accessShowAllFields,
        ],
        [
            'attribute' => 'extension.date_test_vks',
            'visible' => $accessShowAllFields,
            'format' => 'datetime',            
        ],
        [
            'attribute' => 'extension.count_notebooks',
            'visible' => $accessShowAllFields,           
        ],
        [
            'attribute' => 'members_organization',
            'visible' => $accessShowAllFields,           
        ],
    ],
]) ?>