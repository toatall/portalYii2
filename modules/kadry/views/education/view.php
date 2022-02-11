<?php

/** @var \yii\web\View $this */

use app\modules\kadry\widgets\EducationDetailView;
use yii\widgets\Pjax;

/** @var app\modules\kadry\models\education\Education $model */

$this->title = $model->title;

$this->params['breadcrumbs'][] = $this->title;

?>
<p class="display-4 border-bottom"><?= $this->title ?></p>

<?php Pjax::begin(['id' => 'pjax-education-view', 'timeout'=>false, 'enablePushState'=>false]); ?>

<?= EducationDetailView::widget([
    'model' => $model,
]) ?>

<?php $this->registerJs(<<<JS
    $('.link-download').on('click', function() {
        setTimeout(function() {
            $.pjax.reload({ container: '#pjax-education-view', async: true });
        }, 5000);
    });
JS); ?>

<?php Pjax::end(); ?>