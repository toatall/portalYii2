<?php
/** @var \yii\web\View $this */
/** @var array $result */
/** @var \app\models\thirty\ThirtyVeteran $model */

use app\assets\FancyappsUIAsset;
FancyappsUIAsset::register($this);

$this->title = 'Видео-открытки!';
$this->params['breadcrumbs'][] = ['label' => 'Проекты', 'url' => ['/project']];
$this->params['breadcrumbs'][] = ['label'=>'30-летие налоговых органов', 'url'=>['/project/thirty/default/index']];
$this->params['breadcrumbs'][] = $this->title;

$data = [
    ['name' => 'Межрайонная ИФНС России № 1 по Ханты-Мансийскому автономному округу - Югре', 'video'=>'/files_static/video/8600/8601.mp4'],
    ['name' => 'Межрайонная ИФНС России № 2 по Ханты-Мансийскому автономному округу - Югре', 'video'=>'/files_static/video/8600/8606.mp4'],
    ['name' => 'Межрайонная ИФНС России № 3 по Ханты-Мансийскому автономному округу - Югре', 'video'=>'/files_static/video/8600/8610.mp4'],
    ['name' => 'Межрайонная ИФНС России № 5 по Ханты-Мансийскому автономному округу - Югре', 'video'=>'/files_static/video/8600/8607.mp4'],
    ['name' => 'Межрайонная ИФНС России № 6 по Ханты-Мансийскому автономному округу - Югре', 'video'=>'/files_static/video/8600/8603.mp4'],
    ['name' => 'Межрайонная ИФНС России № 8 по Ханты-Мансийскому автономному округу - Югре', 'video'=>'/files_static/video/8600/8611.mp4'],
    ['name' => 'Межрайонная ИФНС России № 9 по Ханты-Мансийскому автономному округу - Югре', 'video'=>'/files_static/video/8600/8624.mp4'],
    ['name' => 'ИФНС России по Сургутскому району Ханты-Мансийского автономного округа – Югры', 'video'=>'/files_static/video/8600/8617new.mp4'],
];

?>
<div class="video-card">
    <div class="row mv-hide">
        <div class="col border-bottom mb-2">
            <p class="display-4">
            <?= $this->title ?>
            </p>    
        </div>    
    </div>

    <div class="row">
        <?php foreach ($data as $item): ?>
        <div class="col-3 mb-2">
            <div class="card">
                <div class="card-header text-center">
                    <strong><?= $item['name'] ?></strong>
                </div>
                <div class="card-body text-center">
                    <a href="<?= $item['video'] ?>" data-fancybox class="btn btn-light" style="font-size: 20px;">
                        <i class="fab fa-youtube text-danger"></i> <strong>Просмотр</strong>
                    </a>                
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>
<?php $this->registerJs(<<<JS
    Fancybox.bind('[data-fancybox]', {});
JS); ?>