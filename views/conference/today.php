<?php
use app\models\conference\AbstractConference;
/* @var $this yii\web\View */
/* @var $queryResult AbstractConference[][] */

use yii\helpers\Html;
?>
<ul class="list-unstyled">
    <?php foreach ($queryResult as $type => $item): ?>
    <li class="nav-header"><?= ($label = AbstractConference::getLabelType($type)) ?></li>
    <div style="padding: 0 15px; margin-bottom: 10px;">

        <?php if ($item != null && count($item)): ?>
            <?php foreach ($item as $row): ?>
            <span style="font-size: large">
                <?= Html::a('<span class="label label-default">' . $row['time_start'] . '</span>', ['/conference/view', 'id'=>$row['id']], [
                    'class' => 'mv-link',
                    'data-toggle' => 'popover',
                    'data-trigger' => 'hover',
                    'data-original-title' => $row['theme'],
                    'data-content' => $row->members_people,
                    'target' => '_blank',
                ]) ?>
            </span>
            <?php endforeach; ?>

        <?php else: ?>
            <li class="small">
                <?= $label ?> сегодня не запланированы
            </li>
        <?php endif; ?>

    </div>
    <?php endforeach; ?>
</ul>

<?php $this->registerCss(<<<CSS
    div.popover {
        width:300px;
    }
CSS
); ?>
