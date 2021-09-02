<?php
/** @var yii\web\View $this */
/** @var array $queryResult */

use app\models\conference\AbstractConference;
use yii\bootstrap4\Html;
?>
<ul class="list-group">
    <?php foreach ($queryResult as $type => $item): ?>
        <li class1="ml-3 font-weight-lighter" class="list-group-item">        
            <h6>
                <?= ($label = AbstractConference::getLabelType($type)) ?>      
            </h6>
            <?php if ($item != null && count($item)): ?>
                <?php foreach ($item as $row): ?>
                <span style="font-size: large">
                    <?= Html::a('<span class="badge badge-'.($row->isFinished() ? 'success' : 'secondary') . ' fa-sm">' . $row->time_start . '</span>', ['/conference/view', 'id'=>$row['id']], [
                        'class' => 'mv-link',
                        'data-toggle' => 'popover',
                        'data-trigger' => 'hover',
                        'data-original-title' => $row->getTitle(),
                        'data-content' => $row->accessShowAllFields() ? $row->members_people : '',
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
