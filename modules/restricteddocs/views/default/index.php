<?php

/** @var yii\web\View $this */
/** @var array $listOrgsGeneral */
/** @var array $listOrgsOther */


use app\modules\restricteddocs\models\RestrictedDocs;
use kartik\select2\Select2;
use yii\bootstrap5\ButtonDropdown;
use yii\bootstrap5\Html;
use yii\helpers\Url;

$this->title = 'Информационный ресурс по предоставлению информации ограниченного доступа';
$roleEditor = Yii::$app->user->can('admin') || Yii::$app->user->can(RestrictedDocs::roleModerator());
?>

<p class="display-4 border-bottom">
    <?= $this->title ?>
</p>

<?php if ($roleEditor): ?>
<div class="text-end">
    <?= ButtonDropdown::widget([
        'label' => 'Управление',
        'encodeLabel' => false,
        'buttonOptions' => [
            'class' => 'btn btn-light',
        ],
        'dropdown' => [
            'encodeLabels' => false,
            'items' => [
                ['label' => '<i class="fas fa-tasks"></i> Управление организациями', 'url' => ['/restricteddocs/docs-orgs']],
            ],
        ],
    ]) ?>
</div>

<div>
    <?= Html::a('<i class="fas fa-tasks"></i> Управление организациями', 
        ['/restricteddocs/docs-orgs'], ['class' => 'btn btn-secondary btn-sm mv-link']) ?>
    <?= Html::a('<i class="fas fa-tasks"></i> Управление видами сведений', 
        ['/restricteddocs/docs-types'], ['class' => 'btn btn-secondary btn-sm mv-link']) ?>
</div>
<hr />
<?php endif; ?>


<!-- Шаг 1: Кто запашивает информацию? -->
<div id="step-1" class="card">
    <div class="card-header">
        <span class="fa-2x fw-bold">Кто запашивает информацию?</span>
    </div>
    <div class="card-body col">
        <ul id="list-general" class="list-group">
            <?php foreach($listOrgsGeneral as $item): ?>    
            <button class="list-group-item list-group-item-action select-org-general" data-text-result="<?= $item['text_result'] ?>">
                <?= $item['name'] ?>
            </button>                     
            <?php endforeach; ?>
            <button class="list-group-item list-group-item-action select-org-other">
                Другая организация    
            </button>    
        </ul>
        
        <ul id="list-other" class="list-group mt-2" style="display: none;">
            <?php foreach($listOrgsOther as $item): ?>           
            <li class="list-group-item">
                <input class="form-check-input me-1" value="<?= $item['id'] ?>" type="checkbox" id="org_<?= $item['id'] ?>" />
                <label class="form-check-label" for="org_<?= $item['id'] ?>"><?= $item['name'] ?></label>
            </li>        
            <?php endforeach; ?>
        </ul>            
    </div>
    <div class="card-footer">
        <button id="step-1-btn-next" class="btn btn-outline-primary mt-2">
            <i class="fas fa-arrow-circle-right"></i> Далее
        </button>    
    </div>
</div>
<div id="info-step-1-general" class="text-success mt-2 border border-success rounded p-2 px-4" style="display: none;">
    <p class="fw-bolder fa-2x" style="text-align: justify;"></p>
</div>
<!-- Шаг 1: Кто запашивает информацию?  -->


<!-- Шаг 2: Иформация является конфиденциальной?  -->
<div id="step-2" style="display: none;">
    <div class="mt-2 card">
        <div class="card-header">
            <span class="fa-2x fw-bold">Иформация является конфиденциальной?</span>
        </div>        
        <div class="card-footer">
            <button id="step-2-btn-yes" class="btn btn-outline-primary"><i class="fas fa-thumbs-up"></i> Да</button>
            <button id="step-2-btn-no" class="btn btn-outline-primary"><i class="fas fa-thumbs-down"></i> Нет</button>
        </div>
    </div>
</div>
<!-- Шаг 2: Иформация является конфиденциальной?  -->


