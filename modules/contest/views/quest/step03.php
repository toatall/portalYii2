<?php

/** @var \yii\web\View $this */
/** @var array $text */
/** @var array|null $result */

use app\assets\FlipAsset;
use yii\bootstrap4\Html;

FlipAsset::register($this);

$this->title = 'Станция «Налоговая полиция»';
?>

<?= Html::a('<i class="fas fa-arrow-circle-left"></i>', ['/contest/quest'], [
    'style' => 'position: fixed; left:2rem; top: 45%; font-size: 4rem;',   
    'class' => 'text-secondary',
    'title' => 'Назад',
]) ?>


<div class="row justify-content-center">
    <div class="col-6 text-center">
        <h3 class="text-muted mt-4">
            <?= $this->title ?>           
        </h3>  
        <hr class="w-100" />
        <h5>
            Найдите ошибки в тексте<br />
            <small>(нажимайте на слова для их выделения)</small>
        </h5>
    </div>
</div>

<?php if ($result): ?>
<div class="row col-10 offset-1 card card-body mt-2 fa-3x bg-secondary">
    <div class="text-center text-white">
        Вы заработали <span class="badge badge-info"><?= $result['balls'] ?></span>
        <?php switch ($result['balls']) {
            case 1: 
                echo 'балл';
                break;
            case 2:
            case 3:
            case 4:
                echo 'балла';
                break;
            default: 
                echo 'баллов';
            } ?>        
    </div>
    <div class="text-center">
        <span style="font-size: 1rem;"">Вы проходили задание <?= Yii::$app->formatter->asDatetime($result['date_create']) ?></span>
    </div>
</div>
<?php else: ?>
<div class="row col-10 offset-1 align-content-center justify-content-center bg-secondary p-3 text-white rounded">      
    <div class="display-4">ОСТАЛОСЬ ВРЕМЕНИ</div>
    <div id="countdown" class="tick" data-value="--:--" style="font-size: 3rem;">
        <div data-layout="vertical">
            <span data-view="flip"></span>
        </div>
    </div>            
</div>
<?php endif; ?>


<div class="mb-5" style="z-index: 10; position: relative; margin-bottom: 5rem; font-size: 1.5rem">      
    <hr />
    <?php foreach($text as $id=>$t): ?>
    <p class="mark-text" data-id="<?= $id ?>"><?= $t['text'] ?></p>
    <?php endforeach; ?>
    
    <hr />
    <?php if (!$result): ?>
    <div class="mt-2 btn-group">
        <button id="btn-save" class="btn btn-primary">Сохранить</button>
        <?= Html::a('Очистить', '', ['class' => 'btn btn-warning']) ?>
    </div>
    <?php endif; ?>
</div>


<?php 
$isResult = $result ? 'true' : 'false';
$this->registerJs(<<<JS

var res = {
        'p1': [],
        'p2': [],
        'p3': [],
        'p4': [],
        'p5': [],
    };
var index = 0;

$('.mark-text').each(function() {
    let words = $(this).first().text().split( /\s+/ );
    let text = words.join("</span> <span>");
    let thisMarkText = $(this);
    $(this).first().html("<span>" + text + "</span>");

    $(this).find("span").each(function() {
        $(this).attr('data-id', index);
        index++;
    });

    if ($isResult == false) {

        $(this).find("span").on( "click", function() {

            const p = $(this).parent('p').data('id');

            res[p] = [];

            if ($(this).attr("is-selected") == undefined) {
                $(this).addClass('badge badge-danger');
                $(this).attr("is-selected", true);
            }
            else {
                $(this).removeAttr("is-selected");               
                $(this).removeClass('badge badge-danger');
            }

            //let res = [];
            thisMarkText.find('span[is-selected]').each(function() {
                res[p].push({ 'id': $(this).data('id'), 'text': $(this).text() });
            });                     
        });
    }
});

JS);


if (!$result) {
    $this->registerJs(<<<JS

        function save() {            
            $.ajax({
                method: 'post',
                data: res,
                async: false
            })
            .done(function() {
                location.reload();
            });
        }

        $('#btn-save').on('click', function() {
            if (!confirm('Вы уверены, что хотите завершить?')) {
                return false;
            }
            save();
        });

        var timer;
            
        if (localStorage.getItem('timerStep3') == null || localStorage.getItem('timerStep3') <= 0) {
            timer = 5 * 60;
        }
        else {
            timer = localStorage.getItem('timerStep3');
        }
        function setTime() {
            const tick = $('#countdown');  
            var d = new Date(null);                
            d.setSeconds(timer);
            tick.attr('data-value', d.toISOString().substring(14, 19));        
            localStorage.setItem('timerStep3', timer);
            
            if (timer <= 0) {                
                stopTime();              
            }

            timer--;
        }

        var interval = setInterval(() => {
            setTime(); 
        }, 1000);
        
        function stopTime() {            
            clearInterval(interval);
            save();
        }                
    JS);
}
else {
    $marked = json_encode(unserialize($result['data']));
    $this->registerJs(<<<JS
        
        marked = $marked;

        for (i=1; i<=5; i++) {
            if (Array.isArray(marked.post[i])) {
                marked.post[i].forEach(function(val) {
                    $('span[data-id="' + val.id + '"]').addClass('badge badge-danger');
                });
            }
        }
           
    
    JS);
}


$this->registerCss(<<<CSS
    .mark-text span {
        cursor: pointer;
        font-size: 1.5rem !important;      
        /* text-decoration: underline dotted;   */
    }
    .mark-text {
        text-align: justify;        
    }
CSS); 

?>