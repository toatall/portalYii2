<?php 
/** @var \yii\web\View $this */
/** @var app\modules\calendar\models\Calendar[] $model */
/** @var app\modules\calendar\models\CalendarColor $modelColor */
/** @var string $date */

use app\modules\calendar\models\Calendar;
use yii\bootstrap5\Html;
use yii\widgets\Pjax;

?>

<?php Pjax::begin(['id'=>'pjax-calendar-view', 'timeout'=>false, 'enablePushState' => false]); ?>

<?php if (Calendar::roleModerator()): ?>
    <div class="border-bottom mb-2">
        <?= Html::a('Добавить', ['/calendar/default/create', 'date'=>$date], ['class' => 'btn btn-primary btn-sm mb-2', 'pjax' => 1]) ?>
    </div>
<?php endif; ?>

<?php if ($model != null): ?>
    <?php 
   
    // список событий
    foreach ($model as $group => $items): ?>
        <div class="card mb-2">
            <div class="card-header"><?= $group ?></div>
            <div class="card-body">
                <table class="table">                
                <?php foreach ($items as $item): ?>
                    <tr>
                        <td>
                            <span class="badge bg-<?= $item->color ?> fa-1x">
                                <?= $item->description ?>
                            </span> 
                            
                        </td>
                        <td>
                            <?php if ($item->is_global): ?>
                                <span class="text-success f-size-14" title="Глобальное событие"><i class="fas fa-globe"></i></span>
                            <?php endif; ?>
                        </td>
                        <?php if (Calendar::roleModerator()): ?>
                        <td>
                            <div class="btn-group">
                                <?= Html::a('<i class="fas fa-pencil-alt"></i>', ['update', 'id'=>$item->id], ['class'=>'btn btn-primary', 'pjax'=>true]) ?>
                                <?= Html::a('<i class="fas fa-trash"></i>', ['delete', 'id'=>$item->id], [
                                    'class'=>'btn btn-danger', 
                                    'data' => [
                                        'confirm' => 'Вы уверены, что хотите удалить?',
                                        'method' => 'post',                               
                                    ],
                                    'data-pjax' => true,
                                ]) ?>
                            </div>
                        </td>
                        <?php endif; ?>
                    </tr>
                <?php endforeach; ?>
                </table>
            </div>
        </div>
    <?php endforeach; 
        
    ?>
<?php else: ?>

    <div class="alert alert-warning">
        Нет данных
    </div>

<?php endif; ?>

<?php Pjax::end(); ?>