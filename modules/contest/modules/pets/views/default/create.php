<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\modules\contest\modules\pets\models\Pets $model */

$this->title = 'Добавление животного';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pets-create">

    <h1 class="display-5 border-bottom"><?= Html::encode($this->title) ?></h1>

    <div class="card card-body">
        <?= $this->render('_form', [
            'model' => $model,
        ]) ?>
    </div>

</div>
