<?php 
/** @var \yii\web\View $this */
/** @var \yii\db\Query $organizations */
/** @var \yii\data\ActiveDataProvider $organizationDataProvider */
/** @var string $organizationUnid */

use app\widgets\TelephoneWidget;
use kartik\select2\Select2;
use yii\bootstrap4\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\Pjax;

?>

<div class="telephone-index-tab1">
    <?php Pjax::begin(['id'=>'pjax-telephone-index-tab1', 'timeout'=>false, 'enablePushState'=>false]) ?>
    
    <?= Html::beginForm('/telephone/index', 'get', ['id'=>'form-organization', 'data-pjax' => true]) ?>
    <div class="card">
        <div class="card-header">
            <?= Select2::widget([
                'name' => 'organizationUnid',
                'data' => ArrayHelper::map($organizations, 'unid', 'name'),
                'value' => $organizationUnid,
                'pluginOptions' => [
                    'placeholder' => 'Выберите налоговый орган',
                ],
                'id' => 'select_organization',
            ]) ?>
        </div>
    </div>
    
<?php 
$this->registerJs(<<<JS
    $('#select_organization').on('change', function() {
        $('#form-organization').submit();
    });
JS); ?>
    <?= Html::endForm() ?>
        
    <div class="mt-2">
    <?php if ($organizationDataProvider != null): ?>
        
        <?= TelephoneWidget::widget([
            'data' => $organizationDataProvider,
        ]) ?>

    <?php endif; ?>
    </div>
    
    <?php Pjax::end() ?>
</div>