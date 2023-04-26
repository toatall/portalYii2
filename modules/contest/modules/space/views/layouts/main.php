<?php

/** @var yii\web\View $this */
/** @var string $content */

use app\assets\FontAwesomeAsset;
use yii\bootstrap5\Html;
use app\assets\ModalViewerAssetBs5;

ModalViewerAssetBs5::register($this);
FontAwesomeAsset::register($this);

$this->title = 'Выставка "Космос - Мир удивительных фантазий"';
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">

<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>

<body class="d-flex flex-column h-100">
    <?php $this->beginBody() ?>

    <div class="grid text-center mt-3">        
        <p class="text-bg-warning p-2" style="font-size: 2.5rem;">
          <i class="fas fa-rocket"></i> Выставка "Космос - Мир удивительных фантазий"
          <i class="fas fa-meteor"></i>
        </p>
    </div>
    <!-- <div class=" border-bottom border-white mt-4 mx-5"></div> -->

    <div class='solar-syst'>
        <div class='sun'></div>
        <div class='mercury'></div>
        <div class='venus'></div>
        <div class='earth'></div>
        <div class='mars'></div>
        <div class='jupiter'></div>
        <div class='saturn'></div>
        <div class='uranus'></div>
        <div class='neptune'></div>
        <div class='pluto'></div>
        <div class='asteroids-belt'></div>
    </div>

    <div class="container-fluid mt-5 px-5">
        <?php if (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false || strpos($_SERVER['HTTP_USER_AGENT'], 'Trident') !== false) : ?>
            <div class="alert alert-danger display-2 font-weight-bolder text-center" style="position: relative; z-index: 1000;">Браузер Internet Explorer не поддерживается!</div>
        <?php else : ?>
            <?= $content ?>           
        <?php endif; ?>
    </div>

    
    <?php
    $this->registerCss(<<<CSS

.stroke {
    -webkit-text-stroke-width: 2px;
    -moz-text-stroke-width: 2px;
    -webkit-text-stroke-color: #111827;
    -moz-text-stroke-color: #111827;
    color: transparent;
}

.shadow-text {
    text-shadow: 4px 4px #545454;
}

.halftone {
    position: relative;
}

.halftone-color:after {
  background-color: #fff;
}

body p {
    font-size: 5vw;
    font-weight: bold;
    letter-spacing: 5px;
    margin: 0;
}
body .grid {
    display: grid;
    grid-gap: 5vw;
    grid-template-columns: 1fr;
}



/*

https://codepen.io/kowlor/pen/ZYYQoy
  Malik Dellidj - @Dathink

  Solar System orbit animation true time scaled

  Revolution of planets in earth days (from Wikipedia)
  Mercury : ~87,5 days
  Venus : ~224,7 days
  Earth : ~365,2563 days
    + Moon : ~27,3216 days (around earth)
  Mars : ~687 days (~1,8 year)
  Jupiter : ~4 331 days (~12 years)
  Saturn : ~10 747 days (~30 years)
  Uranus : ~30 589 days (~84 years)
  Neptune : ~59 802 days (~165 years)
  Pluto : ~90 580 days (~248 years)
*/
*, *:before, *:after {
  padding: 0;
  margin: 0;
  box-sizing: border-box;
}

html, body {
  height: 100%;
  width: 100%;
}

body {
  font: normal 1em/1.45em "Helvetica Neue", Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  color: #fff;
  background: radial-gradient(ellipse at bottom, #1C2837 0%, #050608 100%);
  background-attachment: fixed;
}

h1 {
  font-weight: 300;
  font-size: 2.5em;
  text-transform: uppercase;
  font-family: Lato;
  line-height: 1.6em;
  letter-spacing: 0.1em;
}

a, a:visited {
  text-decoration: none;
  color: white;
  opacity: 0.7;
}
a:hover, a:visited:hover {
  opacity: 1;
}
a.icon, a:visited.icon {
  margin-right: 2px;
  padding: 3px;
}

hr {
  margin: 26px 0;
  border: 0;
  border-top: 1px solid white;
  background: transparent;
  width: 25%;
  opacity: 0.1;
}

code {
  color: #ae94c0;
  font-family: Menlo, Monaco, Consolas, "Courier New", monospace;
  font-size: 0.9em;
}

.solar-syst {
  margin: 0 auto;
  width: 100%;
  height: 100%;
  position: fixed;
}
.solar-syst:after {
  content: "";
  position: absolute;
  height: 2px;
  width: 2px;
  top: -2px;
  background: white;
  box-shadow: 1234px 386px 0 0px rgba(255, 255, 255, 0.118) , 1026px 1407px 0 0px rgba(255,255,255, 0.517) , 1755px 694px 0 0px rgba(255,255,255, 0.181) , 947px 661px 0 0px rgba(255,255,255, 0.611) , 177px 144px 0 0px rgba(255,255,255, 0.84) , 1418px 602px 0 0px rgba(255,255,255, 0.441) , 1413px 1066px 0 0px rgba(255,255,255, 0.429) , 1276px 665px 0 0px rgba(255,255,255, 0.615) , 696px 1612px 0 0px rgba(255,255,255, 0.29) , 669px 614px 0 0px rgba(255,255,255, 0.565) , 483px 599px 0 0px rgba(255,255,255, 0.343) , 1157px 734px 0 0px rgba(255,255,255, 0.94) , 157px 1060px 0 0px rgba(255,255,255, 0.725) , 1604px 1228px 0 0px rgba(255,255,255, 0.985) , 488px 1560px 0 0px rgba(255,255,255, 0.307) , 1413px 258px 0 0px rgba(255,255,255, 0.489) , 908px 1551px 0 0px rgba(255,255,255, 0.752) , 214px 1780px 0 0px rgba(255,255,255, 0.499) , 524px 1145px 0 0px rgba(255,255,255, 0.689) , 368px 1582px 0 0px rgba(255,255,255, 0.688) , 975px 910px 0 0px rgba(255,255,255, 0.84) , 276px 477px 0 0px rgba(255,255,255, 0.971) , 1291px 1371px 0 0px rgba(255,255,255, 0.437) , 409px 1654px 0 0px rgba(255,255,255, 0.714) , 1488px 1419px 0 0px rgba(255,255,255, 0.095) , 1689px 1280px 0 0px rgba(255,255,255, 0.136) , 1618px 619px 0 0px rgba(255,255,255, 0.376) , 621px 1010px 0 0px rgba(255,255,255, 0.255) , 1311px 1541px 0 0px rgba(255,255,255, 0.505) , 1552px 1515px 0 0px rgba(255,255,255, 0.805) , 989px 1310px 0 0px rgba(255,255,255, 0.26) , 993px 1643px 0 0px rgba(255,255,255, 0.473) , 566px 1306px 0 0px rgba(255,255,255, 0.636) , 1013px 1371px 0 0px rgba(255,255,255, 0.816) , 111px 1341px 0 0px rgba(255,255,255, 0.08) , 914px 85px 0 0px rgba(255,255,255, 0.663) , 226px 1343px 0 0px rgba(255,255,255, 0.551) , 872px 1552px 0 0px rgba(255,255,255, 0.245) , 371px 1366px 0 0px rgba(255,255,255, 0.416) , 1255px 981px 0 0px rgba(255,255,255, 0.795) , 502px 1444px 0 0px rgba(255,255,255, 0.32) , 1515px 789px 0 0px rgba(255,255,255, 0.001) , 1594px 460px 0 0px rgba(255,255,255, 0.731) , 771px 563px 0 0px rgba(255,255,255, 0.589) , 165px 1069px 0 0px rgba(255,255,255, 0.205) , 1543px 1502px 0 0px rgba(255,255,255, 0.54) , 1109px 134px 0 0px rgba(255,255,255, 0.208) , 1077px 1608px 0 0px rgba(255,255,255, 0.265) , 349px 1800px 0 0px rgba(255,255,255, 0.116) , 13px 394px 0 0px rgba(255,255,255, 0.468) , 1248px 1232px 0 0px rgba(255,255,255, 0.459) , 1727px 1500px 0 0px rgba(255,255,255, 0.463) , 112px 1626px 0 0px rgba(255,255,255, 0.729) , 1337px 810px 0 0px rgba(255,255,255, 0.891) , 201px 1411px 0 0px rgba(255,255,255, 0.133) , 1018px 1761px 0 0px rgba(255,255,255, 0.217) , 832px 1416px 0 0px rgba(255,255,255, 0.281) , 1364px 1525px 0 0px rgba(255,255,255, 0.888) , 1703px 1026px 0 0px rgba(255,255,255, 0.505) , 1652px 1229px 0 0px rgba(255,255,255, 0.678) , 15px 177px 0 0px rgba(255,255,255, 0.437) , 1777px 1723px 0 0px rgba(255,255,255, 0.917) , 1097px 1187px 0 0px rgba(255,255,255, 0.332) , 352px 447px 0 0px rgba(255,255,255, 0.326) , 99px 482px 0 0px rgba(255,255,255, 0.073) , 1457px 135px 0 0px rgba(255,255,255, 0.235) , 887px 1261px 0 0px rgba(255,255,255, 0.223) , 1547px 1786px 0 0px rgba(255,255,255, 0.877) , 1795px 521px 0 0px rgba(255,255,255, 0.203) , 168px 97px 0 0px rgba(255,255,255, 0.084) , 387px 751px 0 0px rgba(255,255,255, 0.362) , 1606px 833px 0 0px rgba(255,255,255, 0.18) , 1624px 208px 0 0px rgba(255,255,255, 0.216) , 597px 527px 0 0px rgba(255,255,255, 0.845) , 1241px 637px 0 0px rgba(255,255,255, 0.335) , 1414px 563px 0 0px rgba(255,255,255, 0.74) , 760px 604px 0 0px rgba(255,255,255, 0.011) , 630px 1376px 0 0px rgba(255,255,255, 0.645) , 1628px 1739px 0 0px rgba(255,255,255, 0.157) , 223px 1535px 0 0px rgba(255,255,255, 0.952) , 952px 1594px 0 0px rgba(255,255,255, 0.86) , 204px 1326px 0 0px rgba(255,255,255, 0.575) , 1299px 613px 0 0px rgba(255,255,255, 0.345) , 942px 655px 0 0px rgba(255,255,255, 0.334) , 1765px 473px 0 0px rgba(255,255,255, 0.8) , 766px 1077px 0 0px rgba(255,255,255, 0.995) , 1308px 1404px 0 0px rgba(255,255,255, 0.917) , 1237px 1281px 0 0px rgba(255,255,255, 0.64) , 431px 621px 0 0px rgba(255,255,255, 0.893) , 1524px 1415px 0 0px rgba(255,255,255, 0.803) , 1479px 916px 0 0px rgba(255,255,255, 0.113) , 1276px 1081px 0 0px rgba(255,255,255, 0.09) , 100px 1597px 0 0px rgba(255,255,255, 0.333) , 1301px 696px 0 0px rgba(255,255,255, 0.369) , 932px 600px 0 0px rgba(255,255,255, 0.903) , 1641px 1465px 0 0px rgba(255,255,255, 0.143) , 210px 1499px 0 0px rgba(255,255,255, 0.463) , 1002px 733px 0 0px rgba(255,255,255, 0.166) , 510px 1538px 0 0px rgba(255,255,255, 0.928) , 215px 357px 0 0px rgba(255,255,255, 0.471) , 1189px 1454px 0 0px rgba(255,255,255, 0.857) , 1671px 554px 0 0px rgba(255,255,255, 0.952) , 1369px 1418px 0 0px rgba(255,255,255, 0.383) , 687px 382px 0 0px rgba(255,255,255, 0.974) , 1732px 1391px 0 0px rgba(255,255,255, 0.191) , 130px 693px 0 0px rgba(255,255,255, 0.481) , 1105px 359px 0 0px rgba(255,255,255, 0.838) , 1681px 1110px 0 0px rgba(255,255,255, 0.435) , 1298px 762px 0 0px rgba(255,255,255, 0.174) , 299px 300px 0 0px rgba(255,255,255, 0.093) , 1519px 677px 0 0px rgba(255,255,255, 0.091) , 724px 1202px 0 0px rgba(255,255,255, 0.329) , 555px 171px 0 0px rgba(255,255,255, 0.611) , 1471px 1714px 0 0px rgba(255,255,255, 0.482) , 1634px 1113px 0 0px rgba(255,255,255, 0.38) , 397px 982px 0 0px rgba(255,255,255, 0.983) , 1435px 258px 0 0px rgba(255,255,255, 0.105) , 817px 1071px 0 0px rgba(255,255,255, 0.369) , 356px 1073px 0 0px rgba(255,255,255, 0.354) , 1706px 825px 0 0px rgba(255,255,255, 0.964) , 691px 651px 0 0px rgba(255,255,255, 0.244) , 1179px 909px 0 0px rgba(255,255,255, 0.179) , 1365px 1143px 0 0px rgba(255,255,255, 0.393) , 970px 1501px 0 0px rgba(255,255,255, 0.799) , 244px 1458px 0 0px rgba(255,255,255, 0.322) , 1240px 142px 0 0px rgba(255,255,255, 0.691) , 710px 969px 0 0px rgba(255,255,255, 0.475) , 58px 1264px 0 0px rgba(255,255,255, 0.534) , 1139px 657px 0 0px rgba(255,255,255, 0.204) , 471px 1054px 0 0px rgba(255,255,255, 0.987) , 854px 1768px 0 0px rgba(255,255,255, 0.068) , 196px 771px 0 0px rgba(255,255,255, 0.147) , 1177px 806px 0 0px rgba(255,255,255, 0.874) , 37px 657px 0 0px rgba(255,255,255, 0.411) , 1790px 1686px 0 0px rgba(255,255,255, 0.858) , 1166px 454px 0 0px rgba(255,255,255, 0.284) , 1720px 1331px 0 0px rgba(255,255,255, 0.038) , 129px 702px 0 0px rgba(255,255,255, 0.674) , 624px 1044px 0 0px rgba(255,255,255, 0.906) , 1347px 1648px 0 0px rgba(255,255,255, 0.468) , 264px 1199px 0 0px rgba(255,255,255, 0.495) , 255px 1499px 0 0px rgba(255,255,255, 0.77) , 328px 142px 0 0px rgba(255,255,255, 0.289) , 477px 1245px 0 0px rgba(255,255,255, 0.384) , 733px 214px 0 0px rgba(255,255,255, 0.473) , 30px 1041px 0 0px rgba(255,255,255, 0.768) , 1505px 523px 0 0px rgba(255,255,255, 0.007) , 302px 778px 0 0px rgba(255,255,255, 0.544) , 684px 1164px 0 0px rgba(255,255,255, 0.905) , 1671px 690px 0 0px rgba(255,255,255, 0.378) , 814px 1638px 0 0px rgba(255,255,255, 0.2) , 277px 964px 0 0px rgba(255,255,255, 0.055) , 1772px 158px 0 0px rgba(255,255,255, 0.131) , 652px 861px 0 0px rgba(255,255,255, 0.037) , 1110px 539px 0 0px rgba(255,255,255, 0.099) , 832px 1678px 0 0px rgba(255,255,255, 0.147) , 104px 27px 0 0px rgba(255,255,255, 0.941) , 1489px 1693px 0 0px rgba(255,255,255, 0.702) , 1299px 590px 0 0px rgba(255,255,255, 0.493) , 294px 691px 0 0px rgba(255,255,255, 0.215) , 1228px 66px 0 0px rgba(255,255,255, 0.906) , 1759px 1484px 0 0px rgba(255,255,255, 0.672) , 758px 717px 0 0px rgba(255,255,255, 0.82) , 1753px 1425px 0 0px rgba(255,255,255, 0.757) , 316px 404px 0 0px rgba(255,255,255, 0.31) , 271px 7px 0 0px rgba(255,255,255, 0.259) , 1426px 887px 0 0px rgba(255,255,255, 0.83) , 1736px 654px 0 0px rgba(255,255,255, 0.003) , 1560px 271px 0 0px rgba(255,255,255, 0.608) , 49px 554px 0 0px rgba(255,255,255, 0.174) , 1544px 565px 0 0px rgba(255,255,255, 0.81) , 204px 1562px 0 0px rgba(255,255,255, 0.019) , 744px 233px 0 0px rgba(255,255,255, 0.89) , 599px 846px 0 0px rgba(255,255,255, 0.184) , 970px 100px 0 0px rgba(255,255,255, 0.079) , 744px 545px 0 0px rgba(255,255,255, 0.743) , 154px 1649px 0 0px rgba(255,255,255, 0.415) , 521px 193px 0 0px rgba(255,255,255, 0.752) , 1374px 1261px 0 0px rgba(255,255,255, 0.068) , 618px 892px 0 0px rgba(255,255,255, 0.28) , 520px 1322px 0 0px rgba(255,255,255, 0.456) , 458px 366px 0 0px rgba(255,255,255, 0.163) , 926px 905px 0 0px rgba(255,255,255, 0.955) , 226px 520px 0 0px rgba(255,255,255, 0.652) , 1594px 1527px 0 0px rgba(255,255,255, 0.274) , 497px 569px 0 0px rgba(255,255,255, 0.841) , 1510px 157px 0 0px rgba(255,255,255, 0.467) , 1311px 766px 0 0px rgba(255,255,255, 0.029) , 411px 180px 0 0px rgba(255,255,255, 0.934) , 475px 301px 0 0px rgba(255,255,255, 0.797) , 299px 145px 0 0px rgba(255,255,255, 0.455) , 505px 1032px 0 0px rgba(255,255,255, 0.549) , 973px 677px 0 0px rgba(255,255,255, 0.093) , 1759px 1083px 0 0px rgba(255,255,255, 0.279) , 400px 931px 0 0px rgba(255,255,255, 0.77) , 731px 455px 0 0px rgba(255,255,255, 0.286) , 1210px 1247px 0 0px rgba(255,255,255, 0.884) , 1194px 1615px 0 0px rgba(255,255,255, 0.495) , 328px 562px 0 0px rgba(255,255,255, 0.734) , 1103px 585px 0 0px rgba(255,255,255, 0.066) , 790px 742px 0 0px rgba(255,255,255, 0.257) , 1422px 173px 0 0px rgba(255,255,255, 0.411) , 16px 179px 0 0px rgba(255,255,255, 0.331) , 614px 1777px 0 0px rgba(255,255,255, 0.067) , 114px 46px 0 0px rgba(255,255,255, 0.631) , 758px 1727px 0 0px rgba(255,255,255, 0.262) , 495px 1568px 0 0px rgba(255,255,255, 0.363) , 314px 948px 0 0px rgba(255,255,255, 0.12) , 36px 996px 0 0px rgba(255,255,255, 0.683) , 1600px 279px 0 0px rgba(255,255,255, 0.692) , 124px 1016px 0 0px rgba(255,255,255, 0.144) , 913px 1428px 0 0px rgba(255,255,255, 0.809) , 753px 764px 0 0px rgba(255,255,255, 0.146) , 861px 751px 0 0px rgba(255,255,255, 0.882) , 1423px 1215px 0 0px rgba(255,255,255, 0.598) , 1240px 581px 0 0px rgba(255,255,255, 0.13) , 76px 630px 0 0px rgba(255,255,255, 0.721) , 332px 733px 0 0px rgba(255,255,255, 0.957) , 26px 1784px 0 0px rgba(255,255,255, 0.843) , 852px 1079px 0 0px rgba(255,255,255, 0.717) , 1063px 51px 0 0px rgba(255,255,255, 0.556) , 1772px 154px 0 0px rgba(255,255,255, 0.902) , 256px 458px 0 0px rgba(255,255,255, 0.815) , 518px 271px 0 0px rgba(255,255,255, 0.214) , 454px 317px 0 0px rgba(255,255,255, 0.985) , 931px 1774px 0 0px rgba(255,255,255, 0.059) , 629px 1767px 0 0px rgba(255,255,255, 0.522) , 1773px 1294px 0 0px rgba(255,255,255, 0.695) , 1758px 761px 0 0px rgba(255,255,255, 0.37) , 1259px 1708px 0 0px rgba(255,255,255, 0.385) , 1158px 174px 0 0px rgba(255,255,255, 0.672) , 269px 592px 0 0px rgba(255,255,255, 0.007) , 1485px 238px 0 0px rgba(255,255,255, 0.876) , 1799px 1356px 0 0px rgba(255,255,255, 0.579) , 377px 1448px 0 0px rgba(255,255,255, 0.55) , 1488px 772px 0 0px rgba(255,255,255, 0.337) , 394px 91px 0 0px rgba(255,255,255, 0.426) , 86px 751px 0 0px rgba(255,255,255, 0.314) , 210px 507px 0 0px rgba(255,255,255, 0.05) , 881px 319px 0 0px rgba(255,255,255, 0.691) , 1114px 169px 0 0px rgba(255,255,255, 0.104) , 373px 1394px 0 0px rgba(255,255,255, 0.399) , 24px 1601px 0 0px rgba(255,255,255, 0.8) , 1605px 1707px 0 0px rgba(255,255,255, 0.15) , 1027px 376px 0 0px rgba(255,255,255, 0.654) , 584px 983px 0 0px rgba(255,255,255, 0.563) , 588px 227px 0 0px rgba(255,255,255, 0.12) , 1521px 1257px 0 0px rgba(255,255,255, 0.578) , 963px 621px 0 0px rgba(255,255,255, 0.142) , 1158px 692px 0 0px rgba(255,255,255, 0.851) , 435px 1024px 0 0px rgba(255,255,255, 0.455) , 1228px 737px 0 0px rgba(255,255,255, 0.956) , 1700px 1768px 0 0px rgba(255,255,255, 0.43) , 1376px 1163px 0 0px rgba(255,255,255, 0.491) , 924px 1022px 0 0px rgba(255,255,255, 0.418) , 197px 783px 0 0px rgba(255,255,255, 0.687) , 652px 1711px 0 0px rgba(255,255,255, 0.635) , 378px 218px 0 0px rgba(255,255,255, 0.548) , 818px 340px 0 0px rgba(255,255,255, 0.342) , 1460px 546px 0 0px rgba(255,255,255, 0.977) , 199px 1165px 0 0px rgba(255,255,255, 0.552) , 425px 1106px 0 0px rgba(255,255,255, 0.665) , 1483px 23px 0 0px rgba(255,255,255, 0.147) , 935px 1579px 0 0px rgba(255,255,255, 0.602) , 1260px 1727px 0 0px rgba(255,255,255, 0.164) , 1099px 1751px 0 0px rgba(255,255,255, 0.714) , 846px 714px 0 0px rgba(255,255,255, 0.703) , 207px 999px 0 0px rgba(255,255,255, 0.426) , 423px 948px 0 0px rgba(255,255,255, 0.199) , 1151px 140px 0 0px rgba(255,255,255, 0.349) , 1397px 1250px 0 0px rgba(255,255,255, 0.708) , 296px 356px 0 0px rgba(255,255,255, 0.679) , 198px 984px 0 0px rgba(255,255,255, 0.751) , 1577px 102px 0 0px rgba(255,255,255, 0.337) , 984px 756px 0 0px rgba(255,255,255, 0.54) , 281px 1306px 0 0px rgba(255,255,255, 0.89) , 275px 1378px 0 0px rgba(255,255,255, 0.621) , 1150px 1750px 0 0px rgba(255,255,255, 0.96) , 394px 1607px 0 0px rgba(255,255,255, 0.355) , 1057px 1011px 0 0px rgba(255,255,255, 0.917) , 1106px 999px 0 0px rgba(255,255,255, 0.565) , 62px 1615px 0 0px rgba(255,255,255, 0.923) , 1159px 722px 0 0px rgba(255,255,255, 0.249) , 717px 1744px 0 0px rgba(255,255,255, 0.204) , 1150px 123px 0 0px rgba(255,255,255, 0.402) , 1173px 418px 0 0px rgba(255,255,255, 0.999) , 1088px 1585px 0 0px rgba(255,255,255, 0.616) , 522px 713px 0 0px rgba(255,255,255, 0.441) , 1700px 926px 0 0px rgba(255,255,255, 0.627) , 567px 73px 0 0px rgba(255,255,255, 0.09) , 232px 1334px 0 0px rgba(255,255,255, 0.856) , 823px 1701px 0 0px rgba(255,255,255, 0.399) , 1181px 134px 0 0px rgba(255,255,255, 0.152) , 1403px 829px 0 0px rgba(255,255,255, 0.782) , 574px 366px 0 0px rgba(255,255,255, 0.347) , 1783px 1567px 0 0px rgba(255,255,255, 0.12) , 1575px 1596px 0 0px rgba(255,255,255, 0.657) , 1721px 100px 0 0px rgba(255,255,255, 0.904) , 1734px 1409px 0 0px rgba(255,255,255, 0.503) , 811px 1553px 0 0px rgba(255,255,255, 0.721) , 105px 1034px 0 0px rgba(255,255,255, 0.949) , 1334px 598px 0 0px rgba(255,255,255, 0.876) , 467px 998px 0 0px rgba(255,255,255, 0.72) , 1707px 840px 0 0px rgba(255,255,255, 0.157) , 796px 390px 0 0px rgba(255,255,255, 0.981) , 1119px 469px 0 0px rgba(255,255,255, 0.767) , 665px 1554px 0 0px rgba(255,255,255, 0.146) , 287px 864px 0 0px rgba(255,255,255, 0.849) , 1321px 750px 0 0px rgba(255,255,255, 0.436) , 1388px 5px 0 0px rgba(255,255,255, 0.043) , 774px 152px 0 0px rgba(255,255,255, 0.821) , 742px 1417px 0 0px rgba(255,255,255, 0.484) , 389px 1079px 0 0px rgba(255,255,255, 0.297) , 1650px 1115px 0 0px rgba(255,255,255, 0.321) , 971px 717px 0 0px rgba(255,255,255, 0.352) , 426px 1797px 0 0px rgba(255,255,255, 0.512) , 1654px 586px 0 0px rgba(255,255,255, 0.525) , 1280px 1137px 0 0px rgba(255,255,255, 0.07) , 258px 1017px 0 0px rgba(255,255,255, 0.64) , 97px 1085px 0 0px rgba(255,255,255, 0.147) , 983px 1077px 0 0px rgba(255,255,255, 0.986) , 357px 379px 0 0px rgba(255,255,255, 0.7) , 1249px 712px 0 0px rgba(255,255,255, 0.114) , 1075px 855px 0 0px rgba(255,255,255, 0.406) , 888px 856px 0 0px rgba(255,255,255, 0.876) , 1116px 1012px 0 0px rgba(255,255,255, 0.015) , 1272px 1291px 0 0px rgba(255,255,255, 0.305) , 642px 446px 0 0px rgba(255,255,255, 0.814) , 1216px 1408px 0 0px rgba(255,255,255, 0.84) , 1642px 87px 0 0px rgba(255,255,255, 0.466) , 1074px 1194px 0 0px rgba(255,255,255, 0.524) , 1009px 1789px 0 0px rgba(255,255,255, 0.791) , 328px 750px 0 0px rgba(255,255,255, 0.382) , 1433px 1703px 0 0px rgba(255,255,255, 0.322) , 553px 536px 0 0px rgba(255,255,255, 0.805) , 1721px 1067px 0 0px rgba(255,255,255, 0.858) , 1222px 559px 0 0px rgba(255,255,255, 0.125) , 1039px 1795px 0 0px rgba(255,255,255, 0.225) , 322px 287px 0 0px rgba(255,255,255, 0.114) , 1719px 1427px 0 0px rgba(255,255,255, 0.99) , 1488px 1256px 0 0px rgba(255,255,255, 0.016) , 1024px 523px 0 0px rgba(255,255,255, 0.118) , 1227px 131px 0 0px rgba(255,255,255, 0.066) , 744px 1509px 0 0px rgba(255,255,255, 0.798) , 1406px 544px 0 0px rgba(255,255,255, 0.95) , 1493px 600px 0 0px rgba(255,255,255, 0.267) , 850px 682px 0 0px rgba(255,255,255, 0.542) , 1108px 703px 0 0px rgba(255,255,255, 0.23) , 1142px 1027px 0 0px rgba(255,255,255, 0.088) , 1102px 541px 0 0px rgba(255,255,255, 0.357) , 827px 1339px 0 0px rgba(255,255,255, 0.26) , 559px 137px 0 0px rgba(255,255,255, 0.6) , 395px 1244px 0 0px rgba(255,255,255, 0.225) , 118px 1168px 0 0px rgba(255,255,255, 0.845) , 768px 80px 0 0px rgba(255,255,255, 0.415) , 1288px 1156px 0 0px rgba(255,255,255, 0.602) , 402px 995px 0 0px rgba(255,255,255, 0.051) , 1272px 1369px 0 0px rgba(255,255,255, 0.498) , 1512px 898px 0 0px rgba(255,255,255, 0.993) , 1212px 1131px 0 0px rgba(255,255,255, 0.523) , 607px 973px 0 0px rgba(255,255,255, 0.627) , 85px 1256px 0 0px rgba(255,255,255, 0.694) , 261px 465px 0 0px rgba(255,255,255, 0.635) , 255px 972px 0 0px rgba(255,255,255, 0.182) , 703px 995px 0 0px rgba(255,255,255, 0.982) , 551px 142px 0 0px rgba(255,255,255, 0.928) , 446px 1004px 0 0px rgba(255,255,255, 0.828) , 416px 1520px 0 0px rgba(255,255,255, 0.019) , 401px 270px 0 0px rgba(255,255,255, 0.282) , 88px 258px 0 0px rgba(255,255,255, 0.061) , 1021px 7px 0 0px rgba(255,255,255, 0.139) , 1475px 1618px 0 0px rgba(255,255,255, 0.136) , 465px 1706px 0 0px rgba(255,255,255, 0.903) , 1674px 958px 0 0px rgba(255,255,255, 0.847) , 535px 1052px 0 0px rgba(255,255,255, 0.567) , 118px 1374px 0 0px rgba(255,255,255, 0.038) , 608px 986px 0 0px rgba(255,255,255, 0.99) , 1173px 1693px 0 0px rgba(255,255,255, 0.713) , 1586px 1603px 0 0px rgba(255,255,255, 0.005) , 1428px 1547px 0 0px rgba(255,255,255, 0.308) , 826px 461px 0 0px rgba(255,255,255, 0.249) , 1745px 1105px 0 0px rgba(255,255,255, 0.253) , 1426px 1282px 0 0px rgba(255,255,255, 0.586) , 723px 666px 0 0px rgba(255,255,255, 0.377) , 1667px 271px 0 0px rgba(255,255,255, 0.841) , 44px 1309px 0 0px rgba(255,255,255, 0.269) , 409px 81px 0 0px rgba(255,255,255, 0.807) , 1265px 1492px 0 0px rgba(255,255,255, 0.536) , 1134px 85px 0 0px rgba(255,255,255, 0.151) , 1425px 813px 0 0px rgba(255,255,255, 0.876) , 1463px 487px 0 0px rgba(255,255,255, 0.953) , 230px 499px 0 0px rgba(255,255,255, 0.563) , 878px 1769px 0 0px rgba(255,255,255, 0.597) , 276px 215px 0 0px rgba(255,255,255, 0.039) , 662px 1779px 0 0px rgba(255,255,255, 0.101) , 1166px 407px 0 0px rgba(255,255,255, 0.684) , 706px 263px 0 0px rgba(255,255,255, 0.629) , 1346px 1234px 0 0px rgba(255,255,255, 0.94) , 1578px 1242px 0 0px rgba(255,255,255, 0.833) , 1670px 296px 0 0px rgba(255,255,255, 0.718) , 1171px 557px 0 0px rgba(255,255,255, 0.366) , 1554px 654px 0 0px rgba(255,255,255, 0.699) , 1680px 1585px 0 0px rgba(255,255,255, 0.092) , 616px 1416px 0 0px rgba(255,255,255, 0.887) , 1402px 1148px 0 0px rgba(255,255,255, 0.113) , 1340px 527px 0 0px rgba(255,255,255, 0.918) , 1142px 1550px 0 0px rgba(255,255,255, 0.112) , 1423px 1508px 0 0px rgba(255,255,255, 0.672) , 1717px 146px 0 0px rgba(255,255,255, 0.134) , 1174px 227px 0 0px rgba(255,255,255, 0.809) , 334px 179px 0 0px rgba(255,255,255, 0.92) , 1475px 108px 0 0px rgba(255,255,255, 0.187) , 1670px 1266px 0 0px rgba(255,255,255, 0.822) , 1588px 1171px 0 0px rgba(255,255,255, 0.827) , 340px 1294px 0 0px rgba(255,255,255, 0.81) , 112px 159px 0 0px rgba(255,255,255, 0.321) , 2px 413px 0 0px rgba(255,255,255, 0.048) , 1390px 641px 0 0px rgba(255,255,255, 0.287) , 1097px 159px 0 0px rgba(255,255,255, 0.065) , 1195px 589px 0 0px rgba(255,255,255, 0.618) , 1769px 1676px 0 0px rgba(255,255,255, 0.544) , 656px 34px 0 0px rgba(255,255,255, 0.564) , 202px 1160px 0 0px rgba(255,255,255, 0.805) , 741px 744px 0 0px rgba(255,255,255, 0.388) , 536px 1554px 0 0px rgba(255,255,255, 0.008) , 179px 197px 0 0px rgba(255,255,255, 0.687) , 7px 240px 0 0px rgba(255,255,255, 0.935) , 730px 891px 0 0px rgba(255,255,255, 0.001) , 1485px 1533px 0 0px rgba(255,255,255, 0.862) , 1167px 283px 0 0px rgba(255,255,255, 0.905) , 973px 475px 0 0px rgba(255,255,255, 0.756) , 562px 558px 0 0px rgba(255,255,255, 0.636) , 1241px 465px 0 0px rgba(255,255,255, 0.436) , 18px 969px 0 0px rgba(255,255,255, 0.701) , 1340px 908px 0 0px rgba(255,255,255, 0.671) , 163px 192px 0 0px rgba(255,255,255, 0.829) , 670px 1351px 0 0px rgba(255,255,255, 0.833) , 1350px 1565px 0 0px rgba(255,255,255, 0.537) , 1462px 216px 0 0px rgba(255,255,255, 0.534) , 265px 359px 0 0px rgba(255,255,255, 0.558) , 748px 783px 0 0px rgba(255,255,255, 0.388) , 496px 209px 0 0px rgba(255,255,255, 0.41) , 956px 600px 0 0px rgba(255,255,255, 0.388) , 1464px 70px 0 0px rgba(255,255,255, 0.186) , 1336px 749px 0 0px rgba(255,255,255, 0.313) , 1673px 234px 0 0px rgba(255,255,255, 0.318) , 428px 1615px 0 0px rgba(255,255,255, 0.602) , 1193px 1062px 0 0px rgba(255,255,255, 0.165) , 345px 1576px 0 0px rgba(255,255,255, 0.279) , 796px 342px 0 0px rgba(255,255,255, 0.091) , 56px 1779px 0 0px rgba(255,255,255, 0.94) , 1341px 1250px 0 0px rgba(255,255,255, 0.34) , 614px 1270px 0 0px rgba(255,255,255, 0.782) , 852px 1682px 0 0px rgba(255,255,255, 0.711) , 117px 537px 0 0px rgba(255,255,255, 0.208) , 655px 124px 0 0px rgba(255,255,255, 0.572) , 635px 1554px 0 0px rgba(255,255,255, 0.821) , 505px 812px 0 0px rgba(255,255,255, 0.049) , 1476px 1217px 0 0px rgba(255,255,255, 0.709) , 477px 1069px 0 0px rgba(255,255,255, 0.938) , 1504px 728px 0 0px rgba(255,255,255, 0.466) , 513px 1616px 0 0px rgba(255,255,255, 0.826) , 52px 1733px 0 0px rgba(255,255,255, 0.655) , 476px 17px 0 0px rgba(255,255,255, 0.897) , 249px 1387px 0 0px rgba(255,255,255, 0.811) , 826px 976px 0 0px rgba(255,255,255, 0.193) , 1655px 853px 0 0px rgba(255,255,255, 0.275) , 510px 1448px 0 0px rgba(255,255,255, 0.263) , 1507px 160px 0 0px rgba(255,255,255, 0.462) , 1399px 826px 0 0px rgba(255,255,255, 0.373) , 1656px 1703px 0 0px rgba(255,255,255, 0.922) , 938px 1374px 0 0px rgba(255,255,255, 0.331) , 43px 124px 0 0px rgba(255,255,255, 0.73) , 360px 628px 0 0px rgba(255,255,255, 0.992) , 124px 352px 0 0px rgba(255,255,255, 0.169) , 1429px 1338px 0 0px rgba(255,255,255, 0.034) , 1661px 749px 0 0px rgba(255,255,255, 0.732) , 875px 1639px 0 0px rgba(255,255,255, 0.994) , 537px 915px 0 0px rgba(255,255,255, 0.468) , 226px 784px 0 0px rgba(255,255,255, 0.233) , 68px 1773px 0 0px rgba(255,255,255, 0.094) , 1443px 443px 0 0px rgba(255,255,255, 0.978) , 70px 1494px 0 0px rgba(255,255,255, 0.318) , 341px 963px 0 0px rgba(255,255,255, 0.81) , 256px 839px 0 0px rgba(255,255,255, 0.788) , 154px 704px 0 0px rgba(255,255,255, 0.719) , 1480px 447px 0 0px rgba(255,255,255, 0.87) , 566px 1668px 0 0px rgba(255,255,255, 0.13) , 1084px 1469px 0 0px rgba(255,255,255, 0.472) , 112px 1313px 0 0px rgba(255,255,255, 0.147) , 91px 1732px 0 0px rgba(255,255,255, 0.54) , 1772px 567px 0 0px rgba(255,255,255, 0.282) , 253px 865px 0 0px rgba(255,255,255, 0.292) , 527px 1751px 0 0px rgba(255,255,255, 0.049) , 203px 919px 0 0px rgba(255,255,255, 0.882) , 753px 794px 0 0px rgba(255,255,255, 0.769) , 258px 1748px 0 0px rgba(255,255,255, 0.834) , 1123px 1663px 0 0px rgba(255,255,255, 0.192) , 1738px 612px 0 0px rgba(255,255,255, 0.378) , 824px 1338px 0 0px rgba(255,255,255, 0.007);
  border-radius: 100px;
}
.solar-syst div {
  border-radius: 1000px;
  top: 50%;
  left: 50%;
  position: absolute;
  /* z-index: 999; */
}
.solar-syst div:not(.sun) {
  border: 1px solid rgba(102, 166, 229, 0.12);
}
.solar-syst div:not(.sun):before {
  left: 50%;
  border-radius: 100px;
  content: "";
  position: absolute;
}
.solar-syst div:not(.asteroids-belt):before {
  box-shadow: inset 0 6px 0 -2px rgba(0, 0, 0, 0.25);
}

.sun {
  background: radial-gradient(ellipse at center, #ffd000 1%, #f9b700 39%, #f9b700 39%, #e06317 100%);
  height: 40px;
  width: 40px;
  margin-top: -20px;
  margin-left: -20px;
  background-clip: padding-box;
  border: 0 !important;
  background-position: -28px -103px;
  background-size: 175%;
  box-shadow: 0 0 10px 2px rgba(255, 107, 0, 0.4), 0 0 22px 11px rgba(255, 203, 0, 0.13);
}

.mercury {
  height: 70px;
  width: 70px;
  margin-top: -35px;
  margin-left: -35px;
  -webkit-animation: orb 7.1867343561s linear infinite;
          animation: orb 7.1867343561s linear infinite;
}
.mercury:before {
  height: 4px;
  width: 4px;
  background: #9f5e26;
  margin-top: -2px;
  margin-left: -2px;
}

.venus {
  height: 100px;
  width: 100px;
  margin-top: -50px;
  margin-left: -50px;
  -webkit-animation: orb 18.4555338265s linear infinite;
          animation: orb 18.4555338265s linear infinite;
}
.venus:before {
  height: 8px;
  width: 8px;
  background: #BEB768;
  margin-top: -4px;
  margin-left: -4px;
}

.earth {
  height: 145px;
  width: 145px;
  margin-top: -72.5px;
  margin-left: -72.5px;
  -webkit-animation: orb 30s linear infinite;
          animation: orb 30s linear infinite;
}
.earth:before {
  height: 6px;
  width: 6px;
  background: #11abe9;
  margin-top: -3px;
  margin-left: -3px;
}
.earth:after {
  position: absolute;
  content: "";
  height: 18px;
  width: 18px;
  left: 50%;
  top: 0px;
  margin-left: -9px;
  margin-top: -9px;
  border-radius: 100px;
  box-shadow: 0 -10px 0 -8px grey;
  -webkit-animation: orb 2.2440352158s linear infinite;
          animation: orb 2.2440352158s linear infinite;
}

.mars {
  height: 190px;
  width: 190px;
  margin-top: -95px;
  margin-left: -95px;
  -webkit-animation: orb 56.4261314589s linear infinite;
          animation: orb 56.4261314589s linear infinite;
}
.mars:before {
  height: 6px;
  width: 6px;
  background: #cf3921;
  margin-top: -3px;
  margin-left: -3px;
}

.jupiter {
  height: 340px;
  width: 340px;
  margin-top: -170px;
  margin-left: -170px;
  -webkit-animation: orb 355.7228171013s linear infinite;
          animation: orb 355.7228171013s linear infinite;
}
.jupiter:before {
  height: 18px;
  width: 18px;
  background: #c76e2a;
  margin-top: -9px;
  margin-left: -9px;
}

.saturn {
  height: 440px;
  width: 440px;
  margin-top: -220px;
  margin-left: -220px;
  -webkit-animation: orb 882.6952471456s linear infinite;
          animation: orb 882.6952471456s linear infinite;
}
.saturn:before {
  height: 12px;
  width: 12px;
  background: #e7c194;
  margin-top: -6px;
  margin-left: -6px;
}
.saturn:after {
  position: absolute;
  content: "";
  height: 2.34%;
  width: 4.676%;
  left: 50%;
  top: 0px;
  transform: rotateZ(-52deg);
  margin-left: -2.3%;
  margin-top: -1.2%;
  border-radius: 50% 50% 50% 50%;
  box-shadow: 0 1px 0 1px #987641, 3px 1px 0 #987641, -3px 1px 0 #987641;
  -webkit-animation: orb 882.6952471456s linear infinite;
          animation: orb 882.6952471456s linear infinite;
  animation-direction: reverse;
  transform-origin: 52% 60%;
}

.uranus {
  height: 520px;
  width: 520px;
  margin-top: -260px;
  margin-left: -260px;
  -webkit-animation: orb 2512.4001967933s linear infinite;
          animation: orb 2512.4001967933s linear infinite;
}
.uranus:before {
  height: 10px;
  width: 10px;
  background: #b5e3e3;
  margin-top: -5px;
  margin-left: -5px;
}

.neptune {
  height: 630px;
  width: 630px;
  margin-top: -315px;
  margin-left: -315px;
  -webkit-animation: orb 4911.7838624549s linear infinite;
          animation: orb 4911.7838624549s linear infinite;
}
.neptune:before {
  height: 10px;
  width: 10px;
  background: #175e9e;
  margin-top: -5px;
  margin-left: -5px;
}

.asteroids-belt {
  opacity: 0.7;
  border-color: transparent !important;
  height: 300px;
  width: 300px;
  margin-top: -150px;
  margin-left: -150px;
  -webkit-animation: orb 179.9558282773s linear infinite;
          animation: orb 179.9558282773s linear infinite;
  overflow: hidden;
}
.asteroids-belt:before {
  top: 50%;
  height: 210px;
  width: 210px;
  margin-left: -105px;
  margin-top: -105px;
  background: transparent;
  border-radius: 140px !important;
  box-shadow: -54px -86px 0 -104px rgba(255, 255, 255, 0.496) , 145px -54px 0 -104px rgba(255,255,255, 0.1) , -113px 68px 0 -104px rgba(255,255,255, 0.606) , 8px 38px 0 -104px rgba(255,255,255, 0.739) , 33px -90px 0 -104px rgba(255,255,255, 0.124) , 103px -141px 0 -104px rgba(255,255,255, 0.439) , -95px -17px 0 -104px rgba(255,255,255, 0.052) , -109px 70px 0 -104px rgba(255,255,255, 0.258) , -80px 145px 0 -104px rgba(255,255,255, 0.911) , -64px -130px 0 -104px rgba(255,255,255, 0.422) , -95px -81px 0 -104px rgba(255,255,255, 0.989) , -127px 65px 0 -104px rgba(255,255,255, 0.173) , -1px -113px 0 -104px rgba(255,255,255, 0.672) , -34px 68px 0 -104px rgba(255,255,255, 0.535) , 51px 45px 0 -104px rgba(255,255,255, 0.656) , -40px -144px 0 -104px rgba(255,255,255, 0.682) , 7px -7px 0 -104px rgba(255,255,255, 0.524) , -56px 4px 0 -104px rgba(255,255,255, 0.737) , 50px 19px 0 -104px rgba(255,255,255, 0.88) , -16px 17px 0 -104px rgba(255,255,255, 0.918) , -68px -43px 0 -104px rgba(255,255,255, 0.842) , -56px 42px 0 -104px rgba(255,255,255, 0.698) , 137px -38px 0 -104px rgba(255,255,255, 0.037) , -137px -81px 0 -104px rgba(255,255,255, 0.451) , 94px -104px 0 -104px rgba(255,255,255, 0.539) , 104px 49px 0 -104px rgba(255,255,255, 0.914) , 78px 71px 0 -104px rgba(255,255,255, 0.651) , 117px 96px 0 -104px rgba(255,255,255, 0.938) , -104px 25px 0 -104px rgba(255,255,255, 0.929) , 43px 77px 0 -104px rgba(255,255,255, 0.547) , 124px -20px 0 -104px rgba(255,255,255, 0.554) , -14px 61px 0 -104px rgba(255,255,255, 0.162) , -56px 107px 0 -104px rgba(255,255,255, 0.565) , -84px 138px 0 -104px rgba(255,255,255, 0.394) , -95px -73px 0 -104px rgba(255,255,255, 0.802) , 12px -40px 0 -104px rgba(255,255,255, 0.461) , -92px 13px 0 -104px rgba(255,255,255, 0.096) , -66px 127px 0 -104px rgba(255,255,255, 0.265) , -30px -2px 0 -104px rgba(255,255,255, 0.271) , 144px -18px 0 -104px rgba(255,255,255, 0.636) , -74px 118px 0 -104px rgba(255,255,255, 0.482) , 59px -137px 0 -104px rgba(255,255,255, 0.418) , -75px -2px 0 -104px rgba(255,255,255, 0.367) , -38px 137px 0 -104px rgba(255,255,255, 0.392) , -28px 43px 0 -104px rgba(255,255,255, 0.23) , 20px 45px 0 -104px rgba(255,255,255, 0.123) , 129px -135px 0 -104px rgba(255,255,255, 0.998) , 94px 83px 0 -104px rgba(255,255,255, 0.99) , 73px -64px 0 -104px rgba(255,255,255, 0.847) , 83px 64px 0 -104px rgba(255,255,255, 0.132) , 9px -87px 0 -104px rgba(255,255,255, 0.622) , -104px -61px 0 -104px rgba(255,255,255, 0.473) , 55px -102px 0 -104px rgba(255,255,255, 0.162) , 44px 100px 0 -104px rgba(255,255,255, 0.838) , 41px -104px 0 -104px rgba(255,255,255, 0.186) , 127px -143px 0 -104px rgba(255,255,255, 0.82) , -117px 92px 0 -104px rgba(255,255,255, 0.332) , 107px -84px 0 -104px rgba(255,255,255, 0.092) , -51px 35px 0 -104px rgba(255,255,255, 0.524) , -104px 38px 0 -104px rgba(255,255,255, 0.206) , -90px -144px 0 -104px rgba(255,255,255, 0.267) , 78px 66px 0 -104px rgba(255,255,255, 0.193) , 25px -46px 0 -104px rgba(255,255,255, 0.249) , -27px -64px 0 -104px rgba(255,255,255, 0.585) , 20px -79px 0 -104px rgba(255,255,255, 0.933) , -57px -100px 0 -104px rgba(255,255,255, 0.575) , 47px -25px 0 -104px rgba(255,255,255, 0.871) , -2px -53px 0 -104px rgba(255,255,255, 0.741) , 87px -44px 0 -104px rgba(255,255,255, 0.52) , -13px 76px 0 -104px rgba(255,255,255, 0.456) , 18px 97px 0 -104px rgba(255,255,255, 0.02) , -24px -86px 0 -104px rgba(255,255,255, 0.623) , -105px 7px 0 -104px rgba(255,255,255, 0.882) , 122px 15px 0 -104px rgba(255,255,255, 0.249) , 47px 54px 0 -104px rgba(255,255,255, 0.274) , 17px -35px 0 -104px rgba(255,255,255, 0.063) , 81px -46px 0 -104px rgba(255,255,255, 0.438) , -46px -105px 0 -104px rgba(255,255,255, 0.285) , 104px -128px 0 -104px rgba(255,255,255, 0.69) , 4px -81px 0 -104px rgba(255,255,255, 0.639) , -103px -61px 0 -104px rgba(255,255,255, 0.765) , -57px -114px 0 -104px rgba(255,255,255, 0.013) , -33px -29px 0 -104px rgba(255,255,255, 0.23) , -73px -124px 0 -104px rgba(255,255,255, 0.122) , -122px 95px 0 -104px rgba(255,255,255, 0.214) , -59px 58px 0 -104px rgba(255,255,255, 0.209) , -108px 53px 0 -104px rgba(255,255,255, 0.915) , 90px 69px 0 -104px rgba(255,255,255, 0.005) , -88px 54px 0 -104px rgba(255,255,255, 0.292) , -50px -138px 0 -104px rgba(255,255,255, 0.491) , 54px 40px 0 -104px rgba(255,255,255, 0.739) , -27px -128px 0 -104px rgba(255,255,255, 0.152) , 12px -59px 0 -104px rgba(255,255,255, 0.736) , 12px -21px 0 -104px rgba(255,255,255, 0.578) , -101px -64px 0 -104px rgba(255,255,255, 0.033) , -3px -140px 0 -104px rgba(255,255,255, 0.3) , 5px -5px 0 -104px rgba(255,255,255, 0.355) , -8px 141px 0 -104px rgba(255,255,255, 0.972) , 134px 10px 0 -104px rgba(255,255,255, 0.327) , -12px -22px 0 -104px rgba(255,255,255, 0.083) , 113px -9px 0 -104px rgba(255,255,255, 0.669) , 51px 12px 0 -104px rgba(255,255,255, 0.799) , 80px -142px 0 -104px rgba(255,255,255, 0.154) , 104px 145px 0 -104px rgba(255,255,255, 0.349) , 9px 12px 0 -104px rgba(255,255,255, 0.546) , 92px 145px 0 -104px rgba(255,255,255, 0.591) , -73px 8px 0 -104px rgba(255,255,255, 0.295) , 46px -140px 0 -104px rgba(255,255,255, 0.094) , -79px -66px 0 -104px rgba(255,255,255, 0.035) , 12px -83px 0 -104px rgba(255,255,255, 0.907) , -131px 19px 0 -104px rgba(255,255,255, 0.766) , -13px 104px 0 -104px rgba(255,255,255, 0.959) , -96px 61px 0 -104px rgba(255,255,255, 0.138) , -65px 74px 0 -104px rgba(255,255,255, 0.797) , -135px -20px 0 -104px rgba(255,255,255, 0.765) , 1px 107px 0 -104px rgba(255,255,255, 0.007) , -62px -66px 0 -104px rgba(255,255,255, 0.641) , -2px -67px 0 -104px rgba(255,255,255, 0.246) , 63px 90px 0 -104px rgba(255,255,255, 0.04) , -85px 120px 0 -104px rgba(255,255,255, 0.189) , 116px 1px 0 -104px rgba(255,255,255, 0.687) , 127px -87px 0 -104px rgba(255,255,255, 0.197) , -2px -110px 0 -104px rgba(255,255,255, 0.491) , -135px -90px 0 -104px rgba(255,255,255, 0.98) , -19px 137px 0 -104px rgba(255,255,255, 0.321) , 42px -115px 0 -104px rgba(255,255,255, 0.411) , -86px 112px 0 -104px rgba(255,255,255, 0.43) , 94px -57px 0 -104px rgba(255,255,255, 0.243) , -136px 38px 0 -104px rgba(255,255,255, 0.378) , -42px -25px 0 -104px rgba(255,255,255, 0.552) , -80px 6px 0 -104px rgba(255,255,255, 0.122) , -44px -52px 0 -104px rgba(255,255,255, 0.248) , 9px -52px 0 -104px rgba(255,255,255, 0.424) , 56px -13px 0 -104px rgba(255,255,255, 0.747) , 108px -118px 0 -104px rgba(255,255,255, 0.753) , 114px -13px 0 -104px rgba(255,255,255, 0.615) , -29px 34px 0 -104px rgba(255,255,255, 0.278) , 55px -46px 0 -104px rgba(255,255,255, 0.707) , 123px 59px 0 -104px rgba(255,255,255, 0.142) , -113px -38px 0 -104px rgba(255,255,255, 0.792) , -112px 34px 0 -104px rgba(255,255,255, 0.896) , 111px 104px 0 -104px rgba(255,255,255, 0.853) , 125px 140px 0 -104px rgba(255,255,255, 0.793) , -76px 2px 0 -104px rgba(255,255,255, 0.163) , 127px 44px 0 -104px rgba(255,255,255, 0.307) , -50px -12px 0 -104px rgba(255,255,255, 0.871) , -35px -92px 0 -104px rgba(255,255,255, 0.095) , 138px -4px 0 -104px rgba(255,255,255, 0.88) , -124px -34px 0 -104px rgba(255,255,255, 0.566) , -99px -86px 0 -104px rgba(255,255,255, 0.55) , -72px 136px 0 -104px rgba(255,255,255, 0.09) , -125px 112px 0 -104px rgba(255,255,255, 0.948) , -112px -69px 0 -104px rgba(255,255,255, 0.465) , -106px 106px 0 -104px rgba(255,255,255, 0.318) , -33px 18px 0 -104px rgba(255,255,255, 0.306) , -80px -39px 0 -104px rgba(255,255,255, 0.478) , 134px 114px 0 -104px rgba(255,255,255, 0.238) , 144px -68px 0 -104px rgba(255,255,255, 0.83) , -88px -128px 0 -104px rgba(255,255,255, 0.531) , 59px -135px 0 -104px rgba(255,255,255, 0.659) , -107px 118px 0 -104px rgba(255,255,255, 0.986) , -1px -24px 0 -104px rgba(255,255,255, 0.578) , -64px -19px 0 -104px rgba(255,255,255, 0.423) , 38px -82px 0 -104px rgba(255,255,255, 0.046) , -69px 72px 0 -104px rgba(255,255,255, 0.841) , -18px -79px 0 -104px rgba(255,255,255, 0.373) , -23px -16px 0 -104px rgba(255,255,255, 0.66) , -84px 51px 0 -104px rgba(255,255,255, 0.5) , 137px -80px 0 -104px rgba(255,255,255, 0.677) , -30px -45px 0 -104px rgba(255,255,255, 0.726) , 4px -77px 0 -104px rgba(255,255,255, 0.897) , 65px -133px 0 -104px rgba(255,255,255, 0.094) , 136px -61px 0 -104px rgba(255,255,255, 0.046) , 35px 119px 0 -104px rgba(255,255,255, 0.369) , -53px 15px 0 -104px rgba(255,255,255, 0.845) , 16px -119px 0 -104px rgba(255,255,255, 0.774) , 62px -111px 0 -104px rgba(255,255,255, 0.767) , -93px 35px 0 -104px rgba(255,255,255, 0.601) , 32px -25px 0 -104px rgba(255,255,255, 0.249) , 40px 67px 0 -104px rgba(255,255,255, 0.005) , 137px -25px 0 -104px rgba(255,255,255, 0.417) , 92px 94px 0 -104px rgba(255,255,255, 0.288) , 53px -44px 0 -104px rgba(255,255,255, 0.865) , -120px 1px 0 -104px rgba(255,255,255, 0.626) , -81px 76px 0 -104px rgba(255,255,255, 0.007) , -1px -74px 0 -104px rgba(255,255,255, 0.237) , -17px 15px 0 -104px rgba(255,255,255, 0.008) , 140px 91px 0 -104px rgba(255,255,255, 0.099) , 120px 50px 0 -104px rgba(255,255,255, 0.353) , 25px 44px 0 -104px rgba(255,255,255, 0.367) , -21px 12px 0 -104px rgba(255,255,255, 0.492) , 54px -136px 0 -104px rgba(255,255,255, 0.814) , -92px -65px 0 -104px rgba(255,255,255, 0.675) , 37px -48px 0 -104px rgba(255,255,255, 0.753) , 127px -35px 0 -104px rgba(255,255,255, 0.575) , -102px -86px 0 -104px rgba(255,255,255, 0.842) , -135px 54px 0 -104px rgba(255,255,255, 0.842) , 17px -118px 0 -104px rgba(255,255,255, 0.779) , 89px -62px 0 -104px rgba(255,255,255, 0.318) , -140px -26px 0 -104px rgba(255,255,255, 0.076) , 118px 60px 0 -104px rgba(255,255,255, 0.829) , -103px 14px 0 -104px rgba(255,255,255, 0.728) , 137px -128px 0 -104px rgba(255,255,255, 0.124) , 88px -123px 0 -104px rgba(255,255,255, 0.885) , -17px 46px 0 -104px rgba(255,255,255, 0.146) , -111px -57px 0 -104px rgba(255,255,255, 0.384) , 73px -113px 0 -104px rgba(255,255,255, 0.159) , -65px -34px 0 -104px rgba(255,255,255, 0.461) , -129px 99px 0 -104px rgba(255,255,255, 0.977) , -29px 100px 0 -104px rgba(255,255,255, 0.714) , 109px 57px 0 -104px rgba(255,255,255, 0.949) , 23px 55px 0 -104px rgba(255,255,255, 0.695) , 107px -102px 0 -104px rgba(255,255,255, 0.703) , 96px -91px 0 -104px rgba(255,255,255, 0.41) , -139px 95px 0 -104px rgba(255,255,255, 0.633) , 109px 88px 0 -104px rgba(255,255,255, 0.165) , 121px 118px 0 -104px rgba(255,255,255, 0.8) , -40px -59px 0 -104px rgba(255,255,255, 0.312) , -48px 126px 0 -104px rgba(255,255,255, 0.303) , 20px 63px 0 -104px rgba(255,255,255, 0.854) , -17px 78px 0 -104px rgba(255,255,255, 0.035) , 108px -41px 0 -104px rgba(255,255,255, 0.853) , -144px 10px 0 -104px rgba(255,255,255, 0.882) , 86px 49px 0 -104px rgba(255,255,255, 0.699) , 96px -80px 0 -104px rgba(255,255,255, 0.85) , -75px 11px 0 -104px rgba(255,255,255, 0.859) , 93px -43px 0 -104px rgba(255,255,255, 0.649) , -15px -17px 0 -104px rgba(255,255,255, 0.093) , -121px -37px 0 -104px rgba(255,255,255, 0.858) , 91px 44px 0 -104px rgba(255,255,255, 0.151) , 20px 102px 0 -104px rgba(255,255,255, 0.747) , -17px -103px 0 -104px rgba(255,255,255, 0.866) , 63px -10px 0 -104px rgba(255,255,255, 0.542) , 119px 89px 0 -104px rgba(255,255,255, 0.872) , 140px 93px 0 -104px rgba(255,255,255, 0.021) , 26px 77px 0 -104px rgba(255,255,255, 0.239) , -138px -33px 0 -104px rgba(255,255,255, 0.997) , -75px -20px 0 -104px rgba(255,255,255, 0.083) , -12px 39px 0 -104px rgba(255,255,255, 0.674) , 26px 39px 0 -104px rgba(255,255,255, 0.378) , -102px 48px 0 -104px rgba(255,255,255, 0.212) , 140px 21px 0 -104px rgba(255,255,255, 0.741) , 141px 31px 0 -104px rgba(255,255,255, 0.126) , 19px -84px 0 -104px rgba(255,255,255, 0.634) , -120px -50px 0 -104px rgba(255,255,255, 0.904) , 108px 22px 0 -104px rgba(255,255,255, 0.647) , -4px -118px 0 -104px rgba(255,255,255, 0.076) , 110px 117px 0 -104px rgba(255,255,255, 0.925) , 60px -119px 0 -104px rgba(255,255,255, 0.81) , 110px 85px 0 -104px rgba(255,255,255, 0.95) , -69px -134px 0 -104px rgba(255,255,255, 0.181) , -43px -89px 0 -104px rgba(255,255,255, 0.168) , 112px 99px 0 -104px rgba(255,255,255, 0.275) , -61px 109px 0 -104px rgba(255,255,255, 0.703) , 28px 122px 0 -104px rgba(255,255,255, 0.118) , -96px 24px 0 -104px rgba(255,255,255, 0.958) , 128px -46px 0 -104px rgba(255,255,255, 0.66) , -93px -89px 0 -104px rgba(255,255,255, 0.287) , 130px 22px 0 -104px rgba(255,255,255, 0.693) , -119px 49px 0 -104px rgba(255,255,255, 0.422) , -3px 114px 0 -104px rgba(255,255,255, 0.492) , 117px -104px 0 -104px rgba(255,255,255, 0.67) , -138px 71px 0 -104px rgba(255,255,255, 0.83) , 89px -127px 0 -104px rgba(255,255,255, 0.779) , 64px -30px 0 -104px rgba(255,255,255, 0.213) , -117px 25px 0 -104px rgba(255,255,255, 0.85) , 87px 119px 0 -104px rgba(255,255,255, 0.755) , -24px 33px 0 -104px rgba(255,255,255, 0.212) , 12px 70px 0 -104px rgba(255,255,255, 0.638) , -121px -40px 0 -104px rgba(255,255,255, 0.102) , 8px 105px 0 -104px rgba(255,255,255, 0.131) , -50px 23px 0 -104px rgba(255,255,255, 0.126) , 84px -124px 0 -104px rgba(255,255,255, 0.284) , 58px -74px 0 -104px rgba(255,255,255, 0.187) , -100px -99px 0 -104px rgba(255,255,255, 0.633) , 92px -83px 0 -104px rgba(255,255,255, 0.325) , 143px 98px 0 -104px rgba(255,255,255, 0.863) , -95px -14px 0 -104px rgba(255,255,255, 0.947) , 34px 47px 0 -104px rgba(255,255,255, 0.344) , -143px -100px 0 -104px rgba(255,255,255, 0.722) , 123px 51px 0 -104px rgba(255,255,255, 0.512) , -22px 133px 0 -104px rgba(255,255,255, 0.516) , 43px -112px 0 -104px rgba(255,255,255, 0.007) , 140px 92px 0 -104px rgba(255,255,255, 0.71) , -115px 44px 0 -104px rgba(255,255,255, 0.62) , -26px -128px 0 -104px rgba(255,255,255, 0.618) , -21px -15px 0 -104px rgba(255,255,255, 0.217) , -138px 100px 0 -104px rgba(255,255,255, 0.966) , 77px -44px 0 -104px rgba(255,255,255, 0.402) , -44px 145px 0 -104px rgba(255,255,255, 0.539) , -3px -89px 0 -104px rgba(255,255,255, 0.999) , -49px -126px 0 -104px rgba(255,255,255, 0.349) , -69px 87px 0 -104px rgba(255,255,255, 0.64) , -105px 53px 0 -104px rgba(255,255,255, 0.625) , 108px -39px 0 -104px rgba(255,255,255, 0.091) , -43px -20px 0 -104px rgba(255,255,255, 0.57) , -129px 133px 0 -104px rgba(255,255,255, 0.997) , -95px 115px 0 -104px rgba(255,255,255, 0.773) , 130px -88px 0 -104px rgba(255,255,255, 0.28) , -36px -120px 0 -104px rgba(255,255,255, 0.625) , 144px 129px 0 -104px rgba(255,255,255, 0.788) , 70px -45px 0 -104px rgba(255,255,255, 0.953) , 133px -135px 0 -104px rgba(255,255,255, 0.169) , 0px 107px 0 -104px rgba(255,255,255, 0.583) , 140px -23px 0 -104px rgba(255,255,255, 0.026) , -46px 98px 0 -104px rgba(255,255,255, 0.966) , -84px 129px 0 -104px rgba(255,255,255, 0.337) , 101px 133px 0 -104px rgba(255,255,255, 0.497) , 84px 80px 0 -104px rgba(255,255,255, 0.092) , -52px -134px 0 -104px rgba(255,255,255, 0.24) , 84px -2px 0 -104px rgba(255,255,255, 0.039) , 81px -15px 0 -104px rgba(255,255,255, 0.99) , 125px 124px 0 -104px rgba(255,255,255, 0.992) , 141px 131px 0 -104px rgba(255,255,255, 0.392) , 28px 15px 0 -104px rgba(255,255,255, 0.48) , 101px 55px 0 -104px rgba(255,255,255, 0.614) , 7px -95px 0 -104px rgba(255,255,255, 0.413) , -80px -140px 0 -104px rgba(255,255,255, 0.143) , -56px 12px 0 -104px rgba(255,255,255, 0.183) , 142px 41px 0 -104px rgba(255,255,255, 0.994) , 56px -125px 0 -104px rgba(255,255,255, 0.041) , 21px 114px 0 -104px rgba(255,255,255, 0.434) , 27px 129px 0 -104px rgba(255,255,255, 0.085) , -31px 85px 0 -104px rgba(255,255,255, 0.968) , 78px 35px 0 -104px rgba(255,255,255, 0.442) , -82px 120px 0 -104px rgba(255,255,255, 0.18) , -119px -52px 0 -104px rgba(255,255,255, 0.067) , -96px 6px 0 -104px rgba(255,255,255, 0.609) , 72px 56px 0 -104px rgba(255,255,255, 0.19) , 115px 40px 0 -104px rgba(255,255,255, 0.582) , -95px 124px 0 -104px rgba(255,255,255, 0.376) , -23px -133px 0 -104px rgba(255,255,255, 0.978) , 55px -2px 0 -104px rgba(255,255,255, 0.426) , 112px 103px 0 -104px rgba(255,255,255, 0.649) , -25px 35px 0 -104px rgba(255,255,255, 0.933) , -71px 65px 0 -104px rgba(255,255,255, 0.225) , -141px 76px 0 -104px rgba(255,255,255, 0.146) , 93px 77px 0 -104px rgba(255,255,255, 0.971) , 43px -27px 0 -104px rgba(255,255,255, 0.47) , 78px -123px 0 -104px rgba(255,255,255, 0.776) , -17px 53px 0 -104px rgba(255,255,255, 0.298) , 111px -3px 0 -104px rgba(255,255,255, 0.508) , -52px 5px 0 -104px rgba(255,255,255, 0.591) , 87px -53px 0 -104px rgba(255,255,255, 0.515) , -91px 69px 0 -104px rgba(255,255,255, 0.247) , -53px 48px 0 -104px rgba(255,255,255, 0.884) , 59px -42px 0 -104px rgba(255,255,255, 0.782) , 126px -30px 0 -104px rgba(255,255,255, 0.582) , -52px 43px 0 -104px rgba(255,255,255, 0.779) , -4px 119px 0 -104px rgba(255,255,255, 0.533) , 85px -112px 0 -104px rgba(255,255,255, 0.387) , -126px 140px 0 -104px rgba(255,255,255, 0.416) , 61px 22px 0 -104px rgba(255,255,255, 0.651) , 112px 95px 0 -104px rgba(255,255,255, 0.93) , 67px 111px 0 -104px rgba(255,255,255, 0.313) , -123px 95px 0 -104px rgba(255,255,255, 0.876) , 133px 29px 0 -104px rgba(255,255,255, 0.492) , 3px -54px 0 -104px rgba(255,255,255, 0.296) , -70px 35px 0 -104px rgba(255,255,255, 0.414) , 39px 143px 0 -104px rgba(255,255,255, 0.177) , 138px -98px 0 -104px rgba(255,255,255, 0.392) , 4px 37px 0 -104px rgba(255,255,255, 0.512) , -132px -32px 0 -104px rgba(255,255,255, 0.324) , 136px 23px 0 -104px rgba(255,255,255, 0.549) , -14px -110px 0 -104px rgba(255,255,255, 0.493) , -33px 137px 0 -104px rgba(255,255,255, 0.393) , 98px 22px 0 -104px rgba(255,255,255, 0.422) , 120px 116px 0 -104px rgba(255,255,255, 0.333) , 127px 83px 0 -104px rgba(255,255,255, 0.268) , 83px 90px 0 -104px rgba(255,255,255, 0.71) , -62px -49px 0 -104px rgba(255,255,255, 0.615) , -118px 143px 0 -104px rgba(255,255,255, 0.232) , 102px 8px 0 -104px rgba(255,255,255, 0.113) , 104px 80px 0 -104px rgba(255,255,255, 0.842) , -95px -126px 0 -104px rgba(255,255,255, 0.678) , 135px -121px 0 -104px rgba(255,255,255, 0.786) , 51px 1px 0 -104px rgba(255,255,255, 0.248) , 26px -124px 0 -104px rgba(255,255,255, 0.757) , 114px 34px 0 -104px rgba(255,255,255, 0.008) , 0px 95px 0 -104px rgba(255,255,255, 0.882) , -137px 114px 0 -104px rgba(255,255,255, 0.151) , -45px 61px 0 -104px rgba(255,255,255, 0.997) , 75px -47px 0 -104px rgba(255,255,255, 0.519) , 84px 54px 0 -104px rgba(255,255,255, 0.662) , -24px -105px 0 -104px rgba(255,255,255, 0.681) , -78px 64px 0 -104px rgba(255,255,255, 0.35) , 135px 12px 0 -104px rgba(255,255,255, 0.306) , -41px 38px 0 -104px rgba(255,255,255, 0.505) , 107px -15px 0 -104px rgba(255,255,255, 0.796) , 1px 68px 0 -104px rgba(255,255,255, 0.716);
}

.pluto {
  height: 780px;
  width: 780px;
  margin-top: -450px;
  margin-left: -320px;
  -webkit-animation: orb 7439.7074054575s linear infinite;
          animation: orb 7439.7074054575s linear infinite;
}
.pluto:before {
  height: 3px;
  width: 3px;
  background: #fff;
  margin-top: -1.5px;
  margin-left: -1.5px;
}

.hide {
  display: none;
}

.links {
  margin-top: 5px !important;
  font-size: 1em !important;
}

@-webkit-keyframes orb {
  from {
    transform: rotate(0deg);
  }
  to {
    transform: rotate(-360deg);
  }
}

@keyframes orb {
  from {
    transform: rotate(0deg);
  }
  to {
    transform: rotate(-360deg);
  }
}



CSS); ?>


    <?php $this->endBody() ?>
</body>

</html>
<?php $this->endPage() ?>