<?php
/* @var $this \yii\web\View */
/* @var $modelDepartment \app\models\department\Department */
/* @var $model \app\models\OP */
/* @var $data array */

use kartik\tabs\TabsX;

$this->title = 'Отраслевые проекты';
$this->params['breadcrumbs'][] = ['label' => $modelDepartment->department_name, 'url' => ['view', 'id'=>$modelDepartment->id]];
$this->params['breadcrumbs'][] = $this->title;
?>

<h1 style="font-weight: bolder;"><?= $this->title ?></h1>
<hr />

<?php
    $items = [];
    $active = true;
    foreach ($data as $id=>$d) {
        $items[] = [
            'id' => $id,
            'label' => $d['title'],
            'content' => $this->render('op_links', [
                'links' => $d['data'],
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
