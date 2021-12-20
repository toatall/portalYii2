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

$this->title = 'Телефонный справочник';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="col border-bottom mb-2">
    <p class="display-4">
    <?= $this->title ?>
    </p>    
</div> 

<div class="telephone-index">
    <?php Pjax::begin(['id'=>'pjax-telephone-index', 'timeout'=>false, 'enablePushState'=>false]) ?>
    <?= Html::beginForm('', 'get', ['id'=>'form-organization', 'data-pjax' => true]) ?>
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
    <?php Html::endForm() ?>
        
    <div class="mt-2">
    <?php if ($organizationDataProvider != null): ?>
        
        <?= TelephoneWidget::widget([
            'data' => $organizationDataProvider,
        ]) ?>

    <?php endif; ?>
    </div>
    
    <?php Pjax::end() ?>
</div>