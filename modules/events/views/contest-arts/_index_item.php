<?php
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $modelToday app\modules\events\models\ContestArts */

?>


<?php if (($message = $modelToday->isAllow()) == null): ?>
<div class="card" style="background-image: url('/img/24.png');">   
    <div class="card-heading">
        <span class=""><b>Ответить можно до <?= $modelToday->getDateEnsStr() ?></b></span>
    </div>
    <div class="card-body">        
        <?= Html::beginForm(Url::to(['/events/contest-arts/answer', 'id'=>$modelToday->id]), 'post', ['class' => 'form-send-answer']) ?>
        <div class="row">            
            <div class="col-5">
                <?= Html::textInput('image_name', '', ['placeholder' => 'Введите название картины', 'class' => 'form-control', 'required' => 'required']) ?>
            </div>
            <div class="col-5">
                <?= Html::textInput('image_author', '', ['placeholder' => 'Введите автора картины', 'class' => 'form-control', 'required' => 'required']) ?>
            </div>
            <div class="col-2">
                <?= Html::submitButton('Сохранить', ['class' => 'btn btn-light']) ?>
            </div>            
        </div>
        <?= Html::endForm() ?>
        <div class="col alert-div" style="margin-top: 10px;"></div>    
    </div>
</div>
<?php else: ?>
    <div class="card" style="background-image: url('/img/24.png');"> 
        <div class="card-body">
            <?= $message ?>
        </div>
    </div>
<?php endif; ?>