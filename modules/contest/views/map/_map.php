<?php

/** @var yii\web\View $this */
/** @var array $regions */
/** @var array $cities */
/** @var array $missionToday */
/** @var array $missionAll */
/** @var array $isAnswered */

use kartik\select2\Select2;
use yii\bootstrap4\Html;
use yii\helpers\Url;
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
            /* stroke: #d45500 !important;  */
            stroke: white !important;
            stroke-width: 0.2rem !important;
            stroke-opacity: 0.3;         
        }
CSS) ?>    

    
    
        <?php foreach($missionAll as $mission): ?>            
            <?php if (strtotime($mission['date_show']) >= strtotime(date('d.m.Y'))): ?>
                <path class="point" data-id="<?= $mission['id'] ?>" d="<?= $mission['point_path'] ?> c 0,2.22716 -1.80522,4.03173 -4.03173,4.03173 -2.22651,0 -4.03174,-1.80457 -4.03174,-4.03173 0,-2.22586 1.80523,-4.03174 4.03174,-4.03174 2.22651,0 4.03173,1.80588 4.03173,4.03174 z m -3.92354,-2.69867 c -0.88596,0 -1.45102,0.37322 -1.89474,1.03652 -0.0575,0.0859 -0.0383,0.20183 0.0441,0.2643 l 0.5641,0.42773 c 0.0846,0.0642 0.20518,0.0489 0.27092,-0.0345 0.29042,-0.36835 0.48955,-0.58195 0.93158,-0.58195 0.33211,0 0.74291,0.21374 0.74291,0.5358 0,0.24346 -0.20098,0.36849 -0.52891,0.55234 -0.38242,0.21438 -0.88847,0.48121 -0.88847,1.14871 v 0.065 c 0,0.10773 0.0873,0.19508 0.19508,0.19508 h 0.91039 c 0.10774,0 0.19509,-0.0874 0.19509,-0.19508 v -0.0217 c 0,-0.46271 1.35235,-0.48197 1.35235,-1.73408 0,-0.94294 -0.9781,-1.65822 -1.89444,-1.65822 z m -0.10819,4.03174 c -0.41236,0 -0.74783,0.33546 -0.74783,0.74782 0,0.41235 0.33547,0.74783 0.74783,0.74783 0.41236,0 0.74782,-0.33548 0.74782,-0.74783 0,-0.41236 -0.33546,-0.74782 -0.74782,-0.74782 z"
                    style="fill:rgb(83, 132, 172);fill-opacity:0.9; stroke: white; stroke-width: 0.03rem;"                
                />
            <?php else: ?>
                <a href="<?= Url::to(['view', 'id'=>$mission['id']]) ?>" class="mv-link">
                <path class="place" data-id="<?= $mission['id'] ?>" d="<?= $mission['place_path'] ?> c -2.22427,-3.22452 -2.63713,-3.55546 -2.63713,-4.74052 0,-1.62328 1.31592,-2.93919 2.93919,-2.93919 1.62328,0 2.9392,1.31591 2.9392,2.93919 0,1.18506 -0.41287,1.516 -2.63714,4.74052 -0.14596,0.21086 -0.45817,0.21084 -0.60412,0 z m 0.30206,-3.51585 c 0.67637,0 1.22466,-0.5483 1.22466,-1.22467 0,-0.67637 -0.54829,-1.22466 -1.22466,-1.22466 -0.67636,0 -1.22466,0.54829 -1.22466,1.22466 0,0.67637 0.5483,1.22467 1.22466,1.22467 z"
                    style="fill:#d45500;fill-opacity:0.9; stroke: white; stroke-width: 0.05rem;"
                    data-toggle="tooltip" title="<?= $mission['place_name'] ?>" data-target="hover"
                />      
                </a>
            <?php endif; ?>
        <?php endforeach; ?>
    
    </svg>


    <svg width="100%"
    style="display:none;"
        viewBox="0 0 210 135"            
        xmlns:inkscape="http://www.inkscape.org/namespaces/inkscape"
        xmlns:sodipodi="http://sodipodi.sourceforge.net/DTD/sodipodi-0.dtd"
        xmlns="http://www.w3.org/2000/svg"
        xmlns:svg="http://www.w3.org/2000/svg">                             
        <g
            id="g11877"
            transform="matrix(0.16868378,0,0,0.16868378,1.8923302,1.4839669)">
        <?php foreach ($regions['regions'] as $region): ?>
            <path class="<?= $region['class'] ?>" d="<?= $region['d'] ?>" title="<?= $region['title'] ?>" data-target="hover" data-toggle="tooltips" />
        <?php endforeach; ?>
        </g>

        
        
        
    </svg>

    
    <div class="container">

        <?php if ($missionToday != null): ?>
        <div class="card mt-4">
            <div class="card-header font-weight-bold card-header-bg">Задание</div>
            <div class="card-body card-body-bg">
                <?= $missionToday['text_question'] ?>
                <hr />
                <?php Pjax::begin(['timeout'=>false, 'enablePushState'=>false]) ?>              
                <?php if ($isAnswered != null): ?>
                <div class="alert alert-info" title="<?=Yii::$app->formatter->asDatetime($isAnswered['date_create']) ?>">Ваш ответ сохранен.</div>
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

        <div class="card mt-4">
            <div class="card-header font-weight-bold card-header-bg">Результаты</div>
            <div class="card-body card-body-bg">
                <div class="row">
                    <?php foreach($missionAll as $mission): ?>
                        <?php if (strtotime($mission['date_show']) < strtotime(date('d.m.Y'))): ?>
                            <div class="col-3 mb-2">
                                <div class="card border border-danger">
                                    <div class="card-body card-body-bg">
                                        <strong><?= $mission['place_name'] ?></strong>
                                        <hr />
                                        <i class="far fa-calendar"></i>
                                        <?= Yii::$app->formatter->asDate($mission['date_show']) ?>
                                        <hr />
                                        <?= Html::a('Подробнее', ['view', 'id'=>$mission['id']], ['class'=>'btn btn-sm btn-outline-danger mv-link']) ?>
                                    </div>
                                </div>
                                
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
                
            </div>
        </div>

        <!--div class="card mt-5">
            <div class="card-header font-weight-bold card-header-bg"><i class="fas fa-trophy text-warning"></i> Победители</div>
            <div class="card-body card-body-bg">
                <div class="alert alert-secondary">Нет данных</div>
            </div>
        </div-->
    </div>
   
</div>

<?php $this->registerJs(<<<JS
    $('[data-toggle="tooltip"]').off();
    $('[data-toggle="tooltip"]').tooltip();    
JS); ?>