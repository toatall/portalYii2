<?php 
/** @var \yii\web\View $this */

use yii\bootstrap5\Tabs;

/** @var array $organizations */
/** @var array $organizationDataProvider */
/** @var string $organizationUnid */
/** @var string $unidPerson */
/** @var string $organization */
/** @var yii\data\ActiveDataProvider $dataProvider */


$this->title = 'Телефонный справочник';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="col border-bottom mb-2">
    <p class="display-5">
        <?= $this->title ?>                
    </p> 
</div> 

<div class="telephone-index">
    <?= Tabs::widget([
        'encodeLabels' => false,
        'items' => [
            [
                'label' => 'Интерактивный телефонный справочник '
                    . '<button class="btn btn-info btn-sm" data-bs-toggle="collapse" data-bs-target="#source-info">'
                    . '<i class="fas fa-info-circle text-white"></i></button>',
                'content' => $this->render('_tab1', [
                    'organization' => $organization,
                    'organizationDataProvider' => $organizationDataProvider,
                    'organizationUnid' => $organizationUnid,
                    'unidPerson' => $unidPerson,
                ]),
            ],
            [
                'label' => 'Скачать телефонные справочники',
                'content' => $this->render('_tab2', [
                    'dataProvider' => $dataProvider,
                ]),
            ],
        ],
        'headerOptions' => [
            'class' => 'fw-bolder fs-4'
        ],
    ]) ?>


</div>