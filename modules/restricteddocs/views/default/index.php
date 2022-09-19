<?php

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */
/** @var string|null $findOrg */
/** @var string|null $findType */
/** @var string|null $searchName */

use app\modules\restricteddocs\models\RestrictedDocs;
use app\modules\restricteddocs\models\RestrictedDocsOrgs;
use app\modules\restricteddocs\models\RestrictedDocsTypes;
use kartik\select2\Select2;
use yii\bootstrap5\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\widgets\Pjax;

$this->title = 'Информационный ресурс по предоставлению информации ограниченного доступа';
$roleEditor = Yii::$app->user->can('admin') || Yii::$app->user->can(RestrictedDocs::roleModerator());
?>

<p class="display-4 border-bottom">
    <?= $this->title ?>
</p>

<?php if ($roleEditor): ?>
<div>
    <?= Html::a('<i class="fas fa-tasks"></i> Управление организациями', ['/restricteddocs/docs-orgs'], ['class' => 'btn btn-secondary btn-sm mv-link']) ?>
    <?= Html::a('<i class="fas fa-tasks"></i> Управление видами сведений', ['/restricteddocs/docs-types'], ['class' => 'btn btn-secondary btn-sm mv-link']) ?>
</div>
<hr />
<?php endif; ?>

<?php Pjax::begin(['id'=>'pjax-restricted-default-index', 'timeout'=>false]); ?>

<div class="mt-3 row">
    <div class="col">        
        <div class="card">
            <div class="card-header">
                Параметры запроса
            </div>
            <div class="card-body">
                <?= Html::beginForm(Url::to(['/restricteddocs/default/index']), 'get', ['id' => 'form-restricted-docs-index', 'data-pjax' => true]) ?>
                <div class="row">
                    <div class="col-4">                            
                        <?= Select2::widget([
                            'name' => 'findOrg',
                            'value' => $findOrg,
                            'data' => RestrictedDocsOrgs::dropDownList(),
                            'options' => [                                
                                'placeholder' => 'наименование организации',
                            ],
                            'pluginOptions' => [
                                'allowClear' => true
                            ],
                        ]) ?>
                    </div>
                    <div class="col-4">                        
                        <?= Select2::widget([
                            'name' => 'findType',
                            'value' => $findType,
                            'data' => RestrictedDocsTypes::dropDownList(),
                            'options' => [                                
                                'placeholder' => 'запрашиваемая информация',
                            ],
                            'pluginOptions' => [
                                'allowClear' => true
                            ], 
                        ]) ?>                            
                    </div>
                    <div class="col-3">
                        <?= Html::textInput('searchName', $searchName, ['class' => 'form-control', 'placeholder' => 'наименование НПА']) ?>
                    </div>
                    <div class="col">
                        <?= Html::submitButton('Поиск', ['class' => 'btn btn-primary']) ?>
                    </div>
                </div>                
                <?= Html::endForm() ?>
            </div>
        </div>
    </div>
</div>

<div class="mt-4">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,    
        'columns' => [
            // 'id',
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
            'name',
            [
                'label' => 'Номер и дата НПА',
                'value' => function($model) {
                    /** @var app\modules\restricteddocs\models\RestrictedDocs $model */
                    return $model->doc_num . ' от ' . ($model->doc_date ? Yii::$app->formatter->asDate($model->doc_date) : null);
                },
            ],
            'privacy_sign_desc',
            [
                'label' => 'Приложения',
                'value' => function($model) {
                    /** @var app\modules\restricteddocs\models\RestrictedDocs $model */
                    $res = '';
                    $files = $model->getFiles();
                    if ($files) {
                        foreach($files as $file) {
                            $res .= '<a href="' . $file . '" data-pjax="0" target="_blank"><i class="far fa-file"></i> ' . basename($file) . '</a><br />';
                        }
                    }
                    return $res;
                },
                'format' => 'raw',
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'header' => $roleEditor ? Html::a('<i class="fas fa-plus-circle"></i> Добавить', ['/restricteddocs/docs/create'], 
                    ['class' => 'btn btn-primary btn-sm mv-link']) : null,
                'template' => '{update} {delete}',            
                'buttons' => [
                    'update' => function($url, $model) {
                        return Html::a('<i class="fas fa-pencil-alt"></i>', ['/restricteddocs/docs/update', 'id'=>$model->id], [
                            'data-pjax' => false,
                            'class' => 'mv-link',
                        ]);
                    },
                    'delete' => function($url, $model) {
                        return Html::a('<i class="fas fa-trash text-danger"></i>', ['/restricteddocs/docs/delete', 'id'=>$model->id], [                            
                            'data' => [
                                // 'confirm' => 'Вы уверены, что хотите удалить?',
                                // 'method' => 'post',
                                'pjax' => false,                      
                            ],  
                            'class' => 'btn-delete',                      
                        ]);
                    },
                ],                         
                'visibleButtons' => [
                    'update' => $roleEditor,
                    'delete' => $roleEditor,
                ],
            ],
        ],
    ]) ?>
</div>

<?php $this->registerJs(<<<JS
   
    $('#form-restricted-docs-index select').on('change', function() {      
        $('#form-restricted-docs-index').submit();
    });

    $('.btn-delete').on('click', function() {
        if (!confirm('Вы уверены, что хотите удалить?')) {
            return false;
        }
        const url = $(this).attr('href');

        $.ajax({
            url: url,
            method: 'post'            
        })
        .done(function() {
            $('#form-restricted-docs-index').submit();      
        });

        return false;
    });
    

JS); ?>

<?php Pjax::end(); ?>

<?php $this->registerJs(<<<JS
    $(modalViewer).off('onRequestJsonAfterAutoCloseModal');
    $(modalViewer).on('onRequestJsonAfterAutoCloseModal', function(event, data) {    
        $('#form-restricted-docs-index').submit();        
    });
JS); ?>