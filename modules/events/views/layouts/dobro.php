<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\helpers\Url;
use app\assets\ModalViewerAsset;
use app\assets\AppAsset;

AppAsset::register($this);
ModalViewerAsset::register($this);

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="/favicon.png" />
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>       
</head>
<body>
<?php $this->registerCss(<<<CSS
        
    .navbar .nav > li > a {
        padding-top: 15px;
        padding-bottom: 15px; 
    }
    
    .logo {
        margin: 0 auto; 
        width: 200px;
        animation-name: logo-animation;
        animation-duration: 2.1s;
        animation-iteration-count: infinite;        
    }   
    @keyframes logo-animation {
        0% {
            transform: scale(1, 1);
        }
        50% {
            transform: scale(1.1, 1.1);
        }
        100% {
            transform: scale(1, 1);
        }
    }
        
CSS
); ?>
        
    <?php $this->beginBody() ?>
    
    <nav class="navbar navbar-default navbar-fixed-top">
        <div class="container">
            <div class="navbar-header">
                
                <a class="navbar-brand" href="<?= Url::to(['/events/dobro']) ?>">Неделя добрых дел</a>
            </div>
        <div id="navbar" class="navbar-collapse collapse"> 
            <ul class="nav navbar-nav">
                <li><a href="<?= Url::to(['/events/dobro']) ?>">Главная</a></li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li><a target="_blank" rel="noopener" class="nav-link" href="<?= Url::to(['/site/index']) ?>"><i class="fa fa-share"></i> Вернуться на Портал</a></li>              
            </ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav>  
    

    <div class="container">
        
        <div class="row" style="margin-top: 100px;">  
            <div class="col-sm-2 col-sm-offset-2">
                <img src="/img/ven3pbfjaj8-22.jpg" class="logo" style="" />
            </div>
            <div class="col-sm-6">
                <h1 class="text-primary" style="font-weight: 900;">
                    <small class="text-success" style="font-weight: 900;">Конкурс</small><br />
                    НЕДЕЛЯ ДОБРЫХ ДЕЛ
                </h1>
            </div>                       
        </div>
        
        <hr />
        
        <?= $content ?>                     
    </div>

    <div class="footer">
        <div class="container-fluid">            
            <div class="col-lg-12 text-center">
                <i class="fa fa-copyright"></i> 
                УФНС России по Ханты-Мансийскому автономному округу - Югре, <?= date('Y') ?>
            </div>
        </div>
    </div>
    <?php $this->endBody() ?>
  </body>
</html>
<?php $this->endPage() ?>