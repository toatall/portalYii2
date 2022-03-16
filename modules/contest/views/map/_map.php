<?php

/** @var yii\web\View $this */
/** @var array $regions */
/** @var array $cities */
/** @var array $missionToday */
/** @var array $missionAll */
/** @var array $isAnswered */

use kartik\select2\Select2;
use yii\bootstrap4\Html;
use yii\widgets\Pjax;

$this->registerCss(<<<CSS
        .region {
            transition: all 0.7s;
            stroke: rgba(231, 206, 91, 0.7);
            stroke-width: 0.4;
            fill: rgba(231, 206, 91, 0.2);     
        }
        .region:hover {
            stroke: rgba(231, 206, 91, 1);
            stroke-width: 3 !important;
            opacity: 0.9 !important;
        }        
        .point {
            transition: all 0.7s;
        }
        .point:hover {                                                  
            cursor: pointer;
            font-size: 3px;
            stroke: rgba(53, 102, 142) !important; 
            stroke: white;
            stroke-width: 0.1rem;
            stroke-opacity: 0.3;
        }
        .place {
            transition: all 0.7s;
        }

        .place:hover {            
            cursor: pointer;            
            stroke: #d45500 !important; 
            stroke-width: 0.1rem !important;
            stroke-opacity: 0.3;         
        }
CSS) ?>


    <svg width="100%"
        viewBox="0 0 210 135"            
        xmlns:inkscape="http://www.inkscape.org/namespaces/inkscape"
        xmlns:sodipodi="http://sodipodi.sourceforge.net/DTD/sodipodi-0.dtd"
        xmlns="http://www.w3.org/2000/svg"
        xmlns:svg="http://www.w3.org/2000/svg">                             
        <g
            id="g11877"
            transform="matrix(0.16868378,0,0,0.16868378,1.8923302,1.4839669)">
        <?php foreach ($regions['regions'] as $region): ?>
            <path class="<?= $region['class'] ?>" d="<?= $region['d'] ?>" title="<?= $region['title'] ?>" />
        <?php endforeach; ?>        
        </g>

        <?php foreach($missionAll as $mission): ?>            
            <?php if (strtotime($mission['date_show']) >= strtotime(date('d.m.Y'))): ?>
            <path class="point" data-id="<?= $mission['id'] ?>" d="<?= $mission['point_path'] ?> c -0.613705,0 -1.111094,0.49738 -1.111094,1.1111 0,0.61369 0.497389,1.11109 1.111094,1.11109 0.613709,0 1.111096,-0.4974 1.111096,-1.11109 0,-0.61372 -0.497387,-1.1111 -1.111096,-1.1111 z m 0,1.73609 c -0.07812,0 -0.138887,-0.0608 -0.138887,-0.1389 0,-0.0782 0.05685,-0.13888 0.138887,-0.13888 0.07422,0 0.138887,0.0608 0.138887,0.13888 0,0.0782 -0.06467,0.1389 -0.138887,0.1389 z m 0.299911,-0.61631 -0.195744,0.12151 v 0.009 c 0,0.0564 -0.04774,0.10416 -0.104163,0.10416 -0.05642,0 -0.104164,-0.0478 -0.104164,-0.10416 v -0.0695 c 0,-0.0347 0.01735,-0.0694 0.05209,-0.0911 l 0.247394,-0.14757 c 0.03038,-0.0174 0.04774,-0.0478 0.04774,-0.0825 0,-0.0521 -0.04731,-0.0955 -0.09939,-0.0955 h -0.221784 c -0.05599,0 -0.09548,0.0433 -0.09548,0.0955 0,0.0564 -0.04774,0.10417 -0.104163,0.10417 -0.05642,0 -0.104164,-0.0478 -0.104164,-0.10417 0,-0.16926 0.134547,-0.30382 0.299909,-0.30382 h 0.221783 c 0.177082,0 0.311629,0.13456 0.311629,0.30382 0,0.10417 -0.05642,0.204 -0.151473,0.26041 z"
                style="fill:rgb(83, 132, 172);fill-opacity:0.9"
            />
            <?php else: ?>
            <path class="place" data-id="<?= $mission['id'] ?>" d="<?= $mission['place_path'] ?> c 0,0.43568 -0.353481,0.7892 -0.78917,0.7892 -0.435854,0 -0.789173,-0.35352 -0.789173,-0.7892 0,-0.43586 0.353319,-0.78916 0.789173,-0.78916 0.435689,0 0.78917,0.3533 0.78917,0.78916 z m -0.701483,-0.43844 c 0,-0.0485 -0.03948,-0.0876 -0.08769,-0.0876 -0.295392,0 -0.526114,0.23559 -0.526114,0.52614 0,0.048 0.03923,0.0874 0.08769,0.0874 0.04822,0 0.08769,-0.0394 0.08769,-0.0874 0,-0.19359 0.157268,-0.35077 0.350739,-0.35077 0.04822,0 0.08769,-0.0391 0.08769,-0.0876 z m -0.263061,2.27986 v -0.89275 c 0.057,0.0117 0.115641,0.0117 0.175346,0.0117 0.05974,0 0.118385,-6.2e-4 0.175371,-0.0117 v 0.89275 c 0,0.0968 -0.07839,0.17532 -0.175371,0.17532 -0.09702,0 -0.175346,-0.0785 -0.175346,-0.17532 z"
                style="fill:#d45500;fill-opacity:0.9; stroke: white; stroke-width: 0.1px;" />      
            <?php endif; ?>
        <?php endforeach; ?>
        
    </svg>

    
    <div class="container">

        <?php if ($missionToday != null): ?>
        <div class="card">
            <div class="card-header">Задание</div>
            <div class="card-body">
                <?= $missionToday['text_question'] ?>
                <hr />
                <?php Pjax::begin(['timeout'=>false, 'enablePushState'=>false]) ?>              
                <?php if ($isAnswered != null): ?>
                <div class="alert alert-info">Ваш ответ сохранен. <?=''// Yii::$app->formatter->asDatetime($isAnswered['date_create']) ?></div>
                <?php else: ?>
                <?= Html::beginForm('', '', [
                    'data-pjax' => true,
                ]) ?>
                <div class="row">
                    <div class="col-10">                        
                        <?= Select2::widget([
                            'data' => $cities,
                            'name' => 'city',
                            'options' => [
                                'placeholder' => 'Ваш вариант',
                            ],
                        ]) ?>
                    </div>
                    <div class="col">
                        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary col']) ?>
                    </div>
                </div>                
                <?= Html::endForm() ?>                                
                <?php endif; ?>
                <?php Pjax::end() ?>
            </div>
        </div>
        <?php endif; ?>

        <div class="card mt-5">
            <div class="card-header">Результаты</div>
            <div class="card-body">
                <div class="row">
                    <div class="col-3">
                        <a href="">
                            <i class="far fa-calendar-alt text-primary"></i> 25.05.2022
                        </a>
                    </div>
                    <div class="col-3">
                        <a href="">
                            <i class="far fa-calendar-alt text-primary"></i> 25.05.2022
                        </a>
                    </div>
                    <div class="col-3">
                        <a href="" class="btn btn-outline-secondary">
                            Ответ за 25.05.2022
                        </a>
                    </div>
                </div>
                <?php foreach($missionAll as $mission): ?>
                    <?php if (strtotime($mission['date_show']) < strtotime(date('d.m.Y'))): ?>
                        <?= $mission['date_show'] ?><br />
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </div>

    </div>
   

    
    

</div>