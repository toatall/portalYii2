<?php
/** @var \yii\web\View $this */
/** @var bool $isViewLikers */
/** @var \yii\data\ActiveDataProvider|null $detailDataProvider */
/** @var array $groupDataByOrg */
/** @var array $groupDataByDate */
/** @var int $idLike */

use yii\bootstrap5\Tabs;
?>

<h2 class="mv-hide title">Статистика по лайкам</h2>

<?php
    $items = [
        [
            'label' => 'Статистика', 
            'content' => $this->render('_tabGroup', [
                'groupDataByOrg' => $groupDataByOrg,
                'groupDataByDate' => $groupDataByDate,
                'idLike' => $idLike,
            ]),
        ],        
    ];

    if ($isViewLikers) {
        $items[] = [
            'label' => 'Детализация',
            'content' => $this->render('_tabDetail', [
                'dataProvider' => $detailDataProvider,
            ]),
            'visible' => $isViewLikers,
        ];
    }

    echo Tabs::widget([
        'items' => $items,
    ]);
?>
