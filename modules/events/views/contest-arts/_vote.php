<?php
/* @var $this yii\web\View */
/* @var $modelVotes array */

use yii\bootstrap\Html;
use kartik\widgets\StarRating;
use yii\helpers\Url;

/**
 * Голосование разбить на пошаловый, так интереснее:
 * 1. начальный экран типа предлагаем проголосовать по номинациям таким сяким и кнопку вперед или поехали
 * 2. дальше идут голосовалки
 * 3. и в конце типа спасибо и т.п.
 */

$index = 2;
?>

<div id="step1" class="step">
    <div class="card">
        <div class="card-header">
            <h1 style="font-weight: bolder;">Уважаемые коллеги!</h1>
        </div>
        <div class="card-body">
            Приглашаем принять участие в голосовании по номинациям: самое достоверное воспроизведение и самое оригинальное название.<br />
            <strong>Внимание! Проголосовать возможно только 1 раз! Вы можете проголосовать за любое количество работ!</strong>
        </div>
        <div class="card-footer">
            <button class="btn btn-primary btn-next" next_step="#step2">Начать <i class="fas fa-arrow-circle-right"></i></button>
        </div>
    </div>
</div>


<?php foreach ($modelVotes as $model): ?>
    <div id="step<?= $index ?>" class="step" style="display: none;">
        <?= Html::beginForm(Url::to(['/events/contest-arts/vote', 'id' => $model['id']]), 'post', ['id' => 'form_' . $index, 'class' => 'form-rating']) ?>
        <div class="card">
            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="card-header">
                            <h1>
                                <?= $model['image_original_title'] ?><br />
                                <small><?= $model['image_original_author'] ?></small>
                            </h1>
                        </div>
                        <div class="card-body">
                            <?= Html::a(Html::img($model['image_original'], 
                                ['class' => 'img-thumbnail border-art-small', 'style'=>'margin: 0 auto; max-height:18em; max-width:100%;',]), 
                                $model['image_original'], ['class' => 'fancybox']) ?>
                        </div>                        
                    </div>                    
                </div>
                <div class="col">
                    <div class="card">
                        <div class="card-header">
                            <h1>
                                <?= $model['image_reproduced_title'] ?><br />
                                <small><?= $model['department_name'] ?></small>
                            </h1>                            
                        </div>
                        <div class="card-body">
                            <?= Html::a(Html::img($model['image_reproduced'], 
                                ['class' => 'img-thumbnail border-art-small', 'style'=>'margin: 0 auto; max-height:18em; max-width:100%;',]), 
                                $model['image_reproduced'], ['class' => 'fancybox']) ?>
                        </div>                        
                    </div>                    
                </div>
            </div>           
            <div class="card-body">                                                                               
                <div class="row">
                    <div class="col text-right">
                        <h4 class="text-secondary" style="margin-top:10px;">Достоверность воспроизведения</h4>
                    </div>
                    <div class="col text-left">
                        <?= StarRating::widget([
                            'name' => 'rating_real_art',
                            'pluginOptions' => [
                                'step' => 1,
                            ],
                        ]) ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col text-right">
                        <h4 class="text-secondary" style="margin-top:10px;">Оригинальность названия</h4>
                    </div>
                    <div class="col text-left">
                        <?= StarRating::widget([
                            'name' => 'rating_original_name',
                            'pluginOptions' => [
                                'step' => 1,
                            ],
                        ]) ?>
                    </div>
                </div>                                                               
            </div>
            <div class="card-footer">
                <button class="btn btn-primary btn-next" next_step="#step<?= $index+1 ?>" form_name="#form_<?= $index ?>">Далее <i class="fas fa-arrow-circle-right"></i></button>               
                <div class="spinner-border text-success" role="status" style="display: none;">
                    <span class="sr-only">Loading...</span>
                </div>
            </div>
        </div>
        <?= Html::endForm(); ?>       
    </div>     
    <?php $index++; ?>
<?php endforeach; ?>

<div id="step<?= $index; ?>" class="step" style="display: none;">
    <div class="card">
        <div class="card-header">
            <h1 style="font-weight: bolder;">Вот и все</h1>
        </div>
        <div class="card-body">
            Спасибо за ваши оценки 
        </div>
        <div class="card-footer">
            <?= Html::a('Завершить', '', ['class' => 'btn btn-primary']) ?>
        </div>
    </div>
</div>

<div class="position-fixed bottom-0 right-0 p-3" style="z-index: 5; right: 0; bottom: 0;">
  <div id="liveToast" class="toast hide" role="alert" aria-live="assertive" aria-atomic="true" data-delay="5000">
    <div class="toast-header">
      <strong class="text-danger">Произошла ошибка!</strong>           
      <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
            <span aria-hidden="true">&times;</span>
      </button>
    </div>
    <div class="toast-body">         
        <p class="text-danger"></p>
    </div>
  </div>
</div>


<?php 
$this->registerJs(<<<JS
    
    function showStep(name)
    {
        $('.step').hide();
        $(name).show(); 
    }
    
    $('.btn-next').on('click', function() {
        $('.alert').hide();
        let thisBtn = $(this);
   
        // send ajax
        if (thisBtn.is('[form_name]')) {
            let form = thisBtn.attr('form_name');
            $(form).submit();            
        }
        else {
            // next slide
            showStep(thisBtn.attr('next_step'));       
        }
    });
            
    $('.form-rating').on('submit', function() {        
        let url = $(this).attr('action');
        let data = $(this).serialize();
        let btnNext = $(this).find('.btn-next');
        let stepNext = $(this).find('.btn-next').attr('next_step');
        let spinner = $(this).find('.spinner-border');
        
        btnNext.hide();
        spinner.show();
        
        $.ajax({
            url: url,
            method: 'post',
            data: data
        })
        .done(function() {
            showStep(stepNext);
        })
        .fail(function(jqXHR) {
            $('.toast p').html(jqXHR.responseText);
            $('.toast').toast('show');
        
            btnNext.show();
            spinner.hide();
        });        
        
        event.preventDefault();
        return false;
    });
JS
);

$this->registerCss(<<<CSS
    .step {
        width: 100%;        
    }
    .card {
        background-image: url('/img/24.png');
    }
    .card-header h1 {
        font-weight: bolder;
    }
CSS
);
?>
        