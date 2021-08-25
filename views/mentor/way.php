<?php
/** @var yii\web\View $this */
/** @var \yii\data\ActiveDataProvider $dataProvider */
/** @var \app\models\mentor\MentorWays $modelWay */

use app\models\mentor\MentorPost;
use yii\bootstrap4\Html;
use yii\bootstrap4\LinkPager;
use yii\widgets\ListView;

$this->title = $modelWay->name;
$this->params['breadcrumbs'][] = ['label' => 'Наставничество', 'url' => ['/mentor/normative']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col border-bottom mb-2">
        <p class="display-4">
        <?= $this->title ?>
        </p>    
    </div>    
</div>

<?php if (MentorPost::isModerator()): ?>
<?= Html::a('Добавить пост', ['/mentor/create-post', 'way' => $modelWay->id], ['class'=>'btn btn-primary']); ?>
<hr />
<?php endif; ?>

<div class="row mt-2">
    <?= ListView::widget([
        'dataProvider' => $dataProvider,
        'itemView' => '_list',
        'layout' => "{items}\n{pager}",
        'pager' => [
            'class' => LinkPager::class,
            'options' => [
                'class' => 'pt-2',
            ],
        ],
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

