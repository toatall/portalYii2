<?php

/** @var yii\web\View $this */
/** @var app\models\Organization[] $organizations */

?>

<div class="list-group">
    <?php foreach ($organizations as $organization): ?>
    <a href="<?= \yii\helpers\Url::to(['/admin/user/change-organization', 'code'=>$organization->code]) ?>" 
       class="list-group-item<?= \Yii::$app->userInfo->current_organization == $organization->code ? ' active' : '' ?> list-group-item-action"><?= $organization->fullName ?></a>
    <?php endforeach; ?>
</div>

