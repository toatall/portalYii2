<?php
/* @var $this \yii\web\View */
/* @var $model ThirtyOldEmployee */

use app\models\thirty\ThirtyOldEmployee;

$this->title =  $model['fio_full'];
$this->params['breadcrumbs'][] = $this->title;

?>
<style type="text/css">

    p.description {
        font: 1.9em/1 Georgia;
        text-align: justify;
    }

    p.description:first-letter {
        float: left;
        margin-right: 5px;
        padding: 0 5px 5px 5px;
        background: #337ab7;
        border-radius: 10%;
        font: 2.5em/1 Georgia;
        text-align: center;
        color: white;
    }

</style>

<div class="row">
    <div class="col-sm-10 col-sm-offset-0">
        <img src="<?= $model['file_name'] ?>" class="thumbnail" style="width: 300px; float: left; margin: 0 20px 20px 20px;"  alt=""/>
        <p class="description"><?= $model['description'] ?></p>
        <hr />
    </div>
</div>
