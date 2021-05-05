<?php
/* @var $this \yii\web\View */
/* @var $model array */

$this->title = 'Мгновения службы';
$this->params['breadcrumbs'][] = ['label'=>'30-летие налоговых органов', 'url'=>['/thirty/index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="happy-birthday">
    <h1 class="head"><?= $this->title ?></h1>

    <div class="row gallery">
        <?php foreach ($model as $item): ?>
            <div class="col-sm-3">
                <div class="panel panel-default">
                    <div class="panel-body text-center ">
                        <a href="<?= $item['photo'] ?>" class="" target="_blank"  data-caption="<h1><?= $item['description'] ?></h1>">
                            <img src="<?= $item['photo'] ?>" class="thumbnail" style="height: 20vh; margin: 0 auto;" />
                        </a>
                    </div>
                    <div class="panel-footer">
                        <strong><?= $item['description'] ?></strong>
                        <hr />
                        <?= $item['code_ifns'] ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