<!-- Шаг 3: Запрос соответствует следующим требованиям приказа ФНС России  от 03.03.2003 № БГ-3-28/96? -->
<div id="step-3" style="display: none;" data-url="<?= Url::to(['get-list-types']) ?>">
    <div class="mt-2 card">
        <div class="card-header">
            <span class="fa-2x fw-bold">Запрос соответствует следующим требованиям приказа ФНС России  от 03.03.2003 № БГ-3-28/96?</span>
        </div>
        <div class="card-body">
            <ul class="text-justify">                
                <li>есть подпись должностного лица, имеющего право направлять запросы в налоговые органы?</li>
                <li>есть печать канцелярии отправителя?</li>
                <li>есть ссылка на закон, устанавливающее право пользователя на получение конфиденциальной информации?</li>
                <li>есть обоснование (мотив) запроса которого является конкретная цель, связанная с исполнением пользователем определенных федеральным законом обязанностей, для достижения которой ему необходимо использовать запрашиваемую конфиденциальную информацию (например, находящееся в производстве суда, правоохранительного органа дело с указанием его номера; проведение правоохранительным органом оперативно-розыскных мероприятий или проверки по поступившей в этот орган информации с указанием даты и номера документа, на основании которого проводится оперативно-розыскное мероприятие или проверка информации).</li>
            </ul>
        </div>
        <div class="card-footer">                  
            <button id="step-3-btn-yes" class="btn btn-outline-primary"><i class="fas fa-thumbs-up"></i> Да</button>
            <button id="step-3-btn-no" class="btn btn-outline-primary"><i class="fas fa-thumbs-down"></i> Нет</button>
        </div>
    </div>
</div>
<!-- Шаг 3: Запрос соответствует следующим требованиям приказа ФНС России  от 03.03.2003 № БГ-3-28/96? -->


<!-- Результаты по конфиденциальной информации -->
<div id="info-step-3-yes" class="text-success mt-2" style="display: none;">
    <p class="fw-bolder fa-2x"><i class="fas fa-check-circle"></i> Запрос подлежит исполнению</p>
</div>
<div id="info-step-3-no" class="text-success mt-2" style="display: none;">
    <p class="fw-bolder fa-2x"><i class="fas fa-check-circle"></i> Запрос подлежит исполнению <u>без предоставления конфиденциальной информации</u></p>
</div>
<!-- Результаты по конфиденциальной информации -->


<!-- Шаг 4: Какая информация запрашивается? -->
<div id="step-4" style="display: none;">
    <div class="mt-2 card">
        <div class="card-header">
            <span class="fa-2x fw-bold">Какая информация запрашивается?</span>
        </div>
        <div class="card-body">            
            <?= Select2::widget([
                'id' => 'step-4-types',
                'name' => 'types',
                // 'data' => $listTypes,
                'options' => [
                    'multiple' => true,
                    'placeholder' => 'Выберите один или несколько видов сведений',
                ],
                'pluginOptions' => [
                    'allowClear' => true,
                ],
            ]) ?>
        </div>
        <div class="card-footer">
            <button id="step-4-btn-next" class="btn btn-outline-primary mt-2" disabled="disabled">
                <i class="fas fa-arrow-circle-right"></i> Далее
            </button>    
        </div>
    </div>
</div>
<!-- Шаг 4: Какая информация запрашивается? -->


<!-- Шаг 5: Результаты -->
<div id="step-5" style="display: none;" data-url="<?= Url::to(['table']) ?>">
    <div class="mt-2 card">
        <div class="card-header">
            <span class="fa-2x fw-bold">Результаты</span>
        </div>
        <div class="card-body">            
            <span class="spinner-border"></span>
        </div>        
    </div>
</div>
<!-- Шаг 5: Результаты -->


<hr class="mt-3" />
<button id="btn-redo" class="btn btn-outline-secondary mt-1"><i class="fas fa-redo"></i> Начать заново</button>

