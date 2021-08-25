<?php
/** @var yii\web\View $this */
/** @var app\models\department\Department $modelDepartment */
/** @var string $url */
/** @var array $breadcrumbs */

use yii\helpers\ArrayHelper;

$this->params['breadcrumbs'][] = ['label' => $modelDepartment->department_name, 'url' => ['view', 'id'=>$modelDepartment->id]];
$this->params['breadcrumbs'] = ArrayHelper::merge($this->params['breadcrumbs'], $breadcrumbs);

?>
<div id="container-ajax-department" data-ajax-url="<?= $url ?>"></div>
<?php $this->registerJS(<<<JS
             
     function runAjaxGetRequest(container) 
     {
        container.html('<img src="/img/loader_fb.gif" style="height: 100px;">');
        $.get(container.attr('data-ajax-url'))
        .done(function(data) {
            container.html(data);
        })
        .fail(function (jqXHR) {
            container.html('<div class="alert alert-danger">' + jqXHR.status + ' ' + jqXHR.statusText + '</div>');
        });    
    }

    runAjaxGetRequest($('#container-ajax-department')); 
    
JS
);
?>
