<?php
/** @var \yii\web\View $this */
/** @var \yii\data\ActiveDataProvider $dataProvider */
/** @var \app\modules\meeting\models\search\VksUfnsSearch $searchModel */

use app\modules\meeting\models\VksUfns;

echo $this->render('/shared/_index', [
    'dataProvider' => $dataProvider,
    'searchModel' => $searchModel,
    'title' => VksUfns::getTypeLabel(),
    'accessShowAllFields' => VksUfns::isViewerAllFields(),
    'roleEditor' => VksUfns::roleEditor(),
    'typeMeeting' => VksUfns::getType(),
    'grantAccessUnique' => [
        [
            'id' => VksUfns::roleEditor(),
            'label' => 'Редакторы',
        ],        
    ],
    'columns' => [
        'theme',
        'responsible',
        'members_people',
        'members_organization',
        'place',
    ],
    'showBtnLocation' => true,
]);
