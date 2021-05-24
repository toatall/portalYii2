<?php

use yii\helpers\Html;
use app\modules\events\assets\FancyboxAsset;
use app\modules\events\assets\ModalViewerAsset;
use app\modules\events\models\ContestArts;

ModalViewerAsset::register($this);
FancyboxAsset::register($this);

/* @var $this yii\web\View */
/* @var $modelsToday \app\modules\events\models\ContestArts[] */
/* @var $modelLastArts \app\modules\events\models\ContestArts[] */
/* @var $winners app\modules\events\models\ContestArtsResults[] */
/* @var $modelVotes \app\modules\events\models\ContestArtsVote[] */

$this->title = 'Конкурс "Навстречу искусству"';
$this->params['breadcrumbs'][] = $this->title;
?>


<?php $this->registerCss(<<<CSS
    .border-art {
        border-width: 30px;
        border-style: ridge;
        border-color: #a0522d;
    }
    .border-art-small {
        border-width: 10px;
        border-style: ridge;
        border-color: #a0522d;
    }    
CSS
); ?>

<div class="masthead bg-primary text-center" style="padding-top:20px; background-image: url('/img/19.jpg');">
    
<section id="main" class="page-section" style="padding-top: calc(6rem);">         
    <div class="container-fluid">   
        <!-- Contact Section Heading-->
        <h2 class="page-section-heading text-center text-uppercase text-secondary mb-0"></h2>
        <!-- Icon Divider-->
        <div class="divider-custom">
            <div class="divider-custom-line"></div>
            <div class="divider-custom-icon"><i class="fas fa-info-circle"></i></div>
            <div class="divider-custom-line"></div>
        </div>
        <!-- Contact Section Form-->
        <div class="" style="margin: 0 auto;">
            <div id="step1" class="step">
                <div class="card bg-primary2" style="sbackground-image: url('/img/24.png');">
                    <div class="card-header">
                        <h1 class="text-secondary"  style="font-weight: bolder;">Итоги конкурса!</h1>
                    </div>
                    <div class="card-body text-secondary">
                        <br />
                        <table class="table table-bordered col-sm-6" style="margin: 0 auto;">
                            <tr>
                                <th>
                                    Искусствовед (наибольшее число угаданных картин) – 8 победителей (угаданы все 23 произведения)
                                </th>
                            </tr>
                            <tr>
                                <td>Холкина Марина Викторовна</td>
                            </tr>
                            <tr>                                
                                <td>Волкова Яна Григорьевна</td>
                            </tr>
                            <tr>
                                <td>Зенютич Марина Владимировна</td>
                            </tr>
                            <tr>                                
                                <td>Макаров Владимир Александрович</td>
                            </tr>
                            <tr>
                                <td>Шураева Евгения Александровна</td>
                            </tr>
                            <tr>
                                <td>Зарубин Сергей Александрович</td>
                            </tr>
                            <tr>
                                <td>Логинов Алексей Валерьевич</td>
                            </tr>
                            <tr>
                                <td>Нефёдова Татьяна Петровна</td>
                            </tr>
                        </table>                        
                        
                        <br /><br />
                        <table class="table table-bordered col-sm-6" style="margin: 0 auto;">
                            <tr>
                                <th colspan="2">Достоверность воспроизведения</th>
                            </tr>
                            <tr>
                                <td>
                                    <i class="fas fa-medal" style="color: rgb(249, 173, 14);">&nbsp;</i> 1 место
                                </td>
                                <td>Общий отдел («Наконец-то Гагаро в галстуке», 4.52 баллов)</td>
                            </tr>
                            <tr>
                                <td>
                                    <i class="fas fa-medal" style="color: rgb(209, 215, 218);">&nbsp;</i> 2 место
                                </td>
                                <td>Отдел обеспечения («Лед: Перезагрузка», 4.39 баллов)</td>
                            </tr>
                            <tr>
                                <td>
                                    <i class="fas fa-medal" style="color: rgb(223, 126, 8);">&nbsp;</i> 3 место
                                </td>
                                <td>Отдел контроля налоговых органов («Замша», 4.33 балла)</td>
                            </tr>
                        </table>
                        
                        <br /><br />
                        <table class="table table-bordered col-sm-6" style="margin: 0 auto;">
                            <tr>
                                <th colspan="2">Оригинальность названия</th>
                            </tr>
                            <tr>
                                <td>
                                    <i class="fas fa-medal" style="color: rgb(249, 173, 14);">&nbsp;</i> 1 место
                                </td>
                                <td>Общий отдел («Наконец-то Гагаро в галстуке», 4.37 баллов)</td>
                            </tr>
                            <tr>
                                <td>
                                    <i class="fas fa-medal" style="color: rgb(209, 215, 218);">&nbsp;</i> 2 место
                                </td>
                                <td>Отдел налогообложения имущества и доходов ФЛ и администрирования страховых взносов («Исповедь перед премией», 3.83 балла)</td>
                            </tr>
                            <tr>
                                <td>
                                    <i class="fas fa-medal" style="color: rgb(223, 126, 8);">&nbsp;</i> 3 место
                                </td>
                                <td>Отдел камерального контроля («И снова нарушил «Контрольку», 3.78 баллов)</td>
                            </tr>
                        </table>
                        
                        <br /><br />
                        <table class="table table-bordered col-sm-6" style="margin: 0 auto;">
                            <tr>
                                <th colspan="2">Наибольшее число участников (учитывалось количество участников по отношению к общему числу сотрудников отдела)</th>
                            </tr>
                            <tr>
                                <td>
                                    <i class="fas fa-medal" style="color: rgb(249, 173, 14);">&nbsp;</i> 1 место
                                </td>
                                <td>Отдел обеспечения («Лед: Перезагрузка», 10 из 9) и Контрольный отдел №1 (На Вольве, 9 из 9)</td>
                            </tr>                            
                        </table>
                    </div>
                </div>
            </div>            
        </div>            
    </div>    
