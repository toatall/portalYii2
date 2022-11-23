<?php

/** @var \yii\web\View $this */
/** @var \yii\data\ActiveDataProvider $organizationDataProvider */
/** @var string $organizationUnid */
/** @var string $unidPerson */
/** @var string $organization */

use app\assets\JQueryUiAsset;
use app\widgets\TelephoneWidget;
use yii\helpers\Url;

JQueryUiAsset::register($this);
?>

<div class="telephone-index-tab1">
    
    <div class="row mt-2">
        <div class="col-12">
             <div class="input-group" id="searchForm">
                <div class="input-group-prepend">
                    <div class="input-group-text">
                        Поиск
                    </div>
                </div>
                <input type="text" id="autocomplete" class="form-control" 
                    placeholder="по ФИО / номеру телефона / наименования или коду налогового органа" autocomplete="off">
                <div class="input-group-addon">
                    <div class="position-absolute" style="right: 10px; top: calc(50% - 10px); z-index: 999;">
                        <div id="autocomplete-spinner" style="display: none;">
                            <span class="spinner-border spinner-border-sm"
                                styles="right: 10px; top: calc(50% - 10px);"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 mt-2 collapse" id="source-info">
            <div class="alert alert-info">
                Данные используются из справочника "Справочник общий 86" Lotus Notes на сервере HUB86/R86/МНС
            </div>
        </div>
        <div class="col-12" id="div-error" style="display: none;"></div>
    </div>   
   
    <div class="mt-4">
        <?php if ($organization): ?>
            <h3><?= $organization['orgName'] ?></h3>
        <?php endif; ?>
        <?php if ($organizationDataProvider != null) : ?>
            <?= TelephoneWidget::widget([
                'data' => $organizationDataProvider,
                'selectUnid' => $unidPerson,
                'orgCode' => $organization['orgCode'] ?? null,
            ]) ?>          
        <?php endif; ?>
    </div>

</div>

<?php 
$url = Url::to(['find']);
$urlLocation = Url::to(['index', 'unidPerson' => '_unidPerson_', 'unidOrg' => '_unidOrg_']);

$this->registerJs(<<<JS

$(document).ready(function() {

    $('#form-organization').on('submit', () => { return false });
    
    $.widget("custom.catcomplete", $.ui.autocomplete, {
        _create: function() {
            this._super();
            this.widget().menu( "option", "items", "> :not(.ui-autocomplete-category)" );
        },
        _renderMenu: function(ul, items) {
            var that = this,
            currentCategory = "";

            if (items.length > 0 && items[0].value != 'not-found') {
                $.each(items, function( index, item) {
                    var li;
                    if ( item.category != currentCategory ) {                                     
                        ul.append(
                            $('<li class="lead fs-5 px-3 bg-secondary text-white">')
                            .append(item.category)
                            .attr('aria-label', 'category')
                        );
                        currentCategory = item.category;
                    }
                    li = that._renderItemData(ul, item);
                    if ( item.category ) {
                        li.attr( "aria-label", item.category + " : " + item.label );                    
                    };
                });    
            }
            else {
                var li;
                ul.append( $('<li class="lead fs-6 px-3">')
                    .append("Ничего не найдено")
                    .attr('aria-label', 'not-found') );                
            }
        },
        
        _renderItem: function(ul, item) {
            let li = $('<li class="border-bottom">');
            if (item.type == 'person') {
                let tel1 = phoneRegexp(item.tel1, this.term);
                if (tel1) {
                    tel1 = '<i class="fas fa-phone-alt"></i> ' + tel1;
                }
                let tel2 = phoneRegexp(item.tel2, this.term);
                if (tel2) {
                    tel2 = '<br /><i class="fas fa-phone-alt"></i> ' + tel2;
                }
                li                    
                    .append('<div class="row m-0 p-0">'
                        + '<div class="col-1"><img src="' + item.img + '" class="w-100 img-thumbnail" /></div>'
                        + '<div class="col">' + highlight(item.value, this.term)
                        + "<br />" + highlight(item.desc, this.term) 
                        + "<br />" + tel1 + tel2
                        + "</div></div>")                   
                    .appendTo( ul );
            }
            if (item.type == 'org') {
                li
                    .append("<div>" + highlight(item.value, this.term) + "<br />" + highlight(item.desc, this.term) + "</div>")
                    .appendTo( ul );
            }
            li.attr('data-value', item.value);
            return li;
        }
    });

    function phoneRegexp(phoneStr, search) {
        var phoneRegExp = new RegExp(search.split('').join('[()-]?'), 'g');
        var userPhone = phoneStr.match(phoneRegExp);
        var phone = phoneStr.replace(phoneRegExp, '<span class="founded">' + userPhone + '</span>');
        return phone;
    }

    function highlight(text, search) {
        var nameRegExp = new RegExp(search, 'i');
        var result = text.match(nameRegExp);
        if (result && result[0]) {
            return text.replace(nameRegExp, '<span class="founded">' + result + '</span>');
        }
        return text;
    }

    let a = $('#autocomplete').catcomplete({
        source: function(request, response) {
            $('#div-error').html('');
            $('#div-error').hide();

            $.get('$url', {
                term: request.term
            })
            .done(function(data) {
                response(data);
            })
            .fail(function(err) {
                console.log(err);
                $('#div-error').html('<div class="alert alert-danger">' + err.responseText + '</div>');
                $('#div-error').show();
            });
        },
        delay: 1000,
        select: function(ul, item) {
            if (item.item) {                
                let url = '$urlLocation';
                url = url
                    .replace('_unidOrg_', item.item.unidOrg)
                    .replace('_unidPerson_', item.item.unid)
                    + '#' + item.item.unid;
                window.location.href = url;
            }
        },
        search: function(event, ul) {
            $('#autocomplete-spinner').show();                   
        },
        response: function(event, ul) {            
            $('#autocomplete-spinner').hide();            
            if (ul.content.length == 0) {
                let resultNull = { value: "not-found", label: "Label", desc: "not-found desc", categiry: 'not-found' };
                ul.content.push(resultNull);
            }
        },
    });

});

JS); 

$this->registerCss(<<<CSS

    .founded {
        font-weight: bold;
        color: #029cca;
    }  
    .ui-state-active {
        margin: 0 !important;
    }

CSS);

?>