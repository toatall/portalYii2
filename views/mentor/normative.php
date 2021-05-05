<?php
/* @var $this yii\web\View */
/* @var $models \app\models\mentor\MentorWays[] */

use yii\helpers\Html;

$this->title = 'Наставничество';
$this->params['breadcrumbs'][] = $this->title;
?>

<h1 style="font-weight: bolder;"><?= $this->title ?></h1>
<hr />

<?php foreach ($models as $model): ?>
    <p><?= Html::a($model->name . ' (' . count($model->mentorPosts) . ')', ['/mentor/way', 'id'=>$model->id]) ?></p>
<?php endforeach; ?>
