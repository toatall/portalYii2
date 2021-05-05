<?php
/* @var $this \yii\web\View */
/* @var $links array */
/* @var $model OP */
/* @var $idSection int */

use app\models\OP;
use yii\helpers\Url;
use app\helpers\DateHelper;
use yii\helpers\Html;

$linksDocuments = array_filter($links, function ($val) {
    return $val['type_section'] == OP::SECTION_DOCUMENTS;
});
$linksArbitration = array_filter($links, function ($val) {
    return $val['type_section'] == OP::SECTION_ARBITRATION;
});
?>

<div class="row" style="margin-top: 10px;">
    <div class="col-sm-12">
        <div class="col-sm-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <?= OP::SECTION_DOCUMENTS_TITLE ?>
                </div>
                <div class="panel-body">
                    <?php if ($model->isEditor()): ?>
                        <a href="<?= Url::to(['op-create', 'idSection'=>$idSection, 'section'=>OP::SECTION_DOCUMENTS]) ?>" class="btn btn-primary">Добавить</a>
                        <hr />
                    <?php endif; ?>
                    <ul class="list-group">
                        <?php foreach ($linksDocuments as $link): ?>
                            <li class="list-group-item">
                                <div class="row">
                                    <div class="col-sm-10">
                                        <?php if (DateHelper::dateDiffDays($link['date_create']) <= 7): ?>
                                            <span class="label label-success">Новое</span>
                                        <?php endif; ?>
                                        <?= Html::a($link['name'], $link['file_name']) ?>
                                    </div>
                                    <div class="col-sm-2">
                                        <?php if ($model->isEditor()): ?>
                                            <?= Html::a('<i class="fas fa-edit"></i>', ['op-update', 'id'=>$link['id']], ['class'=>'btn btn-default', 'title'=>'Изменить']) ?>
                                            <?= Html::a('<i class="fas fa-trash"></i>', ['op-delete', 'id'=>$link['id']], [
                                                'data' => [
                                                    'method' => 'post',
                                                    'confirm' => 'Вы уверены, что хотите удалить?',
                                                ],
                                                'class' => 'btn btn-danger',
                                            ]) ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <?= OP::SECTION_ARBITRATION_TITLE ?>
                </div>
                <div class="panel-body">
                    <?php if ($model->isEditor()): ?>
                        <?= Html::a('Добавить', ['op-create', 'idSection'=>$idSection, 'section'=>OP::SECTION_ARBITRATION], ['class'=>'btn btn-primary']) ?>
                        <hr />
                    <?php endif; ?>
                    <ul class="list-group">
                        <?php foreach ($linksArbitration as $link): ?>
                            <li class="list-group-item">
                                <div class="row">
                                    <div class="col-sm-10">
                                        <?php if (DateHelper::dateDiffDays($link['date_create']) <= 7): ?>
                                            <span class="label label-success">Новое</span>
                                        <?php endif; ?>
                                        <?= Html::a($link['name'], $link['file_name']) ?>
                                    </div>
                                    <div class="col-sm-2">
                                        <?php if ($model->isEditor()): ?>
                                            <?= Html::a('<i class="fas fa-edit"></i>', ['op-update', 'id'=>$link['id']], ['class'=>'btn btn-default', 'title'=>'Изменить']) ?>
                                            <?= Html::a('<i class="fas fa-trash"></i>', ['op-delete', 'id'=>$link['id']], [
                                                'data' => [
                                                    'method' => 'post',
                                                    'confirm' => 'Вы уверены, что хотите удалить?',
                                                ],
                                                'class' => 'btn btn-danger',
                                            ]) ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>



