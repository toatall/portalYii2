<?php

use yii\bootstrap5\Html;
use app\modules\admin\models\Role;
use kartik\grid\GridView;

/** @var yii\web\View $this */
/** @var Role $model */
/** @var yii\data\ActiveDataProvider $dataProvider */

?>

<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        ['class' => 'kartik\grid\SerialColumn'],
        'name',
        'description',
        'rule_name',
        'created_at:datetime',
        [
            'format'=>'raw',
            'value'=>function(Role $m) use ($model) {
                return Html::a('Добавить', ['/admin/role/add-sub-role', 'id' => $model->name, 'roleId' => $m->name], 
                ['class' => 'btn btn-primary btn-select-role']);
            },
        ],        
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
]); ?>
<?php if (\Yii::$app->request->isAjax): ?>
<script type="text/javascript">
    $('.btn-select-role').on('click', function () {
        $(modalViewer).on('onRequestJsonDone', function(event, data) {
            if (modalViewer.autoCloseModal(data)) {
                $.pjax.reload({container:'#pjax-role-container', async: false });
            }
            $(modalViewer).unbind('onRequestJsonDone');
        });
        modalViewer.openUrl($(this).attr('href'));
        return false;
    });
</script>
<?php endif; ?>