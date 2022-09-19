<?php

/** @var yii\web\View $this */

use yii\bootstrap5\Html;
use yii\helpers\Url;

/** @var array $data */
/** @var array $dataSort */

$this->title = 'Игра посвященная дню кадрового работника';
?>
<style type="text/css">
    /* .img-thumbnail {
        height: initial !important;
    } */
</style>
<div class="site-index pt-5">
    <div class="alert alert-danger mt-3" id="alert-danger" style="display: none;"></div>                  
        <div class="col text-center" id="game-start" style="padding-top: 2em;">
            <p class="display-4 text-primary">Дню кадрового работника посвящается… </p>
            <strong class="text-success" style="font-size: larger;">Игра для сотрудников УФНС России по Ханты-Мансийскому автономному округу – Югре</strong>            
            <div class="text-center">
                <img src="/public/content/contest/images/logo.jpg" style="height: 30em;" />
            </div>
            <button class="btn btn-primary btn-lg mt-4" id="btn-play">Играть</button>
        </div>

        <div id="game-run" class="row" style="display: none;">
            <div class="col-5">
                <div class="card shadow-sm mb-2">
                    <div class="card-body" style="background: url('/public/content/contest/images/v4.gif') no-repeat; background-size: 100%;  height: 750px;  overflow: hidden;">
                        <div id="cont-faces" class="align-middle text-center" style="height: 100%;">
                            <img id="img-face" class="img-thumbnail" src="/public/images/face.jpg" style="height: 100%;" />
                        </div>
                        <div id="counter-temp" class="card badge badge-success" style="position: absolute; right: 10px; bottom: 10px; font-size: 1.4em; font-family: 'Courier New', Courier, monospace; opacity: 0;">                          
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-7" id="container-form">
                <div class="display-4 text-center mb-3 mt-2 border-bottom">Укажите температуру</div>
                <?= Html::beginForm(['/contest/default/result'], 'post', ['id' => 'form-result', 'class' => 'form-inline', 'style'=>'display: none;']) ?>  
                <?= Html::csrfMetaTags() ?>
                        
                <?= Html::endForm() ?>      
            </div>
        </div>

        <div class="game-result" style="display: none;"></div>    
</div>

<?php 
$urlData = Url::to(['/contest/default/data']);
$this->registerJs(<<<JS
    
    $('#btn-play').on('click', function() {                
        // start 
        startGame(); 
        $(this).prop('disabled', true); 
        $(this).append('<span class="spinner-border" role="status"></span>');     
    });

    function startGame() {
        $.get('$urlData')
        .done(function(data) {
            $('#game-run').show();
            $('#game-start').hide();
            //console.log(data);
            buildForm(data.dataSort);
            beginLogo();
            setTimeout(function() { animate(data.data, data.idResult); $('#form-result').show(); }, 4000);
        })
        .fail(function(err) {
            const alert = $('#alert-danger');
            alert.show();
            alert.html(err.responseText);
        })
        .always(function() {
            const btn = $('#btn-play');
            btn.prop('disabled', false);
            btn.children('span').remove();
        });
    }

    /** форма для заполнения информации о температуре */
    function buildForm(formData) {
        const form = $('#form-result');
        //form.html('');
        formData.forEach(function(element) {
            form.append(
                '<div class="col-6 mb-1">'
                    + '<div class="input-group">'
                        + '<div class="input-group-prepend" style="width:70%;">'
                            + '<div class="input-group-text" style="width:100%;">' + element.fio + '</div>'
                        + '</div>'
                        + '<input type="text" class="form-control" name="users[' + element.id + ']" placeholder="Температура">'
                    + '</div>'
                + '</div>');
        });
    }

    /** Смена изображений */
    function animate(data, idResult) {
        if (data.length == 0) {
            //alert('done');
            sendResult(idResult);
            return;
        }
        
        const cont = $('#cont-faces');   
        var item = data.shift();        
        cont.html('<img id="img-face" class="img-thumbnail" src="' + item.photo + '" style="height: 0;" />');
        $('#img-face').delay(1000).animate({height:'100%'},{duration:3000, complete: function() {
            //console.log(item);
            var timeout = (Math.random() * (1000 - 5) + 5) * 1000;
            //console.log('timeout: ' + timeout);
            setTimeout(function() { animate(data, idResult) }, timeout);
        }});
        $('#counter-temp').html('<i class="fas fa-thermometer-full"></i> ' + item.temp);
        $('#counter-temp').css('opacity', 0);
        $('#counter-temp').animate({opacity:1},{duration:5000});
    }
    
    /** Начальная заставка */
    function beginLogo() {
        const cont = $('#cont-faces');
        cont.html('<div id="back-counter" style="display: inline-block; font-size: 15rem; font-weight: 900; line-height: 1.2; margin: 0 auto; color: white;"></div>');
        var countDownIndex = 3;
        function countDown() {
            if (countDownIndex > 0) {    
                $('#back-counter').html(countDownIndex);
                countDownIndex--;
                setTimeout(function() { countDown() }, 1000);
            }
        }
        countDown();
    }

    function sendResult(idResult)
    {
        const form = $('#form-result');
        form.append('<input type="hidden" name="idResult" value="' + idResult + '" />');
        form.submit();
    }

JS); ?>
