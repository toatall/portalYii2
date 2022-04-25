<?php

use yii\bootstrap4\Html;
use yii\bootstrap4\Tabs;


/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider1 */
/** @var app\models\ChangeLegislationSearch $searchModel1 */
/** @var yii\data\ActiveDataProvider $dataProvider2 */
/** @var app\models\ChangeLegislationSearch $searchModel2 */

$this->title = 'Изменение в законодательстве';
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="change-legislation-index">
    
    <!-- <h1 class="display-4 border-bottom"><?= Html::encode($this->title) ?></h1> -->

    <?= Tabs::widget([
        'items' => [
            [
                'label' => 'Изменения в законодательстве', 
                'content' => $this->render('tab', [
                    'dataProvider' => $dataProvider1,
                    'searchModel' => $searchModel1,
                    'urlCreate' => ['create'],
                ]),
                'headerOptions' => [
                    'class' => 'display-4',
                ],
            ],
            [
                'label' => 'Антикризисные меры', 
                'content' => $this->render('tab', [
                    'dataProvider' => $dataProvider2,
                    'searchModel' => $searchModel2,
                    'urlCreate' => ['create', 'isAntiCrisis' => true],
                ]),
                'headerOptions' => [
                    'class' => 'display-4',
                ],
            ],
        ],
    ]) ?>
</div>