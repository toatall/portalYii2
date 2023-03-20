<?php
/** @var yii\web\View $this */
/** @var array $queryResult */

use app\models\conference\AbstractConference;
use yii\bootstrap5\Html;
?>
<ul id="conference-list-today" class="list-group">
    <?php foreach ($queryResult as $type => $item): ?>
        <li class="list-group-item">        
            <h6>
                <?= ($label = AbstractConference::getLabelType($type)) ?>      
            </h6>
            <?php if ($item != null && count($item)): ?>
                <?php foreach ($item as $row): ?>
                <span style="font-size: large">
                    <?= Html::a('<span class="badge bg-'.($row->isFinished() ? 'success' : 'secondary') . ' fs-6">' . $row->time_start . '</span>', ['/conference/view', 'id'=>$row['id']], [
                        'class' => 'mv-link',
                        'data-bs-toggle' => 'popover',
                        'data-bs-trigger' => 'hover',
                        'data-bs-original-title' => $row->getTitle(),
                        'data-bs-content' => $row->accessShowAllFields() ? $row->members_people : '',
                        'target' => '_blank',
                    ]) ?>
                </span>
                <?php endforeach; ?>
            <?php else: ?>
                <small>
                    <?= $label ?> сегодня не запланированы
                </small>
            <?php endif; ?>
        </li>                        
    <?php endforeach; ?>
</ul>
<?php $this->registerJs(<<<JS
    $('#conference-list-today [data-bs-toggle="popover"]').popover();   
JS);
?>