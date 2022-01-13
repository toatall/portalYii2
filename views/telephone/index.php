<?php 
/** @var \yii\web\View $this */
/** @var \yii\db\Query $organizations */
/** @var \yii\data\ActiveDataProvider $organizationDataProvider */
/** @var string $organizationUnid */
/** @var array $dateUpdate */

use yii\bootstrap4\Tabs;

$this->title = 'Телефонный справочник';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="col border-bottom mb-2">
    <p class="display-4">
        <?= $this->title ?>        
    </p>
    <kbd>Актуальность справочника: <?= Yii::$app->formatter->asDate($dateUpdate['date']) ?></kbd>   
</div> 

<div class="telephone-index">
    
    <?= Tabs::widget([
        'items' => [
            [
                'label' => 'Структура',
                'content' => $this->render('_tab1', [
                    'organizations' => $organizations,
                    'organizationDataProvider' => $organizationDataProvider,
                    'organizationUnid' => $organizationUnid,
                ]),
                'active' => true,
            ],
            [
                'label' => 'Поиск',
                'content' => $this->render('_tab2', [

                ]),
            ],
        ],
    ]) ?>

</div>