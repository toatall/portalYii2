<?php
/** @var \yii\web\View $this */
/** @var \yii\data\ActiveDataProvider $dataProvider */
/** @var \app\modules\meeting\models\search\VksExternalSearch $searchModel */

use app\modules\meeting\models\VksExternal;

echo $this->render('/shared/_index', [
    'dataProvider' => $dataProvider,
    'searchModel' => $searchModel,
    'title' => VksExternal::getTypeLabel(),
    'accessShowAllFields' => VksExternal::isViewerAllFields(),
    'roleEditor' => VksExternal::roleEditor(),
    'typeMeeting' => VksExternal::getType(),
    'grantAccessUnique' => [
        [
            'id' => VksExternal::roleEditor(),
            'label' => 'Редакторы',
        ],
        [
            'id' => VksExternal::roleViewerAllFields(),
            'label' => 'Просмотр всех полей',
        ],
    ],
    'columns' => [
        'theme',
        'members_organization',
        'place',
    ],
    'extensionColumns' => [        
        'extension.platform',
    ],
    'showBtnLocation' => true,
]);
