<?php

/* @var $this yii\web\View */
/* @var $organizations[] app\models\Organization */

?>

<div class="list-group">
    <?php foreach ($organizations as $organization): ?>
    <a href="<?= \yii\helpers\Url::to(['/admin/user/change-organization', 'code'=>$organization->code]) ?>" 
       class="list-group-item<?= \Yii::$app->userInfo->current_organization == $organization->code ? ' active' : '' ?>"><?= $organization->fullName ?></a>
    <?php endforeach; ?>
</div>

