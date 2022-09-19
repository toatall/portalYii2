<?php
/** @var yii\web\View $this */
/** @var array $links */
/** @var OP $model */
/** @var int $idSection */
/** @var array $opGroupModel */

use app\models\OP;
use yii\helpers\Url;
use app\helpers\DateHelper;
use yii\bootstrap5\Html;

$linksDocuments = array_filter($links, function ($val) {
    return $val['type_section'] == OP::SECTION_DOCUMENTS;
});
$linksArbitration = array_filter($links, function ($val) {
    return $val['type_section'] == OP::SECTION_ARBITRATION;
});
?>

<div class="row mt-3">        
    <?php if ($opGroupModel['view_documents_section']): ?>
    <div class="col-6">
        <div class="card">
            <div class="card-header">
                <?= OP::SECTION_DOCUMENTS_TITLE ?>
            </div>
            <div class="card-body">
                <?php if ($model->isEditor()): ?>
                    <a href="<?= Url::to(['op-create', 'idSection'=>$idSection, 'section'=>OP::SECTION_DOCUMENTS]) ?>" class="btn btn-primary">Добавить</a>
                    <hr />
                <?php endif; ?>
                <ul class="list-group">
                    <?php foreach ($linksDocuments as $link): ?>
                        <li class="list-group-item">
                            <div class="row">
                                <div class="col-10">
                                    <?php if (DateHelper::dateDiffDays($link['date_create']) <= 7): ?>
                                        <span class="badge badge-success">Новое</span>
                                    <?php endif; ?>
                                    <?= Html::a($link['name'], $link['file_name']) ?>
                                </div>
                                <div class="col-2">
                                    <?php if ($model->isEditor()): ?>
                                        <div class="btn-group">
                                            <?= Html::a('<i class="fas fa-edit"></i>', ['op-update', 'id'=>$link['id']], ['class'=>'btn btn-secondary', 'title'=>'Изменить']) ?>
                                            <?= Html::a('<i class="fas fa-trash"></i>', ['op-delete', 'id'=>$link['id']], [
                                                'data' => [
                                                    'method' => 'post',
                                                    'confirm' => 'Вы уверены, что хотите удалить?',
                                                ],
                                                'class' => 'btn btn-danger',
                                            ]) ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
    <?php endif; ?>
    
    <?php if ($opGroupModel['view_arbitration_section']): ?>
    <div class="col-6">
        <div class="card">
            <div class="card-header">
                <?= OP::SECTION_ARBITRATION_TITLE ?>
            </div>
            <div class="card-body">
                <?php if ($model->isEditor()): ?>
                    <?= Html::a('Добавить', ['op-create', 'idSection'=>$idSection, 'section'=>OP::SECTION_ARBITRATION], ['class'=>'btn btn-primary']) ?>
                    <hr />
                <?php endif; ?>
                <ul class="list-group">
                    <?php foreach ($linksArbitration as $link): ?>
                        <li class="list-group-item">
                            <div class="row">
                                <div class="col-10">
                                    <?php if (DateHelper::dateDiffDays($link['date_create']) <= 7): ?>
                                        <span class="badge badge-success">Новое</span>
                                    <?php endif; ?>
                                    <?= Html::a($link['name'], $link['file_name']) ?>
                                </div>
                                <div class="col-2">
                                    <?php if ($model->isEditor()): ?>
                                        <div class="btn-group">
                                            <?= Html::a('<i class="fas fa-edit"></i>', ['op-update', 'id'=>$link['id']], ['class'=>'btn btn-secondary', 'title'=>'Изменить']) ?>
                                            <?= Html::a('<i class="fas fa-trash"></i>', ['op-delete', 'id'=>$link['id']], [
                                                'data' => [
                                                    'method' => 'post',
                                                    'confirm' => 'Вы уверены, что хотите удалить?',
                                                ],
                                                'class' => 'btn btn-danger',
                                            ]) ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
    <?php endif; ?>            
</div>
