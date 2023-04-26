<?php
/** @var yii\web\View $this */

use app\assets\FlipNumbersAsset;
use app\models\DeclareCampaignUsn;
use yii\bootstrap5\Html;

FlipNumbersAsset::register($this);

$query = DeclareCampaignUsn::findWithLastDate();
if (isset($query['8600'])) {
    $percents = Yii::$app->formatter->format(
        ($query['8600']->count_np_provides_reliabe_declare + $query['8600']->count_np_provides_not_required) / $query['8600']->count_np, 
    ['percent', 0]);
}
?>
<div class="rounded border-light border float-right d-none d-xxl-block me-2" style="width: 17rem; height: 12rem; margin-top: 0.5rem; background-color: #3B5998;">    
    <div class="row">
        <div class="col text-right">            
            <span class="lead text-light" style="font-size: 0.7rem;">Декларационная компания <?= date('Y') ?> года по УСН </span>
            <hr class="my-1" style="margin: 0.10rem 0 !important;" />
        </div>        
    </div>
    <div class="row">
        <div class="col">
            <div class="text-center text-light">
                
                <?php $days = 10; ?>
                <span class="lead" style="font-weight: bolder; font-size: 0.80rem;">
                Налогоплательщики, представившие либо обоснованно не представившие, Уведомления за 1 квартал 2023 года                
                </span>
                
                <div class="tick my-1" data-credits="false" style="font-size: 1.5rem;" data-value="<?= $percents ?>">
                    <div data-layout="horizontal">
                        <span data-view="flip"></span>
                    </div>
                </div>                
                <div class="mt-1"><?= Html::a('Подробнее', ['/declare-campaign-usn'], ['class' => 'btn btn-outline-light btn-sm']) ?></div>
            </div>
        </div>                   
    </div>            
</div>
