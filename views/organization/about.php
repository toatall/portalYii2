<?php 

/** @var yii\web\View $this */
/** @var app\models\Organization $model */

use app\models\Organization;
use dosamigos\gallery\Gallery;
use yii\bootstrap5\Html;

?>

<div class="card">
    <?php if (Organization::isRoleModerator($model->code)): ?>
        <div class="card-header">
            <div class="btn-group">
                <?= Html::a('<i class="fas fa-pencil-alt"></i> Правка', ['update', 'code'=>$model->code], ['class'=>'btn btn-outline-primary mv-link']) ?>
            </div>
        </div>
    <?php endif; ?>

    <div class="card-body">
        <div class="fa-1x">
            <?= $model->description ?>
        </div>
                

        <?php if ($model->getImages()): ?>
            <div class="card mt-4">
                <div class="card-header">
                    <button data-toggle="collapse" data-target="#collapse-image" class="btn btn-light btn-sm">
                        <i class="fa fa-minus" id="collapse-image-i"></i>
                    </button> Изображения
                </div>
                <div class="card-body" id="collapse-image">                
                    <?php 
                    $items = array();
                    foreach ($model->getImages() as $image)
                    {
                        $imageFile = \Yii::$app->storage->getFileUrl($image);
                        $items[] = [
                            'url' => $imageFile,
                            'src' => $imageFile,
                            'imageOptions' => [
                                'class' => 'img-thumbnail',
                                'style' => 'width:200px;',
                            ],
                        ];
                    }
                    // виджет галереи
                    echo Gallery::widget(['items' => $items]);
                    ?>
                </div>
            </div>      
        <?php endif; ?>

    </div>
</div>

<?php $this->registerJS(<<<JS
    
    // настройки collapse для изображений
    $('#collapse-image').collapse('show');
    $('#collapse-image').on('show.bs.collapse', function() { $('#collapse-image-i').attr('class', 'fa fa-minus'); });
    $('#collapse-image').on('hide.bs.collapse', function() { $('#collapse-image-i').attr('class', 'fa fa-plus'); });

    // для корректного отображения изображения из галереии при просмотре 
    $('#blueimp-gallery').prependTo($('body'));
    
JS
);
?>