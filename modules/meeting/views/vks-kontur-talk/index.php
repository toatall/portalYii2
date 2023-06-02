<?php
/** @var \yii\web\View $this */
/** @var \yii\data\ActiveDataProvider $dataProvider */
/** @var \app\modules\meeting\models\search\VksKonturTalkSearch $searchModel */

use app\modules\meeting\models\VksKonturTalk;

echo $this->render('/shared/_index', [
    'dataProvider' => $dataProvider,
    'searchModel' => $searchModel,
    'title' => VksKonturTalk::getTypeLabel(),
    'accessShowAllFields' => VksKonturTalk::isViewerAllFields(),
    'roleEditor' => VksKonturTalk::roleEditor(),
    'typeMeeting' => VksKonturTalk::getType(),
    'grantAccessUnique' => [
        [
            'id' => VksKonturTalk::roleEditor(),
            'label' => 'Редакторы УФНС',
        ],
        [
            'id' => VksKonturTalk::roleEditorIfns(),
            'label' => 'Редакторы ИФНС',
        ],
    ],
    'columns' => [
        'org_code',
        'theme',        
    ],
    'showBtnLocation' => false,
]);
