<?php
/** @var yii\web\View $this */

use app\assets\FlipNumbersAsset;
use app\helpers\DateHelper;
use yii\bootstrap5\Html;
use yii\db\Query;

FlipNumbersAsset::register($this);

$queryGeneral = (new Query())
    ->from('{{%pay_taxes_general}}')
    ->limit(1)
    ->where(['code_org' => '8600'])
    ->andWhere('YEAR([[date]]) = YEAR(GETDATE())')
    ->orderBy(['date' => SORT_DESC])
    ->one();
?>

<div class="rounded border-light border float-right d-none d-xxl-block me-2" style="width: 26rem; height: 12rem; margin-top: 0.5rem; background-color: #3B5998;">    
    <div class="row">
        <div class="col text-right">            
            <span class="lead text-light" style="font-size: 0.7rem;">Кампания по уплате имущественных налогов <?= date('Y') ?></span>
            <hr class="my-1" style="margin: 0.10rem 0 !important;" />
        </div>        
    </div>
    <div class="row">
        <div class="col">
            <div class="text-center text-light">
                <?php if (date('Ymd') > 20221201): ?>
                    <?php $days = DateHelper::dateDiffDays('02.01.2024'); ?>
                    <span class="lead" style="font-weight: bolder; font-size: 0.8em;">До срока исполнения СМС показателя 
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
                <?php else: ?>
                    <?php $days = DateHelper::dateDiffDays('03.12.2022'); ?>
                    <span class="lead" style="font-weight: bolder; font-size: 0.9em;">До срока уплаты налогов 
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
                <?php endif; ?>
                <div class="tick" data-credits="false" style="font-size: 2rem;" data-value="<?= $days ?>">
                    <div data-layout="vertical">
                        <span data-view="flip"></span>
                    </div>
                </div>
                <span style="font-family: 'Lucida Grande', 'Lucida Sans Unicode', Arial, sans-serif; font-weight: bolder; font-size: 0.9rem;">
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
                <div class="mt-1"><?= Html::a('Подробнее', ['/paytaxes/default/map'], ['class' => 'btn btn-outline-light btn-sm']) ?></div>
            </div>
        </div>
        <div class="col text-primary">
            <div class="text-center text-light">
                <span style="font-weight: bolder; font-size: 0.9em;">Поступления</span>
                <div class="tick" data-credits="false" style="font-size: 1.1em;" data-value="<?= round($queryGeneral['sum2'] ?? 0,2) ?> тыс">        
                    <div data-layout="vertical">            
                        <span data-view="flip"></span> 
                    </div>
                </div>  
                <hr class="my-1" />      
                <span style="font-weight: bolder; font-size: 0.9em;">СМС-показатель</span>
                <div class="tick" data-credits="false" style="font-size: 1.2em;" data-value="<?= round($queryGeneral['sms'] ?? 0,2) ?>">
                    <div data-layout="vertical">
                        <span data-view="flip"></span>
                    </div>
                </div>            
            </div>
            <hr class="my-1" />            
            <div class="text-right">
                <?php if (isset($queryGeneral['date'])): ?>
                    <small class="text-white">По состоянию на <?= Yii::$app->formatter->asDate($queryGeneral['date']) ?></small>
                <?php endif; ?>
            </div>
        </div>                
    </div>        
    
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