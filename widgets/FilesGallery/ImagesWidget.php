<?php
namespace app\widgets\FilesGallery;

use yii\bootstrap5\Html;

/**
 * Галлерея для изображений
 * 
 * Пример использования:
 *     app\widget\FilesGallery\ImagesWidget::widget([
 *         'containerTitle' => 'Изображения',
 *         'allowDelete' => true,
 *         'files' => [
 *             ['path' => 'path/images/img1.png', 'thumb' => 'path/images/img1_thumb.png'],
 *             ['path' => 'path/images/img2.png', 'thumb' => 'path/images/img2_thumb.png'],
 *             ['path' => 'path/images/img3.png', 'thumb' => 'path/images/img3_thumb.png'],
 *         ],
 *     ]);
 * 
 *     app\widget\FilesGallery\ImagesWidget::widget([
 *         'containerTitle' => 'Изображения',
 *         'allowDelete' => true,
 *         'files' => [
 *             'path/images/img1.png',
 *             'path/images/img2.png',
 *             'path/images/img3.png',
 *         ],
 *     ]);
 * 
 * @author toatall
 */
class ImagesWidget extends BaseGalleryWidget
{

    /**
     * Каталог с миниатюрой
     * @var string
     */
    public $thumbDir = '_thumb';
    
    /**
     * {@inheritDoc}
     */
    public function run() 
    {
        parent::run();
        $this->registerImageAsset();
    }
       
    /**
     * {@inheritdoc}
     * @param array $galleryItem
     * @return string
     */
    protected function renderItem($galleryItem) 
    {
        $item = $this->prepareFileItem($galleryItem);
        $id = $this->getIdItem();
        $res = [];
        $res[] = Html::beginTag('div', ['class' => 'col-2']);
        $res[] = Html::a(Html::img($this->getThumb($item['thumb']), ['class' => 'img-thumbnail']), $item['path'], ['target' => '_blank', 'data-fancybox' => 'gallery']);        
        if ($this->allowDelete) {
            $res[] = $this->renderCheckBoxDeleteFile($item['basename'], $item['path'], $id, false);
        }
        $res[] = Html::endTag('div');
        return implode(PHP_EOL, $res);
    }
    
    /**
     * Поиск миниатюры
     * @param string $fileImg
     * @return string
     */
    protected function getThumb($fileImg)
    {        
        /** @var \app\components\Storage $storage */
        $storage = \Yii::$app->storage;
        
        $newFileImg = str_replace(basename($fileImg), $storage->mergeUrl($this->thumbDir, basename($fileImg)), $fileImg);
        if (file_exists($storage->mergePath(\Yii::getAlias('@webroot'), $newFileImg))) {
            return $newFileImg;
        }
        return $fileImg;
    }
    
    /**
     * Регистрация Fancybox для предпросмотра изображения
     */
    protected function registerImageAsset()
    {
        $view = $this->getView();
        \app\assets\FancyappsUIAsset::register($view);

        $view->registerJs(<<<JS
            Fancybox.bind('#{$this->getIdConainer()} [data-fancybox]', {});
        JS);
    }
    
    
} 