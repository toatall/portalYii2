<?php

use app\models\Organization;
use yii\bootstrap5\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Налоговые органы';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="protocol-index">

    <h1 class="display-5 border-bottom"><?= Html::encode($this->title) ?></h1>

    <?php 
    $models = $dataProvider->getModels();
    if ($models && count($models) > 0): ?>
        <div class="row">
        <?php foreach($models as $model): 
            /** @var Organization $model */
            ?>            
            <div class="col-3 mb-3">
                <a href="<?= Url::to(['view', 'id'=>$model->code]) ?>" class="link-noline">
                <div class="card rounded shadow">
                    <img src="/public/assets/portal/img/builder.png" style="max-width: 10rem;" class="card-img p-3 m-auto" />
                    <div class="card-header text-center">
                        <p class="lead">
                            <?= $model->name ?>
                        </p>
                    </div>
                </div>
                </a>
            </div>
        <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="alert alert-secondary">Нет данных</div>
    <?php endif; ?>

</div>
<?php $this->registerCss(<<<CSS
    a.link-noline, a.link-noline:visited, a.link-noline:active {
        text-decoration: none;
    }

CSS); ?>