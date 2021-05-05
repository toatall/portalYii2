<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\news\NewsSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="news-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'id_tree') ?>

    <?= $form->field($model, 'id_organization') ?>

    <?= $form->field($model, 'title') ?>

    <?= $form->field($model, 'message1') ?>

    <?php // echo $form->field($model, 'message2') ?>

    <?php // echo $form->field($model, 'author') ?>

    <?php // echo $form->field($model, 'general_page') ?>

    <?php // echo $form->field($model, 'date_start_pub') ?>

    <?php // echo $form->field($model, 'date_end_pub') ?>

    <?php // echo $form->field($model, 'flag_enable') ?>

    <?php // echo $form->field($model, 'thumbail_title') ?>

    <?php // echo $form->field($model, 'thumbail_image') ?>

    <?php // echo $form->field($model, 'thumbail_text') ?>

    <?php // echo $form->field($model, 'date_create') ?>

    <?php // echo $form->field($model, 'date_edit') ?>

    <?php // echo $form->field($model, 'date_delete') ?>

    <?php // echo $form->field($model, 'log_change') ?>

    <?php // echo $form->field($model, 'on_general_page') ?>

    <?php // echo $form->field($model, 'count_like') ?>

    <?php // echo $form->field($model, 'count_comment') ?>

    <?php // echo $form->field($model, 'count_visit') ?>

    <?php // echo $form->field($model, 'tags') ?>

    <?php // echo $form->field($model, 'date_sort') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
