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
        
        <div class="col-4 d-flex align-items-stretch mb-5">
            <a href="<?= Url::to(['/contest/understand-me/view', 'id'=>$item->id]) ?>" class="mv-link">
                <div class="card text-white h-100" style="background: none; border-width: 0;">                
                    <img src="<?= $item->image ?>" class="card-img" />                   
                    <div class="card-img-overlay">
                        <div class="row align-items-end h-100 w-100">
                            <div class="col text-center">
                                <span class="font-weight-bolder fa-2x" style="text-shadow: 2px 2px #555, -1px -1px #888, 1px 3px #444, 3px 1px #777;">    
                                    <?= $item->title ?>
                                </span>
                            </div>
                            
                        </div>
                    </div>                
                </div>
            </a>
        </div>        
    <?php endforeach; ?>
    </div>
</div>