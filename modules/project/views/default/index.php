<?php

/** @var \yii\web\View $this */

use yii\helpers\Url;

$this->title = 'Проекты';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="col border-bottom mb-2">
    <p class="display-4">
    <?= $this->title ?>
    </p>    
</div>

<div class="row">
    <div class="col-3">
        <div class="card">
            <div class="card-body text-center">
                <a href="<?= Url::to(['/project/thirty'])  ?>">30 лет ФНС России</a>
            </div>
        </div>
    </div>
</div>