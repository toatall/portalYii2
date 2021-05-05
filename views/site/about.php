<?php

/* @var $this yii\web\View */

use yii\helpers\Html;

$this->title = 'About';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-about">
    <h1 class="mv-hide"><?= Html::encode($this->title) ?></h1>

    <p>
        This is the About page. You may modify the following file to customize its content:
        <span class="label label-info mv-hide1">Test</span>
        <code><?= print_r($m) ?></code>

    </p>
    <form action="<?= yii\helpers\Url::to(['/site/test']) ?>" class="mv-form" enctype="multipart/form-data">
        <input type="text" class="form-control" name="ds" />
        <input type="submit" value="Send" class="btn btn-primary" />
        <?= yii\bootstrap\Html::fileInput('fileX') ?>
    </form>

    <code><?= __FILE__ ?></code>
</div>
