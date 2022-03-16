<?php
/** @var \yii\web\View $this */
/** @var array $periodsAll */

use yii\bootstrap4\Html;
$this->title = 'Сведения о результатах итогового тестирования сотрудников Инспекций, прошедших курсы повышения квалификации (дистанционно)';
$this->params['breadcrumbs'][] = ['label'=>'Кадровые проекты', 'url' => ['/kadry']];
$this->params['breadcrumbs'][] = $this->title;
?>
<p class="display-4 border-bottom">
    <?= $this->title ?>
</p> 

<?php if ($periodsAll): ?>

    <?php foreach ($periodsAll as $year => $periods): ?>        
        <p class="display-3"><?= $year ?></p>
        <div class="row">
            <?php foreach ($periods as $preiodKey => $period): ?>
            <div class="col-2">
                <div class="card">
                    <div class="card-header lead text-center">
                        <?= $period ?>
                    </div>
                    <div class="card-body text-center">                    
                        <?= Html::a('Подробнее', ['view', 'year' => $year, 'period'=>$preiodKey], ['class' => 'btn btn-outline-primary mv-link mt-2']) ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <hr />
    <?php endforeach; ?>

<?php else: ?>
    <div class="alert alert-secondary">Нет данных</div>
<?php endif; ?>
