<?php
/** @var \yii\web\View $this */
/** @var \app\modules\meeting\models\search\VksFnsSearch $searchModel */

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;

?>

<?php $form = ActiveForm::begin([   
        'action' => ['index'],    
        'method' => 'get',
        'layout' => ActiveForm::LAYOUT_FLOATING,        
        'options' => ['data-pjax' => true, 'autocomplete' => 'off'],
    ]); ?>

    <?= $form->field($searchModel, 'between_days')->dropdownList([
        '3' => 'трех дней',
        '7' => 'недели',
        '14' => 'двух недель',
        '30' => 'месяца',
        '60' => 'двух месяцов',
        '90' => 'трех месяцов',
        '180' => 'полу года',
        '365' => 'года',
        '0' => 'Показать все мероприятия',
    ])->label('Показывать мероприятия, дата начала которых не менее и не более') ?>

    <?php 
    $idBetweenDays = Html::getInputId($searchModel, 'between_days');
    $this->registerJs(<<<JS
        $('#$idBetweenDays').on('change', function(){
            $(this).parents('form').submit()
        })
    JS);?>
        
<?php ActiveForm::end(); ?>