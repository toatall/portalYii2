<?php
/** @var yii\web\View $this */
/** @var array $queryResult */

use yii\bootstrap5\Html;
?>
<ul id="conference-list-today" class="list-group">
    <?php foreach ($queryResult as $type => $item): ?>
        <li class="list-group-item">        
            <h6>
                <?= ($label = $item['label']) ?>      
            </h6>
            <?php if ($item['data'] != null && count($item['data'])): ?>
                <?php foreach ($item['data'] as $row): ?>
                <span style="font-size: large">
                    <?= Html::a('<span class="badge bg-'.($row->isFinished() ? 'success' : 'secondary') . ' fs-6">' 
                        . $row->time_start . '</span>', ['/meeting/' . $type . '/view', 'id'=>$row['id']], [
                        'class' => 'mv-link',
                        'data-bs-html' => 'true',
                        'data-bs-toggle' => 'popover',
                        'data-bs-trigger' => 'hover',
                        'data-bs-original-title' => $row->getTitle(),
                        'data-bs-content' => $item['isViewerAllFields'] ? $row->getDescription() : $item->getDescription(true),
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