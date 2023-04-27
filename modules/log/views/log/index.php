<?php

use app\modules\log\models\Log;
use kartik\grid\CheckboxColumn;
use yii\bootstrap5\Html;
use yii\helpers\Url;
use kartik\grid\ActionColumn;
use kartik\grid\ExpandRowColumn;
use kartik\grid\GridView;
use yii\helpers\StringHelper;
use yii\widgets\Pjax;
/** @var yii\web\View $this */
/** @var app\modules\log\models\LogSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Журнал работы';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="log-index">

    <h1 class="display-3 border-bottom text-center">
        <a href="<?= Url::to(['index']) ?>" class="text-decoration-none">
            <i class="fas fa-file-medical-alt"></i>
            <?= Html::encode($this->title) ?>
        </a>
    </h1>    

    <?php Pjax::begin(['id' => 'pjax-log-index', 'timeout' => false, 'scrollTo' => true]) ?>

    <div class="card my-2">            

        <div class="card-body">
        
        <?= $this->render('_search', ['model' => $searchModel]) ?>

        <?= GridView::widget([
            'options' => ['style' => 'font-size: 0.9rem;'],            
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],    
                [
                    'class' => CheckboxColumn::class,
                ],  
                [
                    'class' => ExpandRowColumn::class,
                    'detailUrl' => Url::to(['/log/log/view']),
                    'value' => function() {
                        return GridView::ROW_COLLAPSED;
                    },
                ],      
                'id',
                'level',
                'category',               
                [
                    'attribute' => 'url',
                    'format' => 'raw',
                    'value' => function(Log $model) {
                        return Html::a(StringHelper::truncate($model->url, 30), Url::to($model->url), 
                        [
                            'target' => '_blank',                        
                            'data-bs-target' => 'tooltip',
                            'data-bs-title' => $model->url,
                        ]);
                    },
                
                ],
                [
                    'attribute' => 'statusCode',
                    'format' => 'raw',
                    'value' => function(Log $model) {
                        $firstNum = substr($model->statusCode, 0, 1);
                        $bg = 'secondary';
                        if (in_array($firstNum, ['2', '3'])) {
                            $bg = 'success';
                        }
                        elseif ($firstNum == '4') {
                            $bg = 'warning';
                        }
                        elseif ($firstNum == '5') {
                            $bg = 'danger';
                        }

                        return Html::tag('span', $model->statusCode, ['class' => "badge bg-{$bg} fs-6"]);
                    }
                ],                
                'statusText:ntext',                
                [
                    'attribute' => 'user',
                    'format' => 'raw',
                    'value' => function(Log $model) {
                        return Html::tag('i', '', ['class' => 'far fa-user'])
                            . ' ' 
                            . $model->user 
                            . (!$model->userModel ?:
                                '<br />'
                                . Html::tag('span', $model->userModel->fio, [
                                    'data-bs-target' => 'tooltip',
                                    'data-bs-title' => $model->userModel->department,
                                ])
                            );                            
                    },
                ],
                [
                    'attribute' => 'log_time',
                    'format' => 'html',
                    'value' => function(Log $model) {
                        return '<i class="far fa-clock"></i> ' . 
                            Yii::$app->formatter->asRelativeTime($model->log_time)
                            . ' (' . Yii::$app->formatter->asDatetime($model->log_time) . ')';
                    }
                ],                                     
            ],
            'toolbar' => [[
                'content' => 
                    Html::button('<i class="fas fa-refresh"></i> Обновить', [
                        'id' => 'btn-refresh',
                        'class' => 'btn btn-primary btn-sm',                        
                    ])
                    . Html::button('<i class="fas fa-trash"></i> Удалить', [
                        'id' => 'btn-delete', 
                        'class' => 'btn btn-danger btn-sm',
                        'data-url' => Url::to(['/log/log/delete']),
                        'disabled' => 'disabled',
                    ])
                    . Html::button('<i class="fas fa-eraser"></i> Очистить таблицу', [
                        'id' => 'btn-clear',
                        'class' => 'btn btn-secondary btn-sm',
                        'data-url' => Url::to(['/log/log/truncate']),
                    ]),
            ]],
            'panel' => [
                'type' => 'secondary',
            ],
                
        ]); ?>
        
        </div>

    </div>

<?php 
$this->registerJs(<<<JS
    
    $('[data-bs-target="tooltip"]').tooltip()
    
    $('#btn-delete').on('click', function() {        
        let ids = []
        let url = $(this).data('url')
        $(this).prop('disabled', true)
        $(this).append(' <i class="fas fa-circle-notch fa-spin"></i>')

        $('input:checkbox:checked[name="selection[]"]').each(function() { 
            ids.push($(this).val())            
        })    
        $.ajax({
            method: 'post',
            url: url,
            data: {
                'ids': ids
            }
        })
        .done(() => $.pjax.reload({ container: '#pjax-log-index', async: false }))
    })

    $('input:checkbox[name="selection[]"]').on('change', function() {
        const countChecked = $('input:checkbox:checked[name="selection[]"]').length
        $('#btn-delete').prop('disabled', countChecked === 0)
    })

    $('#btn-clear').on('click', function() {
        if (!confirm('Вы уверены что хотите очистить таблицу?')) {
            return false
        }
        $(this).prop('disabled', true)
        $(this).append(' <i class="fas fa-circle-notch fa-spin"></i>')

        const url = $(this).data('url')
        $.ajax({
            method: 'post',
            url: url           
        })
        .done(() => $.pjax.reload({ container: '#pjax-log-index', async: false }))
    })

    $('#btn-refresh').on('click', function() {
        $(this).prop('disabled', true)
        $(this).append(' <i class="fas fa-circle-notch fa-spin"></i>')
        $.pjax.reload({ container: '#pjax-log-index', async: false })
    })
    
JS); ?>

    <?php Pjax::end() ?>

</div>
