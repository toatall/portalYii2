<?php
/** @var yii\web\View $this */
/** @var app\models\rating\RatingMain $model */

use kartik\tabs\TabsX;
?>

<div class="mt-3">
<?php
$years = [];
foreach ($model->getYears() as $year) {
    $years[] = [
        'label' => '<i class="fas fa-calendar-day"></i> ' . $year['rating_year'],
        'content' => $this->render('_data', ['model' => $model->getRatingData($year)]),
    ];
}
if ($years) {
    $years[count($years)-1]['active'] = true;
}

?>
<?= TabsX::widget([
    'id' => 'years',
    'items' => $years,
    'position' => TabsX::POS_LEFT,   
    'encodeLabels' => false,
]) ?>
</div>