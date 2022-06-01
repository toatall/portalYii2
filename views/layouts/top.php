<?php
/** @var \yii\web\View $this */

use app\assets\FlipAsset;
use app\assets\newyear\GerljandaAsset;
use app\assets\fancybox\FancyboxAsset;
use app\helpers\DateHelper;
use yii\bootstrap4\Html;

FancyboxAsset::register($this);

$flagNewYear = false;//(date('m') == 12);

if ($flagNewYear) {
    //FlipAsset::register($this);
    GerljandaAsset::register($this);
}
FlipAsset::register($this);

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

<?php 
if (date('Y') < 2022) {
    echo $this->render('top_new_year'); 
}
?>

<div class="container-fluid">
    <div class="row justify-content-between" id="logo-background" style="overflow: hidden;">
        
        <div class="col-7 col-md-6 col-sm-5 text-left" id="logo-image" style="background-image: url('<?= $logoTopPath . $logoTopImg ?>');"></div>
        
        <div class="col text-right">
            <?php if (date('Y') < 2022) { echo $this->render('top_pay_taxes'); } ?>
            <?= $this->render('top_calendar') ?>
            
            <!-- <div class="float-right d-none d-xl-block">                
                <a href="/contest/map">
                    <img src="/public/content/map/images/svgg.png" class="img-thumbnail" style="height: 185px; margin: 10px 10px 0 0;" />
                </a>
            </div> -->

            <div class="float-right d-none d-xl-block p-1 mt-2 mr-2 text-center" style="height: 12rem;" 
                data-toggle="tooltip" data-content="<span class='lead'>Спартакиада 2022</span>" data-trigger="hover" data-html="true" data-placement="left">
                <?php if (date('Ymd') < 20220530): ?>
                <div class="d-inline-block">
                    <?php 
                        $days = DateHelper::dateDiffDays('30.05.2022');
                        $endNumber = $days % 10;
                    ?>
                    <span style="color: white; font-family: 'Lucida Grande', 'Lucida Sans Unicode', Arial, sans-serif; font-weight: bolder; font-size: medium;">
                        До спартакиады<br /> <?= ($endNumber == 1) ? 'остался' : 'осталось' ?>
                    </span>                
                    <div class="tick mt-2" data-value="<?= $days ?>">
                        <div data-layout="vertical">
                            <span data-view="flip"></span>
                        </div>
                    </div>                
                    <span style="color: white; font-family: 'Lucida Grande', 'Lucida Sans Unicode', Arial, sans-serif; font-weight: bolder; font-size: medium;">
                        <?= (($endNumber == 1) ? 'день' : (($endNumber > 1 && $endNumber < 5) ? 'дня' : 'дней')) ?>
                    </span>
                </div>
                <?php endif; ?>
                <?= Html::a(Html::img('/public/content/portal/images/sport_2022.png', ['style'=>'height: 80%; margin-top: 1rem;']), ['news/index', 'tag'=>'sport2022']) ?>
                
            </div>

            <?php if (!Yii::$app->user->isGuest && Yii::$app->user->identity->isOrg('8600')): ?>
            <div class="d-none d-xl-block">
                <a href="/contest/photo-kids">
                    <img src="/public/assets/contest/photo-kids/img/image832.png" style="height: 7rem; margin: 3rem 10px 0 0;" />
                </a>
            </div>
            <?php endif; ?>
            
        </div>        

        <a href="/contest/understand-me">
            <img src="/public/assets/contest/understand_me/img/croco.png" style="height: 185px; position: absolute; left: 10px; top: 10px;" data-toggle="tooltip" data-content="Детский конкурс 'Пойми меня'" data-trigger="hover" data-placement="left" />
        </a>
        
    </div>    
</div>

<?php
$this->registerCss(<<<CSS

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

