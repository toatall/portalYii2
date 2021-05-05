<?php
/* @var $this \yii\web\View */
/* @var $model array */

$this->title = 'Сквозь время';
$this->params['breadcrumbs'][] = ['label'=>'30-летие налоговых органов', 'url'=>['/thirty/index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="through-time">
    <h1 class="head"><?= $this->title ?> <button id="btn-doodl-today" class="btn btn-default"><i class="fas fa-sync"></i></button></h1>

    <div class="row gallery">
        <?php foreach ($model as $item): ?>
        <div class="col-sm-6">
            <table class="table well">
                <tr>
                    <td>
                        <div class="panel panel-default">
                            <div class="panel-body text-center ">
                                <a href="<?= $item['photo_old'] ?>" data-caption="<h1><?= $item['description_old'] ?></h1><?= $item['code_ifns'] ?>">
                                    <img src="<?= $item['photo_old'] ?>" class="thumbnail" style="height: 20vh; margin: 0 auto;" />
                                </a>
                            </div>
                            <div class="panel-footer">
                                <strong><?= $item['description_old'] ?></strong><br />
                                <?= $item['code_ifns'] ?>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="panel panel-default">
                            <div class="panel-body text-center ">
                                <a href="<?= $item['photo_new'] ?>" data-caption="<h1><?= $item['description_new'] ?></h1><?= $item['code_ifns'] ?>">
                                    <img src="<?= $item['photo_new'] ?>" class="thumbnail" style="height: 20vh; margin: 0 auto;" />
                                </a>
                            </div>
                            <div class="panel-footer">
                                <strong><?= $item['description_new'] ?></strong><br />
                                <?= $item['code_ifns'] ?>
                            </div>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
        <?php endforeach; ?>
    </div>
</div>
<?php $this->registerJS(<<<JS
    
     $(document).ready(function() {
        $('#btn-doodl-today').on('click', function () {
            $('.black-wall').css('animation-play-state', 'running');
            $('.img-thirty').css('animation-play-state', 'running');
            $('.bounce-2').css('animation-play-state', 'running');
            $(this).prop('disabled', true);
            $(this).children('i').addClass('fa-spin');
            setTimeout(function() {
                $('#btn-doodl-today').hide();
            }, 10000);
        });
    });
    
JS
);
?>
