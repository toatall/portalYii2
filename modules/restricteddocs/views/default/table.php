<?php

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

use yii\grid\GridView;
use yii\widgets\Pjax;

?>
<?php Pjax::begin(['id'=>'pjax-restricted-default-index', 'timeout'=>false]); ?>

<?= GridView::widget([
    'dataProvider' => $dataProvider,    
    'summary' => false,
    'columns' => [   
        [
            'attribute' => 'restrictedDocsOrgsVals',
            'value' => function($model) {
                /** @var app\modules\restricteddocs\models\RestrictedDocs $model */
                $orgs = $model->restrictedDocsOrgs;
                $res = '<ol>';
                if ($orgs) {
                    foreach($orgs as $org) {
                        $res .= '<li>' . $org->name . '</li>';
                    }
                }
                return $res . '</ol>';
            },
            'format' => 'raw',
        ],
        [
            'attribute' => 'restrictedDocsTypesVals',
            'value' => function($model) {
                /** @var app\modules\restricteddocs\models\RestrictedDocs $model */
                $types = $model->restrictedDocsTypes;
                $res = '<ol>';
                if ($types) {
                    foreach($types as $type) {
                        $res .= '<li>' . $type->name . '</li>';
                    }
                }
                return $res . '</ol>';
            },
            'format' => 'raw',
        ],
        [
            'attribute' => 'name',
            'value' => function($model) {
                /** @var app\modules\restricteddocs\models\RestrictedDocs $model */
                return $model->name 
                    . ($model->is_privacy 
                        ? '<br /><span class="badge bg-success"><i class="fas fa-key"></i> Конфиденциально</<span>'
                        : ''
                    );
            },
            'format' => 'raw',
        ],        
        [
            'label' => 'Номер и дата НПА',
            'value' => function($model) {
                /** @var app\modules\restricteddocs\models\RestrictedDocs $model */
                return $model->doc_num . ' от ' . ($model->doc_date ? Yii::$app->formatter->asDate($model->doc_date) : null);
            },
        ],
        'privacy_sign_desc',
        [
            'label' => 'Описание',
            'value' => function($model) {
                /** @var app\modules\restricteddocs\models\RestrictedDocs $model */
                $res = '';

                if ($model->description_internet) {
                    $res .= $model->description_internet;
                }

                $files = $model->getFiles();
                if ($files) {
                    if ($res) {
                        $res .= '<br /></hr />';
                    }
                    foreach($files as $file) {
                        $res .= '<a href="' . $file . '" data-pjax="0" target="_blank"><i class="far fa-file"></i> ' . basename($file) . '</a><br />';
                    }
                }
                return $res;
            },
            'format' => 'raw',
        ],        
    ],
]) ?>

<?php Pjax::end(); ?>