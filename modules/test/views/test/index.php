<?php
use app\modules\test\models\Test;
use yii\bootstrap5\Html;
use yii\bootstrap5\LinkPager;

/** @var yii\web\View $this **/
/** @var yii\data\ActiveDataProvider $dataProvider **/

$this->title = 'Тесты';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="test-index">

    <h1 class="display-5 border-bottom">
        <?= Html::encode($this->title) ?>
    </h1>    

    <?php if (Test::canManager()): ?>
    <div class="border-bottom mt-2 pb-2 mb-2 mx-3">
        <?= Html::a('Добавить', ['create'], ['class'=>'btn btn-primary']) ?>
    </div>
    <?php endif; ?>
    
    <div class="row">        
    <?php foreach ($dataProvider->getModels() as $model): ?>
        <?php /** @var app\modules\test\models\Test $model **/ ?>
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <!-- Card Header - Dropdown -->
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-gray">
                        <?= $model->name ?>
                    </h6>
                    <div class="dropdown no-arrow">
                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                        </a>                        
                        <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                            aria-labelledby="dropdownMenuLink">
                            <?php if (Test::canManager()): ?>
                                <div class="dropdown-header">Управление</div>
                                <?= Html::a('Просмотреть тест', ['/test/test/view', 'id'=>$model->id], ['class'=>'dropdown-item']) ?>
                                <?= Html::a('Изменить тест', ['/test/test/update', 'id'=>$model->id], ['class'=>'dropdown-item']) ?>                               
                                <?= Html::a('Права', ['/test/access/index', 'idTest'=>$model->id], ['class'=>'dropdown-item']) ?>
                                <?= Html::a('Вопросы', ['/test/question/index', 'idTest'=>$model->id], ['class'=>'dropdown-item']) ?>                                
                            <?php endif; ?>
                            <?php if ($model->canStatisticTest()): ?>
                                <div class="dropdown-divider"></div>
                                <div class="dropdown-header">Статистика</div>
                                <?= Html::a('Общая', ['/test/statistic/general', 'id'=>$model->id], ['class'=>'dropdown-item mv-link']) ?>
                                <?= Html::a('По сотрудникам', ['/test/statistic/users', 'id'=>$model->id], ['class'=>'dropdown-item link-modal']) ?>
                                <?= Html::a('По вопросам', ['/test/statistic/questions', 'id'=>$model->id], ['class'=>'dropdown-item link-modal']) ?>
                                <?= Html::a('Оценки', ['/test/statistic/opinion', 'id'=>$model->id], ['class'=>'dropdown-item link-modal']) ?>                                
                            <?php endif; ?>
                        </div>                    
                    </div>
                </div>
                <!-- Card Body -->
                <?php $status = $model->processStatus(); ?>      
                <div class="card-body">   
                    <table class="table">
                        <tr>
                            <th>Статус</th>
                            <td>                                
                                <?php if ($status == Test::PROCESS_STATUS_NOT_START): ?>
                                <span class="mr-2">
                                    <i class="fas fa-circle text-secondary"></i> Не начался
                                </span>
                                <?php endif; ?>

                                <?php if ($status == Test::PROCESS_STATUS_RUNNING): ?>
                                <span class="mr-2">
                                    <i class="fas fa-circle text-success"></i> Запущен
                                </span>
                                <?php endif; ?>

                                <?php if ($status == Test::PROCESS_STATUS_FINISHED): ?>
                                <span class="mr-2">
                                    <i class="fas fa-circle text-info"></i> Завершен
                                </span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <th>Дата начала</th>
                            <td><?= Yii::$app->formatter->asDatetime($model->date_start) ?></td>
                        </tr>
                        <tr>
                            <th>Дата окончания</th>
                            <td><?= Yii::$app->formatter->asDatetime($model->date_end) ?></td>
                        </tr>
                        <?php if ($status == Test::PROCESS_STATUS_RUNNING): ?>
                        <tr>
                            <td colspan="2">
                                <?= Html::a('Начать <i class="fas fa-play-circle"></i>', ['/test/public/start', 'id'=>$model->id], ['class' => 'btn btn-primary btn-start']) ?>
                            </td>
                        </tr>
                        <?php endif; ?>
                    </table>                                     
                
                </div>
            </div>
        </div>
        
    <?php endforeach; ?>
        
    </div>
    
    <?= LinkPager::widget([
        'pagination' => $dataProvider->pagination,
        'firstPageLabel' => '<span title="Первая страница"><i class="fas fa-chevron-left"></i><i class="fas fa-chevron-left"></i></span>',
        'prevPageLabel' => '<i class="fas fa-chevron-left" title="Предыдущая страница"></i>',
        'lastPageLabel' => '<span title="Последняя страница"><i class="fas fa-chevron-right"></i><i class="fas fa-chevron-right"></i></span>',
        'nextPageLabel' => '<i class="fas fa-chevron-right" title="Следующая страница"></i>',        
    ]) ?>

</div>
<?php $this->registerJs(<<<JS
    $('.btn-start').on('click', function () {
        return confirm('Вы уверены, что хотите начать?');
    });   
    // $('.link-modal').on('click', function() {
    //     const link = $(this);
    //     const dialog = $('#modal-dialog');
    //     const dialogTitle = dialog.find('.modal-title');
    //     const dialogBody = dialog.find('.modal-body');
    //     const loader = '<i class="fas fa-circle-notch fa-spin fa-2x"></i>';

    //     dialogTitle.html(loader);
    //     dialogBody.html(loader);
    //     dialog.modal('show');

    //     $.get(link.attr('href'))
    //     .done(function(resp) {
    //         dialogTitle.html(resp.title);
    //         dialogBody.html(resp.body);
    //     })
    //     .fail(function(err) {
    //         dialogTitle.html('Ошибка');
    //         dialogBody.html('<div class="alert alert-danger">' + err.responseText + '</div>');
    //     });

    //     return false;
    // });
JS);