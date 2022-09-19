<?php

/** @var yii\web\View $this */
/** @var string $content */

use app\modules\contest\assets\QuestAsset;
use yii\bootstrap5\Html;

QuestAsset::register($this);
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

    <main role="main" class="flex-shrink-0">
        <div class="container" style="padding-top: 4vh;">
            <div class="w-100 text-center">
                <h1 class="title-main font-weight-bolder display-4" style="z-index: 10; font-size: 6vh;">Корпоративный квест <br />«Налоговый калейдоскоп»</h1>

                <?php if (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false || strpos($_SERVER['HTTP_USER_AGENT'], 'Trident') !== false): ?>
                    <div class="alert alert-danger display-2 font-weight-bolder text-center" style="position: relative; z-index: 1000;">Браузер Internet Explorer не поддерживается!</div>
                <?php endif; ?>
                
            </div>

            <div class="theSun">
                <div class="ray_box">
                    <div class="ray ray1"></div>
                    <div class="ray ray2"></div>
                    <div class="ray ray3"></div>
                    <div class="ray ray4"></div>
                    <div class="ray ray5"></div>
                    <div class="ray ray6"></div>
                    <div class="ray ray7"></div>
                    <div class="ray ray8"></div>
                    <div class="ray ray9"></div>
                    <div class="ray ray10"></div>
                </div>
            </div>

            <div id="clouds">
                <div class="cloud x1"></div>
                <!-- Time for multiple clouds to dance around -->
                <div class="cloud x2"></div>
                <div class="cloud x3"></div>
                <div class="cloud x4"></div>
                <div class="cloud x5"></div>
            </div>
           
            <?= $content ?>            

        </div>        

    </main>


    <!-- <div classs="mt-auto">    
        
        <div style="position: relative; width: 100%;">
            
            <div style="position: absolute; bottom: -2px; left: 25%; z-index: 2;">
                <img src="/public/assets/contest/quest/img/train_station.png" style="height: 17vh;" />
            </div>

            <div class="img-mountain" style="position: absolute; bottom: 0px; right: -6rem; z-index: 2;">
                <img src="/public/assets/contest/quest/img/mountain.png" style="height: 30vh;" />
            </div>

            
            <div style="z-index: 10;">
                <img src="/public/assets/contest/quest/img/toy-train-png-31610.png" style="height: 8vh; margin-bottom: 0rem; position: relative;" class="img-train" />
            </div>

        </div>        
       
        
    </div> -->
   

    <!-- <footer class="footer py-3 h-auto" styles="bottom: 0px;">
        <div class="container">
            <span class="text-muted">УФНС России по Ханты-Мансийскому автономному округу - Югре, 2022</span>
        </div>
    </footer> -->

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
