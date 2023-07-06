<?php
/** @var yii\web\View $this */

use app\assets\FlipNumbersAsset;
use app\models\DeclareCampaignUsn;
use yii\bootstrap5\Html;

FlipNumbersAsset::register($this);

$query = array_reverse(array_slice(DeclareCampaignUsn::findWithLastDate(), -3));
?>
<div class="rounded border-light border float-right d-none d-xxl-block me-2" style="width: 17rem; height: 12rem; margin-top: 0.5rem; background-color: #3B5998;">    
    <div class="row">
        <div class="col text-right">            
            <span class="lead text-light" style="font-size: 0.7rem;">Декларационная кампания по УСН </span>
            <hr class="my-1" style="margin: 0.10rem 0 !important;" />
        </div>        
    </div>
    <div class="row">
        <div class="col">
            <div class="text-center text-light">
                
                <?php $days = 10; ?>
                <span class="lead" style="font-weight: bolder; font-size: 0.80rem;">
                    Налогоплательщики, представившие либо обоснованно не представившие, Уведомления
                </span>

                <div class="row border-top border-bottom mt-1 mb-2" style="font-size: .8rem">
                    <?php foreach($query as $date => $row): 
                        $percents = 0;
                        $date = Yii::$app->formatter->asDate($date);
                        
                        if (isset($row['8600'])) {
                            if ($row['8600']->count_np > 0) {
                                $percents = Yii::$app->formatter->format(
                                    ($row['8600']->count_np_provides_reliabe_declare + $row['8600']->count_np_provides_not_required) 
                                        / $row['8600']->count_np, 
                                ['percent', 0]);
                            }
                        }
                    ?>
                        <div class="col">
                            <span class=""><?= $date ?></span><br />
                            <span class="fw-bold"><?= $percents ?></span>
                        </div>
                    <?php endforeach; ?>

                </div>
                               
                <div class="mt-1"><?= Html::a('Подробнее', ['/declare-campaign-usn'], ['class' => 'btn btn-outline-light btn-sm']) ?></div>
            </div>
        </div>                   
    </div>            
</div>
