<?php
/** @var array $listEmployee  */
/** @var yii\web\View $this */
/** @var array $tasksToday */
/** @var array $results */

use app\modules\contest\models\photokids\PhotoKids;
use app\widgets\CommentWidget;
use yii\bootstrap5\Html;
use yii\helpers\Url;

$this->title = 'Все мы родом из детства';
?>


<?= Html::button('<i class="fas fa-arrow-circle-left"></i> На портал', [
    'style' => 'position: fixed; left:2rem; top: 5%; font-size: 1rem;',   
    'class' => 'btn btn-outline-secondary',
    'title' => 'На портал',
    'id' => 'btn-back',
    'data-url' => Url::to(['/']),
]) ?>

<?php 
$index = 1;
if ($tasksToday) {
    foreach($tasksToday as $task): ?>
        <div class="task">
            <div class="card mt-4 new-background" style="border-width:5px;">
                <div class="card-header display-4 text-center text-muted">Задание от <?= date('d.m.Y H:i', strtotime($task['datetime_start'])) ?></div>
                <div class="card-body w-100 position-relative">
                    <div class="static-thumbnails text-center">                                                                    
                        <?php if (($images = PhotoKids::getImages($task['id']))): ?>
                            <?php foreach($images as $image): ?>
                            <a href="<?= $image ?>">
                                <img src="<?= $image ?>" style="height: 30rem;" class="img-thumbnail" />
                            </a>
                            <?php endforeach; ?>
                        <?php endif; ?>

                    </div>
                </div>    
                <div class="card-footer">
                    <div class="row">
                        <div style="width: 10rem; padding-top: 7px;" class="text-center font-weight-bolder text-muted">
                            Ваш ответ
                        </div>
                        <div class="col">
                            <?= Html::beginForm('', 'post', ['class' => 'form-answer']) ?>
                            <?= kartik\select2\Select2::widget([
                                'name' => 'answer',
                                'data' => $listEmployee,
                                'pluginOptions' => [
                                    'placeholder' => 'Выберите сотрудника',
                                ],
                                'options' => [
                                    'class' => 'select2',
                                ],
                            ]) ?>
                        </div>
                        <div style="width: 10rem;" class="text-center">
                            <?= Html::submitButton('Сохранить <span class="badge badge-light" id="span-counter"></span>', ['class' => 'btn btn-secondary btn-save']) ?>
                        </div>                    
                    </div>    
                    <?= Html::hiddenInput('id', $task['id']) ?>
                    <?= Html::endForm() ?>            
                </div>
            </div>
        </div>
<?php 
        $index++;
    endforeach; 
}
else {
    ?>
    <div class="card card-body mt-4 text-muted text-center new-background" style="border-width: 5px;">
        <p class="display-2" style="text-shadow: 3px 1px #aaa;">Заданий нет</p>
    </div>
    <?php
}
?>

<hr class="mt-5" style="border-width:5px;" />
<?php if ($results): ?>
    <h3 class="display-2">Кто был загадан?</h3>

    <div class="row">
        <?php foreach($results as $result): ?>
            <div class="col-6">
                <div class="card new-background">
                    <div class="card-header">
                        <p class="lead"><?= $result['fio'] ?>
                            <br />
                            <small class="text-muted">
                                от <?= date('d.m.Y H:i', strtotime($result['datetime_start'])) ?>
                            </small>
                        </p>                        
                    </div>
                    <div class="card-body static-thumbnails text-center">
                        <?php if (($images = PhotoKids::getImages($result['id']))): ?>
                            <?php foreach($images as $image): ?>
                                <a href="<?= $image ?>">
                                    <img src="<?= $image ?>" style="height: 10rem;" class="img-thumbnail" />
                                </a>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>    

<?php endif; ?>
<div class="mb-4 mt-4">
    <?= CommentWidget::widget([
        'modelName' => 'PhotoKids',
        'modelId' => 0,
    ]) ?>
</div>

<?php

$this->registerCss(<<<CSS
    .comment-index div.card {
        background: rgba(240, 240, 240, .4);
    }
    .comment-index h4 {
        color: #888;
    }
    .new-background {
        background: rgba(240, 240, 240, .4);
    }
CSS);


$this->registerJs(<<<JS

$('.static-thumbnails').each(function() {
    lightGallery($(this).get(0), {
        addClass: 'lg-custom-thumbnails',  
        appendThumbnailsTo: '.lg-outer',
        animateThumb: false,
        allowMediaOverlap: true,
    });
});

$('.form-answer').on('submit', function() {
    const url = $(this).attr('action');
    const select2 = $(this).children('.select2');
    const data = $(this).serialize();
    const divParent = $(this).parents('div.task');

    if (select2.val() == '') {
        alert('Не выбран сотрудник!');
        return false;
    }

    $.ajax({
        url: url,
        method: 'post',
        data: data,
    })
    .done(function(data) {       
        divParent.html('<div class="alert alert-success new-background"><h3>Спасибо! Ваш голос учтен!</h3></div>');
    });
    
    return false;
});

function getRandom(min, max, z) {
    var r = Math.floor(Math.random() * (max - min)) + min;
    if (z) {
        if (Math.floor(Math.random() * 2) > 0) {
            r *= -1; 
        }
    }
    return r;
}

$('#btn-back').on('click', function() {
        
    const url = $(this).data('url');
    
    $(this).prop('disabled', true);

    setTimeout(() => {
        window.location = url;
    }, 5000);

    window.scrollTo(0, 0);
    $('html, body').animate({ scrollTop: document.body.scrollHeight - window.screen.height + 400 }, 5000);


    $('p,h1,h2,h3,h4,h5,.card,.col').each(function() {
        $(this).css('transition', 'all 1s');        
    });

    $('p,h1,h2,h3,h4,h5,.card,.col').each(function() {
        $(this).css('transform', 'rotate(' + getRandom(2, 750, true) + 'deg) '
            + 'scale(' + (getRandom(8,12)/10) + ', ' + (getRandom(8,12)/10) + ') '            
            + 'translateY(' + getRandom(-300, 5, true) + 'px)');
        
        $(this).css('background-color', 'rgba(' + getRandom(0, 255) + ', ' + getRandom(0, 255) + ', ' + getRandom(0, 255) + ', ' + (getRandom(1,4)/10) + ')');
    });

    $('img').each(function() {
        $(this).css('transition', 'all .55s'); 
        $(this).css('transform', 'rotate(' + getRandom(2, 342, true) + 'deg)');        
    });

    
    $('.stroke,.display-2,.display-4').each(function() {        
        $(this).css('transition', 'all 1s'); 
    });

    $('.stroke,.display-2,.display-4').each(function() {        
        $(this).css('font-size', getRandom(30, 150) + 'px');
        $(this).css('color', 'rgba(' + getRandom(0, 255) + ', ' + getRandom(0, 255) + ', ' + getRandom(0, 255) + ', ' + (getRandom(1,10)/10) + ')');
    });

       
    return false;
    
});

JS);

?>
