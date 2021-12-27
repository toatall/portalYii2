<?php
/** @var yii\web\View $this */

$js = <<<JS
$('#form-statistic-users').submit();
JS;

$this->registerJs($js);
?>
<div class="alert alert-success">Результаты сохранены</div>