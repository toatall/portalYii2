<?php

/** @var \yii\web\View $this */
/** @var arrray $listA */
/** @var arrray $listB */
/** @var array $result */
/** @var string $existingLinks */

use app\assets\FlipAsset;
use app\modules\contest\assets\QuestAsset;
use yii\bootstrap4\Html;
use yii\helpers\Url;

FlipAsset::register($this);

$this->title = 'Станция «Поисковая»';

$stopQuest = true;

$this->registerCssFile("@web/public/assets/contest/quest/vendor/fieldsLinker/fieldsLinker.css", [
    'depends' => [QuestAsset::class]
]);

$this->registerJsFile(
    '@web/public/assets/contest/quest/vendor/fieldsLinker/fieldsLinker.js', [
        'depends' => [QuestAsset::class],
]);

$jsListA = json_encode($listA);
$jsListB = json_encode($listB);

?>

<?= Html::a('<i class="fas fa-arrow-circle-left"></i>', ['/contest/quest'], [
    'style' => 'position: fixed; left:2rem; top: 45%; font-size: 4rem;',   
    'class' => 'text-secondary',
    'title' => 'Назад',
]) ?>


<div class="justify-content-center" style="z-index: 10;">

    <div class="row justify-content-center">
        <div class="col-8 text-center">
            <h3 class="text-muted mt-4">
                <?= $this->title ?>
                <br />
                <small>Установите соответствие между терминами и определениями</small>
            </h3>  
            <hr class="w-100" />
        </div>
    </div>

    <?php if ($result || $stopQuest): ?>
    <!--div class="row col-10 offset-1 card card-body mt-2 fa-3x bg-secondary">
        <div class="text-center text-white">
            Вы заработали <span class="badge badge-info"><?= $result['balls'] ?? 0 ?></span>
            <?php switch ($result['balls'] ?? 0) {
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
            <span style="font-size: 1rem;"">Вы проходили задание <?= isset($result['date_create']) ? Yii::$app->formatter->asDatetime($result['date_create']) : null ?></span>
        </div>
    </div-->
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

    <div>
        <div class="bonds w-75 mt-3" id="original" style="display:block; margin-left: auto; margin-right: auto;"></div>
        <div class="text-center mb-5">
            <?php if (!$result && !$stopQuest): ?>
            <hr />
            <div class="btn-group">
                <button class="btn btn-primary fieldLinkerSave">Сохранить</button>
                <button type="button" class="btn btn-warning eraseLink2">Очистить</button>
            </div>
            <?php endif; ?>
        </div>
        <br /><span id="output"></span>
        <div class="d-none d-xl-block" style="position: absolute; bottom: 35vh; left: 10vw; z-index: 200;">
            <img src="/public/assets/contest/quest/img/person1.png" style="height: 35vh;" />
        </div>

    </div>

</div>

<?php 
$url = Url::to('');
$disable = ($result != null || $stopQuest) ? 'disable' : 'enable';
$this->registerJS(<<<JS
            
    const listA = $jsListA;   
    const listB = $jsListB;
        
    var dataA = [];
    listA.forEach(function(val) {
        dataA.push(val.name);
    });

    var dataB = [];
    listB.forEach(function(val) {
        dataB.push(val.name);
    });
        
    var fieldLinks;
    var inputOri;
    
    inputOri = {
        "localization": {},
        "options": {
            "associationMode": "oneToOne", 
            "lineStyle": "square-ends",
            "displayMode": "original",
            "mobileClickIt": false,
            "whiteSpace": "normal"
        },
        "Lists": [
            {
                "name": "Термины",
                "list": dataA,
            },
            {
                "name": "Определения",
                "list": dataB,
                //"mandatories": dataB,							
            },                
        ],        
        'existingLinks': $existingLinks,
    };
    
    
    function saveResult(validate) {
        localStorage.removeItem('timer');
        var results = fieldLinks.fieldsLinker("getLinks");       
        
        var res = [];
        results.links.forEach(function(val) {
            const from = val.from;
            const to = val.to;
            var idA = listA.find(c => c.name == from);
            var idB = listB.find(c => c.name == to);
            res.push({ 
                'idA': idA.id, 
                'from': from,
                'idB': idB.id,
                'to': to,
            });
        });  

        $.ajax({
            url: '$url',
            data: {result: res},
            method: 'post'
        })
        .done(function(data) {             
            location.reload();
        });
    }            
    
    fieldLinks=$("#original").fieldsLinker("init",inputOri);   
    fieldLinks.fieldsLinker("$disable"); 
    $('.FL-main div[draggable="true"]').each(function() { $(this).attr('draggable', 'false'); });
    $('.FL-right li[data-original-title]').each(function() { $(this).attr('data-original-title', ''); });     

JS); 

if (!$result) {
    $this->registerJs(<<<JS
        
        $(".fieldLinkerSave").on("click",function(){
            saveResult(true);          
        });
        
        $('.eraseLink2').on('click', function() {
            fieldLinks=$("#original").fieldsLinker("init",inputOri);
            $('.FL-main div[draggable="true"]').each(function() { $(this).attr('draggable', 'false'); });
            $('.FL-right li[data-original-title]').each(function() { $(this).attr('data-original-title', ''); });
        });

        var timer = 5 * 60;
        
        if (localStorage.getItem('timerStep1') != null && localStorage.getItem('timerStep1') > 0) {
            timer = localStorage.getItem('timerStep1');
        }
        
        function setTime() {
            const tick = $('#countdown');  
            var d = new Date(null);                
            d.setSeconds(timer);
            tick.attr('data-value', d.toISOString().substring(14, 19));        
            localStorage.setItem('timerStep1', timer);
            
            if (timer <= 0) {
                stopTime();
                console.log('stop');
            }

            timer--;
        }
        var interval = setInterval(() => {
            setTime(); 
        }, 1000);
        
        function stopTime() {
            clearInterval(interval);
            saveResult(false);
        }
    JS);
}

$this->registerCss(<<<CSS

/* .tick-flip-panel {
    background-color: transparent;
} */

label{
    font-weight:300;
    display:inline-block;
}
	
label input[type='radio']{
    vertical-align: top;
}
	
label.active{
    font-weight:600;
    color: #2e44b9;
}
	
	
.bonds{
    min-width: 400px;
    width: 50%;
    min-height: 410px;
}
	 
.radio-zone{
    padding: 0px 8px 0px 8px;
    border-radius:2px;
    min-width:300px;
}
.presentation{
    line-height : 14px;
    font-size : 12px;
}

hr{
    margin-bottom : 10px;
    margin-top:10px;
}

.fieldLinkerSave {
    display:inline-block;
}
	
	
input[type=radio] {
	font-size: 11px;
	cursor: pointer;
}

input[type=checkbox]::before{
  content: "";
  display: inline-block;
  font-size: inherit;
  float:left;
  font-weight:bold;
  margin-left:0;
  margin-right:2px;
  border: 2px solid #337ab7;
  border-radius : 3px;
  padding : 5px;
  margin-top:0px;
  color:black;
  background: white;
}

input[type=checkbox].active::after {
    content: "";
    display: inline-block;
    font-size: inherit;
    float: left;
	transform: rotate(45deg);
    margin-left: 6px;
    margin-top:-13px;
	height: 10px;
	width:  5px;
    color: #337ab7;
	border-bottom: 2px solid #337ab7;
	border-right:  2px solid #337ab7;
}

div.action-check.off {
    color: silver;
}

div.action-check {
    color: black;
    cursor: pointer;
    font-size: 18px;
}

input[type=radio]:before {
	background: white;
	border: 2px solid #337ab7;
	border-radius: 50%;
	margin-top: -2px;
	margin-right: 6px;
	display: inline-block;
	vertical-align: middle;
	content: '';
	width: 14px;
	height: 14px;
}

input[type=radio]:checked:before {
    background: #337ab7;
    border-color: #337ab7;
    box-shadow: inset 0px 0px 0px 2px #fff;
}
.choices{
    display:inline-block;
    margin-right:40px;
}

.choices label{
    margin-left:4px;
    margin-right:4px;
    vertical-align:8%;
}

.choices label.group{
    font-weight:bold;
}

.nice-group{
    width:680px;
    line-height: 16px;
    padding:10px;
    border:1px solid #ccc;
    background-color:#fff;
    border-radius:5px;
}
.nice-group legend {
    color: #337ab7;
    width:120px;
    font-size:14px !important;
    border-bottom:none;
    
}

.fieldsLinker select {
    border: 1px #444 solid;
    border-radius: 4px;
    padding: 5px;
    color: #000;
    font-weight: bolder;
    text-align: center;
}
.fieldsLinker li {   
    border-radius: 0.25rem !important;
    border-color: #6c757d !important;
    border: 1px solid #dee2e6 !important;
    background-color: #6c757d !important;
    margin-top: 1px;
    margin-bottom: 1px;
} 
.fieldsLinker div {
    font-weight: normal;
    text-align: left;
    font-size: 14px;
}
.FL-left {
    width: 52% !important;
}
.FL-mid {
    width: 8% !important;
}
.FL-left > ul > li > div {
    width: 87% !important;
}

.fieldsLinker li[data-mandatory='true'] {
    background-color: #fcfcfc;
    background: linear-gradient(#ffffff, #e9e9e9);
    color: black;
}

CSS); 
?>
