<?php

use yii\widgets\DetailView;
use app\modules\kadry\models\Award;

/** @var yii\web\View $this */
/** @var app\modules\kadry\models\Award $model  */

$isEditor = Yii::$app->user->can(Award::roleModerator())
    || Yii::$app->user->can('admin');

?>
<div class="comment-view">
   
    <?php if ($isEditor && $model->flag_dks): ?>
        <div class="alert alert-warning">
            Редактирование записи невозможно, т.к. запись была синхронизирована из ПК &laquo;ПК ДКС&raquo;!
        </div>
    <?php endif; ?>


    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'org_code',
            'org_name',
            'fio',
            'dep_name',
            'post',
            'aw_name',
            'aw_doc',
            'aw_doc_num',
            'aw_date_doc:date',
            'date_create:datetime',
            'date_update:datetime',
            'flag_dks:boolean:Запись из ПК &laquo;ПК ДКС&raquo;',
        ],
    ]) ?>

</div>
