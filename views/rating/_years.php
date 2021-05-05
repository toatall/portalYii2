<?php
/* @var $this \yii\web\View */
/* @var $model \app\models\rating\RatingMain */

use kartik\tabs\TabsX;
?>

<?php
$years = [];
foreach ($model->getYears() as $year) {
    $years[] = [
        'label' => $year['rating_year'],
        'content' => $this->render('_data', ['model' => $model->getRatingData($year)]),
    ];
} ?>
<?= TabsX::widget([
        'items' => $years,
        'position' => TabsX::POS_LEFT,
        'headerOptions' => [
            'style' => 'font-weight:bold;'
        ],
    ])
?>