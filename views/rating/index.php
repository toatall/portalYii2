<?php
/* @var $this yii\web\View */
/* @var $modelTree \app\models\Tree */
/* @var $modelsRatingMain \app\models\rating\RatingMain[] */

use kartik\tabs\TabsX;

$this->title = $modelTree->name;
$this->params['breadcrumbs'][] = $this->title;

?>
<style type="text/css">

    .thumbnails [class*="span"]:first-child {
        margin-left: 40px;
    }
    .thumb-rating {
        height: 100px;
        overflow: auto;
    }
    .bold ul li a {
        font-weight: bold;
    }
    .stab-content {
        padding-top: 40px;
        border: 1px solid #ddd;
        -webkit-border-radius: 4px;
        -moz-border-radius: 4px;
        border-radius: 4px;
        -webkit-box-shadow: 0 1px 3px rgba(0,0,0,0.055);
        -moz-box-shadow: 0 1px 3px rgba(0,0,0,0.055);
        box-shadow: 0 1px 3px rgba(0,0,0,0.055);
        -webkit-transition: all .2s ease-in-out;
        -moz-transition: all .2s ease-in-out;
        -o-transition: all .2s ease-in-out;
    }

</style>

<div style="margin-top:20px;">
    <?php

    $flagActive = true;
    $tabs = [];

    foreach ($modelsRatingMain as $ratingMain) {
        $tabs[] = [
            'label' => $ratingMain->name,
            'content' => $this->render('_years', ['model'=>$ratingMain]),
            'active' => $flagActive,
            'headerOptions' => [
                'style' => 'font-size:17px; font-weight:bold;'
            ],
        ];
        $flagActive=false;

        // !!!

    }

    echo TabsX::widget([
        'items' => $tabs,
        'position' => TabsX::POS_ABOVE,
    ]);

    ?>
</div>
