<?php
/** @var \yii\web\View $this */

use app\assets\FlipAsset;
use app\assets\newyear\GerljandaAsset;
use app\assets\fancybox\FancyboxAsset;

FancyboxAsset::register($this);

$flagNewYear = false;//(date('m') == 12);

if ($flagNewYear) {
    FlipAsset::register($this);
    GerljandaAsset::register($this);
}

$logoTopPath = '/public/assets/portal/img/';
$logoTopImg = 'top_default.png';
$month = date('m');
switch ($month) {
    case 12:
    case 1:
    case 2:
        $logoTopImg = 'top_winter.png';
        break;
    case 3:
    case 4:
    case 5:
        $logoTopImg = 'top_spring.png';
        break;
    case 6:
    case 7:
    case 8:
        $logoTopImg = 'top_summer.png';
        break;
    case 9:
    case 10:
    case 11:
        $logoTopImg = 'top_fall.png';
        break;
    default:
        $logoTopImg = 'top_default.png';
}


?>
<?php /*
<div id="logo-background">
    <div id="logo-image"></div>    
    <!--div style="top: 25px; right: 20px; position: absolute;">
        <a href="/img/top.jpg" class="fancybox">
            <img src="/img/top.jpg" height="150px;" class="thumbnail" />
        </a>
    </div-->
    <?php //$this->registerJs("$('.fancybox').fancybox();") ?>
    
    <?php 
    // 9 МАЯ
    if (DateHelper::isDateTodayBetween(date('06.05.Y'), date('15.05.Y'))): ?>
        <div style="top: 5px; right: 15px; position: absolute;">
            <a href="<?= Url::to(['/vov']) ?>">
                <img src="/img/9may/z_beb7063b_may9252197!252!197.png" height="190px;" />
            </a>
        </div>
    <?php endif; ?>
    
    <?php if ($flagNewYear): ?>
    <div style="top: 25px; right: 350px; position: absolute; width: 140px;" class="text-center">
        <span style="color: white; font-family: 'Lucida Grande', 'Lucida Sans Unicode', Arial, sans-serif; font-weight: bolder; font-size: medium;">До Нового года осталось</span>
        <div class="tick" data-value="<?= DateHelper::dateDiffDays('01.01.' . intval(date('Y') + 1)); ?>">
            <div data-layout="vertical">
                <span data-view="flip"></span>
            </div>
        </div>
    </div>
    <div style="top: 5px; right: 170px; position: absolute;">
        <a href="<?= Url::to(['/christmas-calendar']) ?>" data-toggle="popover" data-content='Проект "SUPER STAЖ"' data-placement="left">
            <img src="/images/stag.png" style="height: 195px;" />
        </a>
    </div>
    <div style="bottom: 5px; right: 5px; position: fixed; z-index: 9999;">
        <img src="/img/elka.gif" style="height: 185px;" />
    </div>
    <div id="gir" class="gir_3">
        <div id="nums_1">1</div>
    </div>
    <?php endif; ?>
    
    <?php if (date('d.m.Y') == '05.03.2021'): ?>
    <div style="top: 5px; right: 15px; position: absolute;">
        <img src="/images/8march/1520242549_0_7a017_afe03477_orig.png" style="height: 195px; filter: drop-shadow(5px 5px 1px white);" />        
    </div>
    <?php endif; ?>

  

    <?= $this->render('top_pay_taxes') ?>

    
</div>
*/ ?>

<div class="container-fluid">
    <div class="row justify-content-between" id="logo-background">
        
        <div class="col-7 text-left" id="logo-image" style="background-image: url('<?= $logoTopPath . $logoTopImg ?>');"></div>
        
        <div class="col text-right">
            <?= $this->render('top_pay_taxes') ?>
            <?= $this->render('top_calendar') ?>
        </div>

    </div>
    
</div>

<?php
$this->registerCss(<<<CSS
/*
    .tick {
        padding-bottom: 1px;
        font-size:70px;
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
 *
 */
        
    .bounce-2 {
        animation-name: bounce-2;
        animation-timing-function: cubic-bezier(1, 1, 1, 1);
        animation-iteration-count: 9999;
        animation-duration: 3s;        
        animation-fill-mode: forwards;        
    }
        
    @keyframes bounce-2 {
        0% {
            filter: drop-shadow(5px 5px 1px white);
        }
        50% {
            filter: drop-shadow(5px 5px 1px #337ab7);
        }
    }
        
CSS
);
?>

