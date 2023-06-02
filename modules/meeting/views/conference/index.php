<?php
/** @var \yii\web\View $this */
/** @var \yii\data\ActiveDataProvider $dataProvider */
/** @var \app\modules\meeting\models\search\ConferenceSearch $searchModel */

use app\modules\meeting\models\Conference;

echo $this->render('/shared/_index', [
    'dataProvider' => $dataProvider,
    'searchModel' => $searchModel,
    'title' => Conference::getTypeLabel(),
    'accessShowAllFields' => Conference::isViewerAllFields(),
    'roleEditor' => Conference::roleEditor(),
    'typeMeeting' => Conference::getType(),
    'grantAccessUnique' => [
        [
            'id' => Conference::roleEditor(),
            'label' => 'Редакторы',
        ],
        [
            'id' => Conference::roleViewerAllFields(),
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
