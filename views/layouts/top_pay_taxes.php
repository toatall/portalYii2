<?php
/** @var yii\web\View $this */

use app\assets\FlipAsset;
use app\helpers\DateHelper;
use yii\bootstrap4\Html;
use yii\db\Query;

FlipAsset::register($this);

$queryGeneral = (new Query())
    ->from('{{%pay_taxes_general}}')
    ->limit(1)
    ->where(['code_org' => '8600'])
    ->orderBy(['date' => SORT_DESC])
    ->one();
?>

<div class="rounded border-light border float-right container" style="position: absolute; top: 5px; right: 5px; width: 28em; height: 192px; background-color: #3B5998;">    
    <div class="row">
        <div class="col text-right">            
            <span class="lead text-light" style="font-size: small;">Кампания по уплате имущественных налогов 2021</span>
            <hr class="my-1" />
        </div>        
    </div>
    <div class="row">
        <div class="col">
            <div class="text-center text-light">
                <?php $days = DateHelper::dateDiffDays('03.12.2021'); ?>
                <span class="lead" style="font-weight: bolder; font-size: medium;">До срока уплаты налогов 
                <?php
                    $endNumber = $days % 10;
                    if ($endNumber == 1) {
                        echo 'остался';
                    }                    
                    else {
                        echo 'осталось';
                    }
                ?>     
                </span>
                <div class="tick" style="font-size: 2.0em;" data-value="<?= $days ?>">
                    <div data-layout="vertical">
                        <span data-view="flip"></span>
                    </div>
                </div>
                <span style="font-family: 'Lucida Grande', 'Lucida Sans Unicode', Arial, sans-serif; font-weight: bolder; font-size: medium;">
                    <?php
                        $endNumber = $days % 10;
                        if ($endNumber == 1) {
                            echo 'день';
                        }
                        elseif ($endNumber > 1 && $endNumber < 5) {
                            echo 'дня';
                        }
                        else {
                            echo 'дней';
                        }
                    ?>        
                </span>
                <div class="mt-1"><?= Html::a('Подробнее', ['/pay-taxes/map'], ['class' => 'btn btn-outline-light btn-sm']) ?></div>
            </div>
        </div>
        <div class="col text-primary">
            <div class="text-center text-light">
                <span style="font-weight: bolder; font-size: medium;">Поступления</span>
                <div class="tick" style="font-size: 1.1em;" data-value="<?= round($queryGeneral['sum2'],2) ?> тыс">        
                    <div data-layout="vertical">            
                        <span data-view="flip"></span> 
                    </div>
                </div>  
                <hr class="my-1" />      
                <span style="font-weight: bolder; font-size: medium;">СМС-показатель</span>
                <div class="tick" style="font-size: 1.2em;" data-value="<?= round($queryGeneral['sms'],2) ?>">
                    <div data-layout="vertical">
                        <span data-view="flip"></span>
                    </div>
                </div>            
            </div>
            <hr class="my-1" />            
            <div class="text-right">
                <small class="lead text-light small">По состоянию на <?= Yii::$app->formatter->asDate($queryGeneral['date']) ?></small>
            </div>
        </div>        
        
    </div>
    
    <!-- <div style="top: 25px; right: 200px; position: absolute; width: 140px;" class="text-center">
        <?php $days = DateHelper::dateDiffDays('01.12.2021', '30.10.2021'); ?>
        <span style="color: white; font-family: 'Lucida Grande', 'Lucida Sans Unicode', Arial, sans-serif; font-weight: bolder; font-size: medium;">Осталось</span>
        <div class="tick" data-value="<?= $days ?>">
            <div data-layout="vertical">
                <span data-view="flip"></span>
            </div>
        </div>
        <span style="color: white; font-family: 'Lucida Grande', 'Lucida Sans Unicode', Arial, sans-serif; font-weight: bolder; font-size: medium;">
            <?php
                $endNumber = $days % 10;
                if ($endNumber == 1) {
                    echo 'день';
                }
                elseif ($endNumber > 1 && $endNumber < 5) {
                    echo 'дня';
                }
                else {
                    echo 'дней';
                }
            ?>        
        </span>
        <div class="mt-1"><?= Html::a('Подробнее', ['/pay-taxes/map'], ['class' => 'btn btn-outline-primary btn-sm']) ?></div>
    </div>

    <div style="top: 25px; right: 10px; position: absolute; width: 200px;" class="text-center">
        <span style="color: white; font-family: 'Lucida Grande', 'Lucida Sans Unicode', Arial, sans-serif; font-weight: bolder; font-size: medium;">Послупления</span>
        <div class="tick" style="font-size: 1.2em;" data-value="1,34 млн">        
            <div data-layout="vertical">            
                <span data-view="flip"></span> 
            </div>
        </div>  
        <hr />      
        <span style="color: white; font-family: 'Lucida Grande', 'Lucida Sans Unicode', Arial, sans-serif; font-weight: bolder; font-size: medium;">СМС-показатель</span>
        <div class="tick" style="font-size: 1.2em;" data-value="99">
            <div data-layout="vertical">
                <span data-view="flip"></span>
            </div>
        </div>            
    </div> -->
    
</div>

<?php 
$this->registerCss(<<<CSS
    .tick {
        padding-bottom: 1px;
        font-size:40px;
        font-family: Verdana, Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif;
    }
    .tick-label {
        font-size:.5em;
        text-align:center;
    }
    .tick-group {
        margin:0 .25em;
        text-align:center;
    }
    .tick-credits {
        color: white;
        opacity: 0;
    }
    .tick-flip-panel {
        background-color: transparent;
    }

CSS); 
?>