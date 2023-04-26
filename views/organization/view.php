<?php

use yii\bootstrap5\Html;
use yii\bootstrap5\Tabs;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var app\models\Organization $model */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Налоговые органы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="organization-view">

    <h1 class="display-5 border-bottom"><?= Html::encode($this->title) ?></h1>

    <div class="mt-3 pb-3">
        <?= Tabs::widget([
            'items' => [
                ['label'=>'Новости', 'content'=>'<div id="org_container_3" data-url="' . Url::to(['/news/index', 'organization'=>$model->code]) . '" class="container-autoload"></div>'],
                [
                    'label'=>'Историческая справка', 
                    'content'=>'<div id="org_container_1" data-url="' . Url::to(['/organization/about', 'code'=>$model->code]) . '" class="container-autoload"></div>',
                ],
                [
                    'label'=>'Структура', 
                    'content'=>'<div id="org_container_2" data-url="' . Url::to(['/department/index', 'org'=>$model->code]) . '" class="container-autoload"></div>',
                ],
            ],            
            'headerOptions' => ['class' => 'lead'],
            'encodeLabels' => false,
        ]) ?>
    </div>




</div>
<?php $this->registerJs(<<<JS
    
    document.updateContainer = function (container_id) {
        const div = $(container_id);
        const url = div.data('url');
        
        div.html('<img src="/img/loader_fb.gif" style="height: 100px;">');
        $.get(url)
        .done(function(data) {
            div.html(data);
        })
        .fail(function (jqXHR) {
            div.html('<div class="card card-body text-danger">' + jqXHR.status + ' ' + jqXHR.statusText + '</div>');
        }); 
    }

    $('.container-autoload').each(function() {      
        document.updateContainer('#' + $(this).attr('id'));   
    });


    // $(modalViewer).on('onRequestJsonDone', function(event, data) {
    //     if (data.content.toUpperCase() == 'OK') {
    //         document.updateContainer(data.updateId);
    //     }        
    // });

JS); ?>