</section>
    
    
<?php 
// голосуем за картины по разным номинациям
if (empty($modelsToday) && is_array($modelVotes) && count($modelVotes) > 0 && ((new \DateTime('now'))->getTimestamp() <= (new \DateTime('20.05.2021'))->getTimestamp())):
    ?>
    <section id="main" class="page-section" style="padding-top: calc(6rem);">         
        <div class="container-fluid">   
            <!-- Contact Section Heading-->
            <h2 class="page-section-heading text-center text-uppercase text-secondary mb-0">Голосование</h2>
            <!-- Icon Divider-->
            <div class="divider-custom">
                <div class="divider-custom-line"></div>
                <div class="divider-custom-icon"><i class="fas fa-medal"></i></div>
                <div class="divider-custom-line"></div>
            </div>
            <!-- Contact Section Form-->
            <div class="row" style="margin: 0 auto;">
                <?= $this->render('_vote', [
                    'modelVotes' => $modelVotes,
                ]) ?>
            </div>            
        </div>    
    </section>
    <?php
endif;


// угадываем картины
if (!empty($modelsToday)): ?>   
    <section id="main" style="padding-top: calc(6rem);"> 
        <div class="container-fluid d-flex align-baseline flex-column">          
            <div class="row justify-content-center">
            <?php foreach ($modelsToday as $modelToday): ?>
                <div class="col-6">
                    <?= Html::a(Html::img($modelToday->image_reproduced, ['class' => 'img-thumbnail border-art', 'style' => 'height: 40em; margin: 10px auto;']), 
                        $modelToday->image_reproduced, ['class' => 'fancybox', 'target' => '_blank']) ?>
                    <div class="card" style="width: 80%; margin: 0 auto; background-image: url('/img/24.png');">
                        <h4 style="text-shadow: rgba(100,100,100,0.7) 0px 3px 3px; color: #333; ">
                            <?= $modelToday->image_reproduced_title ?>,
                            <?= $modelToday->department_name ?>                           
                        </h4>
                    </div>
                                        
                    <?= $this->render('_index_item', [
                       'modelToday' => $modelToday,
                   ]) ?>                    
                    
                </div>                                
            <?php endforeach; ?>
            </div>               
        </div>    
    </section>
    
<?php $this->registerJs(<<<JS
    $('.form-send-answer').on('submit', function() {      
        let url = $(this).attr('action');
        let dataForm = $(this).serialize();
        let parentDiv = $(this).parent('div');
        let alertDiv = $(this).parent('div').find('.alert-div');
        
        $.ajax({
            url: url,
            method: 'post',
            data: dataForm
        })
        .done(function(data) {
            parentDiv.html(data);
        })
        .fail(function(jqXHR) {
            alertDiv.html('<div class="alert alert-danger">' + jqXHR.responseText + '</div>');
        });
        
        return false;
    });
JS
); ?> 
<?php endif; ?>  
    
