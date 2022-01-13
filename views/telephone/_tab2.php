<?php 
/** @var \yii\web\View $this */

use yii\bootstrap4\Html;

?>
<div class="telephone-index-tab2">
    
    <div class="card">
        <div class="card-header">
            <?= Html::beginForm('/telephone/search', 'get', ['id'=>'form-search', 'data-pjax' => true]) ?>          
            <div class="row">                      
                <div class="col">
                    <?= Html::textInput('term', '', ['class' => 'form-control', 'placeholder' => 'По ФИО или номер телефона', 'minlength'=>3, 'required'=>true]) ?>
                </div>
                <div class="col-1">
                    <?= Html::submitButton('Поиск', ['class' => 'btn btn-primary']) ?>
                </div>            
            </div>
            <?= Html::endForm() ?>
        </div>
    </div>
<?php
    $this->registerJs(<<<JS
        $('#form-search').off('submit');
        $('#form-search').on('submit', function() {
            $('#div-result-search').html('<img src="/img/loader_fb.gif" style="height: 50px;">');
            var url = $(this).attr('action');
            var data = $(this).serialize();
            $.ajax({
                url: url,
                method: "get",
                data: data
            })
            .done(function(data) {
                $('#div-result-search').html(data);
            })
            .fail(function(err) {                
                $('#div-result-search').html('<div class="alert alert-danger">' + err.status + ': ' + err.statusText + '</div>');
            });
            return false;
        });
    
    JS);

?>

    <div class="mt-2" id="div-result-search"></div>

</div>