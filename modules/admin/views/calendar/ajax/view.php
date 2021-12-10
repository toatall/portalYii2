<?php 
/** @var \yii\web\View $this */
/** @var \app\models\calendar\Calendar|null $model */

?>

<?php if ($model !== null): ?>
    <?php 
    // подсветка заголовка выбранным фоном для даты
    $this->registerJs(<<<JS
        $(modalViewer.modalTitle).html('<span class="badge badge-{$model->color} fa-1x">{$model->date}</span>');
    JS);

    // список событий
    foreach ($model->getDataWithGroup() as $group => $items): ?>
        <div class="card mb-2">
            <div class="card-header"><?= $group ?></div>
            <div class="card-body">
                <?php foreach ($items as $item): ?>
                    <?= $item->description ?><br />
                <?php endforeach; ?>
            </div>
        </div>
    <?php endforeach; ?>
<?php else: ?>

    <div class="alert alert-warning">
        Событий нет
    </div>

<?php endif; ?>
