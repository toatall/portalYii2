<?php
/** @var yii\web\View $this */
/** @var app\models\Tree $modelTree */
/** @var app\models\rating\RatingMain[] $modelsRatingMain */

use kartik\tabs\TabsX;

$this->title = $modelTree->name;
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="mt-2">

    <div class="col border-bottom mb-2">
        <p class="display-5">
        <?= $this->title ?>
        </p>    
    </div>    

    <?php

    $tabs = [];
    foreach ($modelsRatingMain as $ratingMain) {
        $tabs[] = [
            'label' => $ratingMain->name,
            'content' => $this->render('_years', ['model'=>$ratingMain]),            
            'headerOptions' => [
                'style' => 'font-size:28px; font-weight:200;',
            ],
        ];
    }

    echo TabsX::widget([
        'id' => 'data',        
        'items' => $tabs,
        'position' => TabsX::POS_ABOVE,
        'sideways' => true,
    ]);

    ?>
</div>
<?php 
$this->registerCss(<<<CSS
    .tab-content {
        width: 100%;
    }
    #years {
        width: 25%;
    }
CSS); 
?>