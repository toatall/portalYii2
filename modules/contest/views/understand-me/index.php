<?php

use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var stdClass $data */

$this->title = 'Пойми меня';
?>

<div class="site-index pt-5">
    <div class="row">
    <?php       
        foreach($data->data as $item): ?>
        
        <div class="col-4 d-flex align-items-stretch mb-3">
            <a href="<?= Url::to(['/contest/understand-me/view', 'id'=>$item->id]) ?>" class="mv-link">
                <div class="card text-white h-100 shadow" style="background-color: darkgrey;">                
                    <img src="<?= $item->image ?>" class="card-img" />
                    <div class="card-img-overlay">
                        <div class="row align-items-end h-100 w-100">
                            <span class="font-weight-bolder fa-2x ml-4" style="text-shadow: 2px 2px #555;">    
                                <?= $item->title ?>
                            </span>
                        </div>
                    </div>                
                </div>
            </a>
        </div>        
    <?php endforeach; ?>
    </div>
</div>