<?php 
$this->registerJs(<<<JS
    
    // >>> global functions
    function loadAjax(selector, data, doneCallback) {
        let cont = $(selector);
        let url = cont.data('url');
        $.ajax({
            method: 'post',
            url: url,
            data: data
        })
        .done(function(data) {
            cont.find('.card-body').html(data);
            doneCallback(data);
        })
        .fail(function(err) {
            cont.find('.card-body').html('<span class="text-danger">' + err.responseText + '</span>');
        });
    }
    function scrollTo(selector) {
        $('html, body').animate({
            scrollTop: $(selector).offset().top
        }, 1000);
    }    
    function selectBtn(btn) {
        btn.removeClass('btn-outline-primary');
        btn.addClass('btn-primary');
    }
    $('#btn-redo').on('click', () => window.location.reload());
    // <<< global functions
    
    
    // >>> functions step 1
    function step1_bind_checked() {    
        let lstChk = $('#step-1 input[type="checkbox"]:checked');
        $('#step-1-btn-next').prop('disabled', (lstChk.length == 0));
    }
    $('#step-1 input[type="checkbox"]').on('change', () => step1_bind_checked());
    step1_bind_checked();
    $('#step-1-btn-next').on('click', function() {        
        $('#step-2').show();
        $('#step-1 input, #step-1 button').prop('disabled', true);
        scrollTo('#step-2');
        selectBtn($(this));
    });
    $('.select-org-general').on('click', function() {
        $('#info-step-1-general p').html($(this).data('text-result'));
        $('#info-step-1-general').show();
        $(this).addClass('active');
        $('#list-general button').prop('disabled', true);
        $('#step-1 input, #step-1 button').prop('disabled', true);
        scrollTo('#info-step-1-general');
    });
    $('.select-org-other').on('click', function() {       
        $(this).addClass('active');
        $('#list-general button').prop('disabled', true);
        // $('#list-other').show();       
        $('#list-other input[type="checkbox"]').prop('checked', true);
        step1_bind_checked();
    });
    // <<< functions step 1

    
    // >>> functions step 2
    $('#step-2-btn-yes').on('click', function() {
        $('#step-2 button').prop('disabled', true);
        $('#step-3').show();
        scrollTo('#step-3');
        selectBtn($(this));
    });
    $('#step-2-btn-no').on('click', function() {
        let arrOrgs = [];        
        $('#step-1 input[type="checkbox"]:checked').each(function() {
            arrOrgs.push($(this).val());
        });
        loadAjax('#step-3', { 'orgs': arrOrgs }, function(data) {
            if (Array.isArray(data)) {
                let sel = $('#step-4-types');
                sel.val(null).trigger('change');
                data.forEach(function(val) {
                    let opt = new Option(val.name, val.id, false, false);
                    sel.append(opt).trigger('change');
                });                
            }
            else {
                alert('Полученные данные не являются массивом!');
            }
        });

        $('#step-2 button').prop('disabled', true);
        selectBtn($(this));
        $('#step-4').show();
        scrollTo('#step-4');
    });
    // <<< functions step 2

    // >>> functions step 3
    $('#step-3-btn-yes').on('click', function() {
        $('#step-3 button').prop('disabled', true);
        $('#info-step-3-yes').show();
        scrollTo('#info-step-3-yes');
        selectBtn($(this));
    });
    $('#step-3-btn-no').on('click', function() {        
        $('#step-3 button').prop('disabled', true);
        $('#info-step-3-no').show();
        scrollTo('#info-step-3-no');
        selectBtn($(this));
    });
    // <<< functions step 3


    // >>> functions step 4
    $('#step-4-types').on('change', function() {
        $('#step-4-btn-next').prop('disabled', ($(this).val() == 0));
    });
    $('#step-4-btn-next').on('click', function() {
        let lstChk = $('#step-1 input[type="checkbox"]:checked');        
        $('#step-4 button, #step-4 select').prop('disabled', true);

        let arrOrgs = [];
        let arrTypes = [];
        $('#step-1 input[type="checkbox"]:checked').each(function() {
            arrOrgs.push($(this).val());
        });
        arrTypes = $('#step-4-types').val();        
        loadAjax('#step-5', { 'orgs': arrOrgs, 'types': arrTypes }, function() {});
        $('#step-5').show();
        scrollTo('#step-5');
        selectBtn($(this));
    });
    // >>> functions step 4

JS); ?>

