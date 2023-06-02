<?php
/** @var \yii\web\View $this */
/** @var \app\modules\meeting\models\Meeting $model */
/** @var \yii\bootstrap5\ActiveForm $form */

use app\modules\meeting\models\Locations;
?>

<?= $form->field($model, 'place')->dropdownList(Locations::listDropDown(), [            
    'placeholder' => 'Выберите кабинет',
    'class' => 'form-select',
]) ?> 