<?php

use yii\bootstrap4\Html;
use app\modules\admin\models\Role;
use kartik\grid\GridView;

/** @var yii\web\View $this */
/** @var Role $model */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Выберите роль';
$this->params['breadcrumbs'][] = ['label' => 'Роли', 'url' => ['/admin/role/index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['/admin/role/admin', 'id'=>$model->name]];
$this->params['breadcrumbs'][] = $this->title;
?>

<h1 class="mv-hide"><?= Html::encode($this->title) ?></h1>

<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],

        'name',
        'description',
        'rule_name',
        'created_at',
        [
            'format'=>'raw',
            'value'=>function(Role $m) use ($model) {
                return Html::a('Добавить', ['/admin/role/add-sub-role', 'id' => $model->name, 'roleId' => $m->name], ['class' => 'btn btn-primary btn-select-role']);
            },
        ],
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