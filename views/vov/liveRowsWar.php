<?php
/* @var $this \yii\web\View */
/* @var $searchModel \app\models\news\NewsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $videos array */

use yii\widgets\ListView;
use yii\helpers\Html;
?>

<?= ListView::widget([
    'dataProvider' => $dataProvider,
    'itemView' => '/news/_list',
    'layout' => "{items}\n{pager}",
]) ?>

<?php foreach ($videos as $category=>$files): ?>
    <h3 class="head"><?= $category ?></h3>

    <div class="row">
        <div class="gallery">
            <?php foreach ($files as $file): ?>

                <div class="col-sm-5 col-md-3" style="margin:0 auto;">
                    <div class="panel panel-default" style="">
                        <div class="panel-body">

                            <a href="<?= $file ?>" target="_blank">
                                <video controls="" width="500" style="height: 200px; max-width: 300px;" class="col-md-12 col-sm-12"><source src="<?= $file ?>"></video>
                            </a>

                        </div>
                        <div class="panel-heading" style="height: 200px; margin-top:10px; overflow: auto;">
                            <div class="text-center text-muted">
                                <h4 class="head text-uppercase"><?= basename($file) ?></h4>
                                <p><?= Html::a('Скачать', $file, ['class' => 'btn btn-primary']) ?></p>
                            </div>
                        </div>
                    </div>
                </div>

            <?php endforeach; ?>
        </div>
    </div>
    <div class="clearfix"></div>
<?php endforeach; ?>