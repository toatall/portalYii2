<?php
/** @var \yii\web\View $this */
/** @var \yii\data\ActiveDataProvider $dataProvider */
/** @var \app\modules\meeting\models\search\VksFnsSearch $searchModel */

use app\modules\meeting\models\VksFns;

echo $this->render('/shared/_index', [
    'dataProvider' => $dataProvider,
    'searchModel' => $searchModel,
    'title' => VksFns::getTypeLabel(),
    'accessShowAllFields' => VksFns::isViewerAllFields(),
    'roleEditor' => VksFns::roleEditor(),
    'typeMeeting' => VksFns::getType(),
    'grantAccessUnique' => [
        [
            'id' => VksFns::roleEditor(),
            'label' => 'Редакторы',
        ],
        [
            'id' => VksFns::roleViewerAllFields(),
            'label' => 'Просмотр всех полей',
        ],
    ],
    'columns' => [
        'theme',
        'members_people',
        'place',
    ],
    'showBtnLocation' => true,
]);
