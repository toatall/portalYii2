<?php
/** @var yii\web\View $this */
/** @var app\models\department\Department $modelDepartment */
/** @var app\models\OP $model */
/** @var array $data */

use kartik\tabs\TabsX;

$this->title = 'Отраслевые проекты';
$this->params['breadcrumbs'][] = ['label' => 'Отделы', 'url' => ['/department/index']];
$this->params['breadcrumbs'][] = ['label' => $modelDepartment->department_name, 'url' => ['view', 'id'=>$modelDepartment->id]];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="col border-bottom mb-2">
    <p class="display-4">
        <?= $this->title ?>
    </p>
</div>

<?php
    $items = [];
    $active = true;
    foreach ($data as $id=>$d) {
        $items[] = [
            'id' => $id,
            'label' => $d['title'],
            'content' => $this->render('op_links', [
                'links' => $d['data'],
                'opGroupModel' => $d['model'],
                'model' => $model,
                'idSection' => $id,
            ]),
            'active' => $active,
        ];
        $active = false;
    }
?>

<?= TabsX::widget([
    'items' => $items,
]) ?>
