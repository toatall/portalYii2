<?php
/** @var yii\web\View $this */
/** @var array $departmentTree */
/** @var app\models\department\Department $model */

use app\assets\BstreeviewAsset;
BstreeviewAsset::register($this);
$jsonData = json_encode($departmentTree);

$this->params['breadcrumbs'][] = ['label' => $model->department_name, 'url' => ['view', 'id'=>$model->id]];
?>

<div class="news-index">

    <div class="row">
        <div class="col border-bottom mb-2">
            <p class="display-5">
            <?= $this->title ?>
            </p>    
        </div> 
    </div>

    <div class="row">
        <div class="col">            
            <div id="tree"></div>          
        </div>
    </div>
    
</div>
<?php $this->registerJs(<<<JS
    
    $('#tree').bstreeview({ 
        data: $jsonData,
        openNodeLinkOnNewTab: false
     });


JS); ?>