<?php
/** @var array $query */
/** @var app\modules\test\models\Test $model */
/** @var yii\web\View $this */

use kartik\select2\Select2;
use yii\bootstrap4\Html;
use yii\helpers\ArrayHelper;

$this->title = 'Статистика по сотрудникам';
$this->params['breadcrumbs'][] = $model->name;
?>

<?= Html::beginForm(['/test/statistic/users-detail', 'id'=>$model->id], 'get', ['id' => 'form-statistic-users']) ?>
<div class="row">
    <div class="col-10">
    <?= Select2::widget([
        'name' => 'orgCode',
        'id' => 'select-org-code-statistic-users',
        'data' => ArrayHelper::map($query, 'code', 'name'),
    ])
    ?>
    </div>
    <div class="col-2">
        <?= Html::submitButton('Показать', ['class' => 'btn btn-primary']) ?>
    </div>
</div>
<?= Html::endForm() ?>
<div id="container-statistic-user-detail" class="mt-4"></div>

<?php $this->registerJs(<<<JS

    $('#form-statistic-users').on('submit', function() {
        const form = $(this);
        const action = form.attr('action');
        const cont = $('#container-statistic-user-detail');
        const loader = '<i class="fas fa-circle-notch fa-spin fa-2x"></i>';

        cont.html(loader);

        $.ajax({
            url: action,
            method: 'get',
            data: form.serialize()        
        })
        .done(function(resp) {
            cont.html(resp);
        })
        .fail(function(err) {            
            cont.html('<div class="alert alert-danger">' + err.responseText + '</div>');
        });;
        return false;
    });

JS); ?>
