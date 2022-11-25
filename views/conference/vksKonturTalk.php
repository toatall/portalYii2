<?php

use yii\bootstrap5\Html;
use kartik\grid\GridView;
use app\helpers\DateHelper;
use app\models\conference\VksKonturTalk;
use yii\widgets\Pjax;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */
/** @var app\models\conference\ConferenceSearch $searchModel */

$this->title = $searchModel::getTypeLabel();
$this->params['breadcrumbs'][] = $this->title;
$this->registerCssFile('/public/assets/portal/css/dayPost.css');

$isModerator = Yii::$app->user->can(VksKonturTalk::roleModerator()) 
    || Yii::$app->user->can('admin');

?>
<div class="conference-index">

    <div class="col border-bottom mb-2">
        <p class="display-5">
            <?= Html::encode($this->title) ?>
        </p>    
    </div>    

    <?php Pjax::begin(['id' => 'pjax-conference-vks-konur-talk-index', 'timeout'=>false, 'enablePushState'=>false]) ?>
    
    <?= GridView::widget([
        'id' => 'grid-conference-vks-konur-talk-index',
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'tableOptions' => ['class' => 'table table-bordered'],
        'options' => [
            'style' => 'table-layout:fixed',
        ],
        'rowOptions' => function($model, $index, $widget, $grid) {
            return $model->isFinished() ? ['class' => "table-success"] : [];
        },
        'columns' => [
            
            [
                'label' => 'Дата начала',
                'value' => function($model) {
                    return '<p class="calendar">' 
                        . date('d', strtotime($model->date_start)) . '<em>' 
                        . DateHelper::getMonthName(date('n', strtotime($model->date_start))) . '</em>'
                        
                        . '</p>'; 
                },
                'format' => 'raw',
            ],          
            [
                'attribute' => 'time_start',
                'value' => function($model) {                    
                    return '<div class="text-center"><span style="font-size: 2em;"><i class="fas fa-clock"></i> ' . $model->time_start . '</span></div>';
                },
                'format' => 'raw',
            ],
            [
                'attribute' => 'code_org',
                'value' => function($model) {
                    return $model->organization->fullName;
                },
                'format' => 'raw',
            ],            
            [
                'attribute' => 'theme',                                
            ],  
            'duration',
            [
                'label' => 'Статус',
                'format' => 'raw',
                'value' => function($model) {                    
                    /** @var VksKonturTalk $model */
                    if ($model->isFinished()) {
                        return '<div class="text-success" style="white-space: nowrap;"><i class="fas fa-check-circle"></i> завершен</div>';
                    }
                    else {
                        return '<div class="text-secondary" style="white-space: nowrap;"><i class="fas fa-clock"></i> выполняется</div>'; 
                    }
                },
            ],
            [
                'value' => function($model) {
                    /** @var VksKonturTalk $model */
                    return Html::a('Просмотр', ['conference/view', 'id'=>$model->id], ['class' => 'btn btn-primary mv-link'])
                        . ($model->isModerator() ? 
                              '<br />' . Html::a('<i class="fas fa-pencil-alt"></i>', ['vks-kontur-talk-update', 'id'=>$model->id], ['class' => 'mv-link']) . '&nbsp;&nbsp;'
                            . Html::a('<i class="fas fa-trash-alt"></i>', ['vks-kontur-talk-delete', 'id'=>$model->id], [
                                'data-confirm' => 'Вы действительно хотите удалить данную запись?',
                                'class' => 'link-delete',
                                'title' => 'Удалить',
                            ])
                            : '');
                },
                'format' => 'raw',
            ],
        ],
        'toolbar' => [
            'content' => ($isModerator)
                ? Html::a('Добавить', ['vks-kontur-talk-create'], ['class' => 'btn btn-success mx-2 mv-link']) : '',
            '{export}',
            '{toggleData}',
        ],
        'export' => [
            'showConfirmAlert' => false,
        ],
        'panel' => [
            'type' => GridView::TYPE_DEFAULT,       
        ],
    ]); ?>

</div>
<?php $this->registerJs(<<<JS

    $(modalViewer).off('onRequestJsonAfterAutoCloseModal');
    $(modalViewer).on('onRequestJsonAfterAutoCloseModal', function(event, data) {               
        $('#grid-conference-vks-konur-talk-index').yiiGridView('applyFilter');
    });

    $('.link-delete').on('click', function(e) {
        e.preventDefault();
        const url = $(this).attr('href');
        const textConf = $(this).data('confirm');
        if (!confirm(textConf)) {
            return false;
        }
        $.ajax({
            url: url,
            method: 'post',
            error: function(xhr, status, error) {
                alert(xhr.responseText);
            }
        })
        .done(function() {
            $('#grid-conference-vks-konur-talk-index').yiiGridView('applyFilter');
        });
        return false;
    });

JS); ?>

<?php Pjax::end() ?>