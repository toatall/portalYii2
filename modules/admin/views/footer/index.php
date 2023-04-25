<?php

use kartik\widgets\Select2;
use yii\helpers\Url;
use yii\widgets\Pjax;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Подвал сайта';
$this->params['breadcrumbs'][] = $this->title;

?>

<?php $this->registerJs(<<<JS
    
    window.AjaxPortal = class AjaxPortal {
        
        constructor(element) {
            this.element = element
            this.url = element.data('url')
        }

        load(url) {
            const thisClass = this
            thisClass.element.html('<div class="fa-2x" style="color: Dodgerblue;"><i class="fas fa-circle-notch fa-spin"></i></div>')
            $.ajax({
                type: 'get',
                url: url ?? thisClass.url
            })
            .done(function (data) {
                thisClass.element.html(data)
            })
            .fail(function (jqXHR) {
                console.log(jqXHR)
                thisClass.element.html('<div class="alert alert-danger"><i class="fas fa-times-circle"></i> ' + jqXHR.responseText + '</div>')                
            })
        }
    }

    $('.data-ajax').each(function() {
        const ajaxPortal = new AjaxPortal($(this))
        $(this).data('ajax', ajaxPortal)
        ajaxPortal.load()
    })    

    $('#btn-footer-type-update').on('click', function() {
        $(this).parent('div').next('.data-ajax').data('ajax').load()
    })
    
JS) ?>

<div class="row">
    <div class="col-3">
        <div class="card">
            <div class="card-header">
                Разделы <button id="btn-footer-type-update" class="btn btn-light btn-sm"><i class="fas fa-refresh"></i></button>
            </div>
            <div id="div-footer-type" class="card-body data-ajax" data-url="<?= Url::to(['index-type']) ?>">                
            </div>
        </div>
    </div>
    <div class="col">
        <div class="card">
            <div class="card-header">
                Ссылки
            </div>
            <div class="card-body">
                
                <?php Pjax::begin(['id'=>'pjax-footer-index-data-select', 'timeout'=>false, 'enablePushState' => false]); ?>

                <?= Select2::widget([
                    'id' => 'select-type',
                    'name' => 'select-type',
                    'data' => $footerTypes,
                ]) ?>
                
                <?php $this->registerJs(<<<JS
                    
                    $('#div-ajax-footer-data').data('ajax', new AjaxPortal($('#div-ajax-footer-data')))

                    $('#select-type').on('change', function() {
                        $('#div-ajax-footer-data').data('ajax').load(UrlHelper.addParam($('#div-ajax-footer-data').data('url'), { idType: $(this).val() }))                        
                    })

                    $('#select-type').trigger('change')   
                                    
                    
                JS) ?>

                <?php Pjax::end() ?>
                
                <div id="div-ajax-footer-data" data-url="<?= Url::to(['/admin/footer-data/index']) ?>"></div>
            </div>                      
        </div>
    </div>
</div>

