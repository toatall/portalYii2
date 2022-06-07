<?php

/** @var yii\web\View $this */
/** @var array $links */
/** @var array $results */

$this->title = 'Главная страница конкурса';
?>

<div class="h-100">
    <div class="d-flex justify-content-center mt-5">                                    
        <div class="row" style="width: 60vw;">
            <?php foreach($links as $link): ?>
                <div class="col-4">
                    <a href="<?= $link['url'] ?>">
                        <img class="<?= $link['finish'] ? 'img-link-finish' : 'img-link' ?>" 
                            title="<?= $link['title'] ?>" 
                            data-toggle="tooltip" 
                            data-html="true"
                            src="/public/assets/contest/quest/img/Lovepik_com-450016080-isometric train station vector.png" 
                            style="height: 20vh;" 
                        />
                    </a>
                </div>
            <?php endforeach; ?> 
        </div>               
    </div>
    <!--div class="mt-5 text-center">
        <?php if ($results): 
            $balls = 0;
            foreach($results as $result) {
                $balls += $result['balls'];
            }    
        ?>
        <h1 class="text-muted font-weight-bolder title-main">
            Ваш счет: <span class="display-4"><?= $balls; ?></span>
            <?php switch ($balls % 10) {
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
        </h1>
        <?php endif; ?>
    </div-->
</div>
                
<div style="position: absolute; bottom: 3px; left: 25%; z-index: 2;">
    <img src="/public/assets/contest/quest/img/train_station.png" style="height: 17vh;" />
</div>

<div class="img-mountain" style="position: absolute; bottom: 4px; right: -6rem; z-index: 2;">
    <img src="/public/assets/contest/quest/img/mountain.png" style="height: 30vh;" />
</div>


<div style="z-index: 10; position: absolute; bottom: 4px; right: -6rem; left: -6rem; z-index: 1; width: 110%;">
    <img src="/public/assets/contest/quest/img/toy-train-png-31610.png" style="height: 8vh; margin-bottom: 0rem; position: relative;" class="img-train" />
</div>

<div style="z-index: 10; position: absolute; bottom: 0px; right: -6rem; left: -6rem; z-index: 1; width: 110%; border-bottom: 5px solid #525252; height: 5px;"></div>

    
<div class="borderы border-secondary rounded p-2 text-center " style="position: absolute; top: 12rem; left: 8rem; width: 10vw; filter: opacity(.5); font-size: .9rem; font-weight: bold; text-shadow: 1px -1px #eee;">
    На каждое задание дается 1 попытка!
</div>