<?php $this->registerJs(<<<JS
    $('.fancybox').fancybox();
JS
); ?>

    <!-- Gallery -->
    <section class="page-section" id="gallery">
        <div class="container-fluid">
            <!-- Contact Section Heading-->
            <h2 class="page-section-heading text-center text-uppercase text-secondary mb-0">Галерея</h2>
            <!-- Icon Divider-->
            <div class="divider-custom">
                <div class="divider-custom-line"></div>
                <div class="divider-custom-icon"><i class="fas fa-images"></i></div>
                <div class="divider-custom-line"></div>
            </div>
            <!-- Contact Section Form-->                     
            <?php foreach ($modelLastArts as $model): ?>

            <h2 class="text-center" style="font-weight: bolder;"><?= $model->date_show ?></h2>

            <div class="row">

                <div class="col">
                    <?= Html::a(Html::img($model->image_reproduced, ['class' => 'img-thumbnail border-art', 'style'=>'margin: 0 auto; max-height:30em; max-width:100%;',]), 
                        $model->image_reproduced, ['class' => 'fancybox']) ?>
                    <div class="card" style="width: 80%; margin: 10px auto; background-image: url('/img/24.png');">
                        <h4 style="text-shadow: rgba(100,100,100,0.7) 0px 3px 3px; color: #333; ">
                            <?= $model->image_reproduced_title ?>,
                            <?= $model->department_name ?>                           
                        </h4>
                    </div>
                </div>

                <div class="col">
                    <?= Html::a(Html::img($model->image_original, ['class' => 'img-thumbnail border-art', 'style'=>'margin: 0 auto; max-height:30em; max-width:100%;',]), 
                        $model->image_original, ['class' => 'fancybox']) ?>
                    <div class="card" style="width: 80%; margin: 10px auto; background-image: url('/img/24.png');">
                        <h4 style="text-shadow: rgba(100,100,100,0.7) 0px 3px 3px; color: #333; ">
                            <?= $model->image_original_title ?>,
                            <?= $model->image_original_author ?>                           
                        </h4>
                    </div>
                </div>                                           

            </div>
            <?= Html::a('Статистика', ['/events/contest-arts/statistic', 'id'=>$model->id], ['class' => 'btn btn-secondary mv-link']) ?>  
            <hr />
            <?php endforeach; ?>                
        </div>  
    </section>
    

    
    <!-- Winner -->
    <section class="page-section" id="winner">
        <div class="container-fluid">
            <!-- Contact Section Heading-->
            <h2 class="page-section-heading text-center text-uppercase text-secondary mb-0">Правильно ответили</h2>
            <!-- Icon Divider-->
            <div class="divider-custom">
                <div class="divider-custom-line"></div>
                <div class="divider-custom-icon"><i class="fas fa-star"></i></div>
                <div class="divider-custom-line"></div>
            </div>
          
            
            <?php
            $index = 1;
            if ($winners && is_array($winners) && count($winners)): ?>
                <!--div class="card">
                    <div class="card-header">
                        Правильно ответили
                    </div>
                    <div class="card-body"-->
                    <div class="col-6" style="margin: 0 auto;">
                        <table class="table table-bordered table-dark">
                            <tr class="bg-warning">     
                                <th>#</th>
                                <th>ФИО</th>
                                <th>Количество ответов</th>
                                <th>&nbsp;</th>
                            </tr>
                            <?php foreach ($winners as $winner): ?>
                            <tr>          
                                <td><?= $index ?></td>
                                <td><?= $winner['fio'] ?></td>
                                <td><?= $winner['count_wins'] ?></td>
                                <td><?= Html::a('Подробнее', ['/events/contest-arts/winner', 'login' => $winner['author']], ['class' => 'btn btn-primary mv-link']) ?>
                            </tr>
                            <?php
                            $index++;
                            endforeach; ?>
                        </table>
                    </div>
                <!--/div-->

            <?php endif; ?>
            
        <!--/div-->  
    </section>
    
</div>



<!-- Portfolio Modals-->
<div class="portfolio-modal modal fade" id="portfolioModal1" tabindex="-1" role="dialog" data-backdrop="static" aria-labelledby="portfolioModal1Label" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title" id="new-modal-title"></h1>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="fas fa-times"></i></span>
                </button>
            </div>            
            <div class="modal-body" id="new-modal-body" style="border-top: 1px solid rgba(0,0,0,.125);">BODY</div>           
            <div class="modal-footer" style="border-top: 1px solid rgba(0,0,0,.125);">                 
                <button class="btn btn-primary" data-dismiss="modal">
                    <i class="fas fa-times fa-fw"></i>
                    Закрыть
                </button>
            </div>
        </div>        
    </div>
</div>





<?php 
$this->registerJs(<<<JS
    modalViewer.modalId = '#portfolioModal1';
    modalViewer.modalBody = '#new-modal-body';
    modalViewer.modalTitle = '#new-modal-title';
JS
);
?>