<?php

/* @var $this yii\web\View */
/* @var $message string */
/* @var $typeMessage string */

if (!isset($typeMessage)) {
    $typeMessage = 'alert-info';
}
?>
<div class="alert <?= $typeMessage ?>">
    <?= $message ?>
</div>
