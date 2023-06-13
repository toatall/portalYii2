<?php
/** @var \yii\web\View $this */
/** @var \yii\data\ActiveDataProvider|null $detailDataProvider */

use kartik\grid\GridView;
use yii\widgets\Pjax;

?>

<?php Pjax::begin(['timeout' => false, 'enablePushState' => false]) ?>
    <?= GridView::widget([    
        'dataProvider' => $dataProvider,
        'columns' => [
            'usernameModel.default_organization:text:Код НО',
            'username:text:Учетная запись',
            'usernameModel.fio',
            'date_create:datetime:Дата',
        ],
        'toolbar' => [        
            '{export}',
            '{toggleData}',            
        ],
        'export' => [
            'showConfirmAlert' => false,
        ],
        'panel' => [
            'type' => GridView::TYPE_DEFAULT,       
        ],
    ]) ?>
<?php Pjax::end() ?>