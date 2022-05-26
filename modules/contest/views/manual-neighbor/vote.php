<?php 

/** @var yii\web\View $this */
/** @var yii\base\DynamicModel $model */

use app\modules\contest\models\ManualNeighbor;
use yii\bootstrap4\Html;
use yii\widgets\Pjax;
use yii\bootstrap4\ActiveForm;

?>

<h3>Номинации</h3>
<p>Каждый сотрудник Управления может проголосовать за три работы, выбрав одну лучшую в каждой из трех номинаций (голосовать за работу сотрудника своего отдела запрещено).</p>

<?php Pjax::begin(['timeout' => false, 'enablePushState' => false]) ?>       

    <?php $form = ActiveForm::begin([
        'action' => ['/contest/manual-neighbor/vote'],     
        'options' => [
            'data-pjax' => true,
        ],
    ]); ?>   

    <?= Html::errorSummary($model, ['class' => 'alert alert-danger']) ?>

    <div class="card">
        <div class="card-header">
            «Разберётся и ребенок» – оценивается доступность изложения материала
        </div>
        <div class="card-body">
            <?= $form->field($model, 'vote1')->radioList(ManualNeighbor::getItemsForRadio(), ['encode' => false])->label(false) ?>
        </div>
    </div>

    <div class="card mt-2">
        <div class="card-header">
            «Охват аудитории» – оценивается количество отделов, которым будет полезна информация
        </div>
        <div class="card-body">
            <?= $form->field($model, 'vote2')->radioList(ManualNeighbor::getItemsForRadio(), ['encode' => false])->label(false) ?>
        </div>
    </div>

    <div class="card mt-2">
        <div class="card-header">
            «Глаза разбегаются» – оценивается творческий подход к подготовке методических рекомендаций
        </div>
        <div class="card-body">
            <?= $form->field($model, 'vote3')->radioList(ManualNeighbor::getItemsForRadio(), ['encode' => false])->label(false) ?>
        </div>
    </div>

    <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary mt-2']) ?>
    
    <?php ActiveForm::end(); ?>

<?php Pjax::end() ?>