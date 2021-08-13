<?php
/** @var yii\web\View $this */

use yii\bootstrap4\Html;

$this->title = 'Проекты новобранцев';
?>

<div class="row justify-content-md-center">
    <div class="col-2 text-center">
        <img src="/public/content/rookie/images/Creative_process_SVG.svg" style="height: 20em;" class="rounded" />
    </div>
    <div class="col-4">
        <h1 class="display-4 font-weight-bolder">Проекты новобранцев</h1>
        <p class="lead">УФНС России по Ханты-Мансийскому автономному округу - Югре</p>
    </div>
</div>
<hr />

<div class="container"> 

    <div class="card-deck mb-3 text-center row">   
        
        <div class="col-5">
            <div class="card mb-4 shadow-sm">
                <div class="card-header">
                    <h4 class="my-0">Конкурс "Неделя добрых дел"</h4>
                    <small>2021</small>                    
                </div>
                <img src="<?= Yii::getAlias('@content/rookie') ?>/images/project_04.jpg" class="card-img-top"  />         
                <div class="card-body">  
                    <p class="lead">Отдел информационных технологий</p>    
                    <hr />
                    <p>Кичатов Алексей Сергеевич</p>
                    <hr />
                    <?= Html::a('Перейти к проекту', ['/events/contest-arts'], [
                        'class' => 'btn btn-lg btn-block btn-outline-primary',
                        'target' => '_blank'
                    ]) ?>                
                </div>
            </div>  
        </div>

        <div class="col-5">
            <div class="card mb-4 shadow-sm">
                <div class="card-header">
                    <h4 class="my-0">Конкурс "Навстречу искусству"</h4>
                    <small>2021</small>
                </div>
                <img src="<?= Yii::getAlias('@content/rookie') ?>/images/project_03.jpg" class="card-img-top"  />         
                <div class="card-body">  
                    <p class="lead">Отдел информационных технологий</p>    
                    <hr />
                    <p>Варлаков Артем Андреевич</p>
                    <hr />
                    <?= Html::a('Перейти к проекту', ['/events/contest-arts'], [
                        'class' => 'btn btn-lg btn-block btn-outline-primary',
                        'target' => '_blank'
                    ]) ?>                
                </div>
            </div>  
        </div>
                   
        <div class="col-5">
            <div class="card mb-4 shadow-sm">
                <div class="card-header">
                    <h4 class="my-0">Конкурс "Видеооткрытка"</h4>
                    <small>2020</small>
                </div>
                <img src="<?= Yii::getAlias('@content/rookie') ?>/images/project_02.jpg" class="card-img-top"  />         
                <div class="card-body">  
                    <p class="lead">Отдел безопасности</p>    
                    <hr />
                    <p>Корнев Николай Николаевич</p>
                    <hr />
                    <?= Html::a('Перейти к проекту', ['/compliments'], [
                        'class' => 'btn btn-lg btn-block btn-outline-primary',
                        'target' => '_blank'
                    ]) ?>                
                </div>
            </div>
        </div>

        <div class="col-5">
            <div class="card mb-4 shadow-sm">
                <div class="card-header">
                    <h4 class="my-0">Проект "SUPER STAЖ"</h4>
                    <small>2020</small>
                </div>
                <img src="<?= Yii::getAlias('@content/rookie') ?>/images/project_01.jpg" class="card-img-top" />         
                <div class="card-body">  
                    <p class="lead">Отдел досудебного урегулирования налоговых споров</p>    
                    <hr />
                    <p>Кичатова Татьяна Евгеньевна</p>
                    <hr />
                    <?= Html::a('Перейти к проекту', ['/christmas-calendar'], [
                        'class' => 'btn btn-lg btn-block btn-outline-primary',
                        'target' => '_blank'
                    ]) ?>                
                </div>
            </div>        
        </div>
    </div>    

</div>    