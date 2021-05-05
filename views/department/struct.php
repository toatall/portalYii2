<?php
/* @var $this yii\web\View */
/* @var $model \app\models\department\Department */
/* @var $arrayCard array */

$this->title = 'Структура';
$this->params['breadcrumbs'][] = ['label' => 'Отделы', 'url' => ['/department/index']];
$this->params['breadcrumbs'][] = ['label' => $model->department_name, 'url' => ['/department/view', 'id'=>$model->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="content content-color">
    <h1><?= $model->department_name . ' (структура)' ?></h1>
    <hr />

    <?php if (is_array($arrayCard) && count($arrayCard) > 0): ?>
    <?php foreach ($arrayCard as $structRow): ?>

        <div class="row">
            <div class="gallery">
                <?php foreach ($structRow as $struct): ?>

                    <div class="col-sm-5 col-md-3" style="margin:0 auto;">
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <a href="<?= $struct['user_photo'] ?>" target="_blank">
                                    <img src="<?= $struct['user_photo'] ?>" class="thumbnail" style="max-width:100%; max-height: 200px; margin: 0 auto;" alt="<?= $struct['user_fio'] ?>" />
                                </a>
                            </div>
                            <div class="panel-heading" style="height: 200px; margin-top:10px; overflow: auto;">
                                <div class="text-center text-muted">
                                    <h4 class="head text-uppercase" style="font-weight: bolder;"><?= $struct['user_fio'] ?></h4>
                                    <p><?= $struct['user_position'] ?></p>
                                    <p><?= $struct['user_rank'] ?></p>
                                    <p><?= $struct['user_telephone'] ?></p>
                                    <p><?= $struct['user_resp'] ?></p>
                                </div>
                            </div>
                        </div>
                    </div>

                <?php endforeach; ?>
            </div>
        </div>
    <?php endforeach; ?>

    <?php else: ?>

        <div class="alert alert-warning">Нет данных</div>

    <?php endif; ?>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        baguetteBox.run('.gallery', {
            captions: function (element) {
                return element.getElementsByTagName('img')[0].alt;
            }
        });
    });
</script>
