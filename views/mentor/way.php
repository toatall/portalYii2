<?php
/* @var $this yii\web\View */
/* @var $dataProvider \yii\data\ActiveDataProvider */
/* @var $modelWay \app\models\mentor\MentorWays */

use yii\helpers\Html;
use yii\widgets\ListView;

$this->title = $modelWay->name;
$this->params['breadcrumbs'][] = ['label' => 'Наставничество', 'url' => ['/mentor/normative']];
$this->params['breadcrumbs'][] = $this->title;
?>

<h1 style="font-weight: bolder;"><?= $this->title ?></h1>
<hr />

<?php if (\app\models\mentor\MentorPost::isModerator()): ?>
<?= Html::a('Добавить пост', ['/mentor/create-post', 'way' => $modelWay->id], ['class'=>'btn btn-primary']); ?>
<hr />
<?php endif; ?>

<div class="row" style="margin-top: 20px;">
    <?= ListView::widget([
        'dataProvider' => $dataProvider,
        'itemView' => '_list',
        'layout' => "{items}\n{pager}",
    ]); ?>
</div>

<?php
$this->registerJs(<<<JS
    $('.delete-confirm a').on('click', function() {
        return confirm('Вы уверены, что хотите удалить?');
    });
JS
);
?>

