<?php

use app\models\calendar\Calendar;
use app\models\calendar\CalendarData;
use yii\grid\GridView;
use yii\bootstrap4\Html;
use yii\helpers\StringHelper;
use yii\widgets\DetailView;
use yii\widgets\Pjax;

/** @var yii\web\View $this */
/** @var app\models\Calendar $model */
/** @var yii\data\ActiveDataProvider $dataProviderCalendarData */

$this->title = Yii::$app->formatter->asDate($model->date);
$this->params['breadcrumbs'][] = ['label' => 'Календарь', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="calendar-view">

    <div class="mb-2">
        <span class="badge badge-<?= $model->color ?>" style="font-size: 2.5rem;"><?= Html::encode($this->title) ?></span>
    </div>

    <div class="btn-group mb-2">
        <?= Html::a('<i class="fas fa-pencil-alt"></i> Изменить запись о дате', ['update', 'id' => $model->id], ['class' => 'btn btn-primary btn-sm']) ?>
        <?= Html::a('<i class="fas fa-trash-alt"></i> Удалить запись о дате', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger btn-sm',
            'data' => [
                'confirm' => 'Вы уверены, что хотите удалить запись?',
                'method' => 'post',
            ],
        ]) ?>
        <?= Html::button('<i class="fas fa-chevron-circle-down"></i> Подробнее', ['id'=>'btn-detail', 'class'=>'btn btn-secondary btn-sm']) ?>
    </div>
<?= $this->registerJs(<<<JS
    $('#btn-detail').on('click', function() {
        $('#detail-view-date').toggle();
    });
JS); ?>

    <div id="detail-view-date" style="display: none;">
        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                'id',
                'date:date',
                'organizationModel.fullName:text:Организация',           
                [
                    'attribute' => 'color',
                    'value' => function(Calendar $model) {                    
                        return "<span class=\"badge-{$model->color} rounded\" style=\"font-size: 1em; font-weight: normal; padding: 0.3rem;\">{$model->getColorDescription()}</span>";                        
                    },
                    'format' => 'raw',
                ],                         
                'date_create:datetime',
                'userModel.fio:text:Автор',
            ],      
        ]) ?>
    </div>

    <hr />

    <?php Pjax::begin(['id'=>'pjax-date-view', 'timeout' => false, 'enablePushState'=>false]) ?>

        <?= GridView::widget([
            'id' => 'grid-view-calendar-data',
            'dataProvider' => $dataProviderCalendarData,            
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                           
                [
                    'attribute' => 'description',
                    'value' => function(CalendarData $model) {
                        return Html::tag('span', StringHelper::truncateWords($model->description, 6), [
                            'class' => 'badge badge-' . $model->color, 
                            'style' => 'font-size: 1rem;',
                            'title' => $model->description,
                        ]);
                    },
                    'format' => 'raw',
                ],  
                [
                    'label' => 'Глобальная',
                    'attribute' => 'is_global',
                    'format' => 'boolean',
                    'visible' => Calendar::roleModerator(),
                ],                
                'date_create:datetime',
                'userModel.fio:text:Автор', 
                [
                    'class' => 'yii\grid\ActionColumn',
                    
                    'header' => Html::a('<i class="fas fa-plus-circle"></i> Добавить', 
                        ['/admin/calendar/create-calendar-data', 'idCalendar'=>$model->id], ['class'=>'btn btn-primary mv-link']),
                    'template' => '{update} {delete}',
                    'buttons' => [                        
                        'update' => function($url, $model, $key) {
                            return Html::a('<i class="fas fa-pencil-alt"></i>', ['/admin/calendar/update-calendar-data', 'id'=>$model->id], ['class'=>'mv-link']);
                        },
                        'delete' => function($url, $model, $key) {
                            return Html::a('<i class="fas fa-trash"></i>', ['/admin/calendar/delete-calendar-data', 'id'=>$model->id], [
                                'data' => [
                                    'confirm' => 'Вы уверены, что хотите удалить?',
                                    'method' => 'post',
                                    'pjax' => true,
                                ],
                            ]);
                        },
                    ],
                ],
            ],

        ]) ?>

    <?php Pjax::end() ?>

<?php $this->registerJs(<<<JS
    $(modalViewer).unbind('onRequestJsonAfterAutoCloseModal');
    $(modalViewer).on('onRequestJsonAfterAutoCloseModal', function(event) {
        $.pjax.reload({container:'#pjax-date-view', async: false });        
    });


    // $('#pjax-date-view').unbind('pjax:complete');
    // $('#pjax-date-view').on('pjax:complete', function() {
    //     $('#pjax-date-view').on('pjax:complete'
    // });
JS); ?>

</div>
