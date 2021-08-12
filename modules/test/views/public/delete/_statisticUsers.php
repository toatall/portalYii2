<?php
/* @var $this yii\web\View */
/* @var $model \app\modules\test\models\Test */

use yii\bootstrap\Html;
use yii\helpers\ArrayHelper;
use app\models\Organization;
use yii\helpers\Url;

?>
<div class="panel panel-default">
    <div class="panel-body">
        <div class="form-inline">
            <div class="form-group">
                <div class="input-group">
                    <?= Html::dropDownList('org_test_result', '', ArrayHelper::map(Organization::find()->all(), 'code', 'fullName'), [
                        'class'=>'form-control',
                        'id'=>'listOrganizations',
                    ]); ?>
                </div>
                <?= Html::button('Показать', [
                    'class'=>'btn btn-primary',
                    'id'=>'btnShow',
                ]) ?>
            </div>
        </div>
    </div>
</div>
<div id="container_tab2_result"></div>
<script type="text/javascript">

    $('#btnShow').on('click', function (){
        let url = '<?= Url::to(['/test/default/statistic-users', 'id'=>$model->id, 'org'=>'_org_']) ?>';
        url = url.replace('_org_', $('#listOrganizations').val());
        let cont = $('#container_tab2_result');
        cont.html('<i class="fas fa-spin fa-spinner"></i>');
        $.get(url)
        .done(function(data) {
            cont.html(data);
        })
        .fail(function (jqXHR) {
            cont.html('<div class="alert alert-danger">' + jqXHR.status + ' ' + jqXHR.statusText + '</div>');
        });
    });

</script>