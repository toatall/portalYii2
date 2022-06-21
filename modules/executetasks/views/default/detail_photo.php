<?php

/** @var yii\web\View $this */
/** @var app\modules\executetasks\models\ExecuteTasksDescriptionDepartment $model */

?>

<div class="row col text-center">
    <div class="w-100">
        <?php if ($model): 
            $image = $model->image;    
        ?>
            
            <p class="lead text-white" style="border-bottom: 2px solid darkred;"><?= $model->fio ?></p>
            <?php if ($image && file_exists(Yii::getAlias('@webroot') . $image)): ?>
                <img src="<?= $model->getImage() ?>" class="img-thumbnail" />
            <?php else: ?>
                Фото нет
            <?php endif; ?>

            <div class="mt-3" style="font-size: 14px;">

                <p>
                    <span class="text-primary">Должность</span><br />
                    <?= $model->post ?>
                </p>

                <p>
                    <span class="text-primary">Чин</span><br />
                    <?= $model->rank ?>
                </p>

                <p>
                    <span class="text-primary">Телефон</span><br />
                    <?= $model->telephone ?>
                </p>

                <p>
                    <span class="text-primary">Описание</span><br />
                    <?= $model->description ?>
                </p>
                
            </div>
                
        <?php else: ?>
            Нет данных
        <?php endif; ?>
    </div>
</div>