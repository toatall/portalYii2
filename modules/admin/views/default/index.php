<?php
/** @var \yii\web\View $this */

use app\models\Tree;
use app\modules\admin\assets\JsTreeAsset;
use yii\bootstrap4\Html;

JsTreeAsset::register($this);

$this->title = 'Главная';
?>

<div class="admin-default-index">
    <h1>Добро пожаловать в систему управления</h1>


    <h3>Выберите, пожалуйуста, раздел сайта</h3>

    <div class="alert alert-info">
        Если вы впервые, посетите раздел <b><?= Html::a('Справка', ['/admin/default/help']) ?></b>
    </div>


    <div class="well" id="containerSection" style="background-color:white; margin-top:3px;">
        <div id="tree-view">
            <?= Tree::getTreeForMain() ?>
            <?php $this->registerJs("
                $('#tree-view').jstree({'core': { 'multiple': false }}).bind('select_node.jstree', function(e, data) {                   
                    let url = $('#' + data.selected).attr('data-url-view');                    
                    if (url != '#') {
                        document.location.href = url;
                    }
                    return false;
                });                
             ", \yii\web\View::POS_READY); ?>
        </div>
    </div>
</div>