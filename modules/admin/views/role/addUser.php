<?php

use yii\bootstrap4\Html;
use app\models\User;
use kartik\grid\GridView;
use app\modules\admin\models\Role;
use yii\widgets\Pjax;

/** @var yii\web\View $this */
/** @var Role $model */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Выберите пользователя';
$this->params['breadcrumbs'][] = ['label' => 'Роли', 'url' => ['/admin/role/index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['/admin/role/admin', 'id'=>$model->name]];
$this->params['breadcrumbs'][] = $this->title;
?>

<h1 class="mv-hide"><?= Html::encode($this->title) ?></h1>

<?php Pjax::begin(['enablePushState'=>false]) ?>
<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],

        'id',
        'username',
        'username_windows',
        'current_organization',
        [
            'format'=>'raw',
            'value'=>function(User $m) use ($model) {
                return Html::a('Добавить', ['/admin/role/add-sub-user', 'id' => $model->name, 'userId' => $m->id], ['class' => 'btn btn-primary btn-select-user']);
            },
        ],
    ],
]); ?>
<?php if (\Yii::$app->request->isAjax): ?>
<script type="text/javascript">
    $('.btn-select-user').on('click', function () {
        $(modalViewer).on('onRequestJsonDone', function(event, data) {
            if (modalViewer.autoCloseModal(data)) {
                $.pjax.reload({container:'#pjax-user-container', async: false });
            };
            $(modalViewer).unbind('onRequestJsonDone');
        });
        modalViewer.openUrl($(this).attr('href'));
        return false;
    });
</script>
<?php endif; ?>
<?php Pjax::end() ?>
