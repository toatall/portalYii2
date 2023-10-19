<?php
    
/** @var \yii\web\View $this */
/** @var int $widgetId */
/** @var string $message */
/** @var int $idModelAutomationRoutine */
/** @var string $btnRedirectTitle */
/** @var string $btnRejectTitle */
use yii\bootstrap5\Html;
use yii\helpers\Url;

$fio = \Yii::$app->user->identity->fio;
$fioArray = explode(' ', $fio);
array_shift($fioArray);
$wellcomeUser = preg_replace(['/.*вна$/', '/.*ич$/'], ['Уважаемая', 'Уважаемый'], $fio);
?>
<p><?= ''//$wellcomeUser ?> <?= implode(' ', $fioArray) ?>!</p>
<hr />
<div>
    <?= $message ?>
</div>
<hr />
<div class="d-flex justify-content-between mt-3">
    <div class="btn-group">
        <?= Html::button($btnRedirectTitle, [
            'id' => 'automation-routine-notify-btn-redirect',
            'class' => 'btn btn-primary btn-sm', 
            'data-url' => Url::to(['automation-routine/view', 'id' => $idModelAutomationRoutine])]) ?>
        <?= Html::button($btnRejectTitle, ['id' => 'automation-routine-notify-btn-reject', 'class' => 'btn btn-light btn-sm']) ?>
    </div>
    <?= Html::button('<i class="fas fa-close"></i> Закрыть', ['id' => 'automation-routine-notify-btn-close', 'class' => 'btn btn-secondary btn-sm']) ?>
</div>