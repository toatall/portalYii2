<?php
namespace app\widgets\FilesGallery;

use yii\bootstrap5\Html;

/**
 * Базовый виджет предпросмотра файлов 
 * 
 * @author toatall
 */
class BaseGalleryWidget extends \yii\bootstrap5\Widget
{
    /**
     * Заголовок виджета
     * @var string
     */
    public $containerTitle;        
    
    /**
     * Массив файлов (изображений) 
     * 
     * Может передаваться обыный массив имен
     * Пример: 
     *     $files = ['file1.docx', 'file2.pdf', 'file3.xlsx', ...]
     * 
     * Либо имена и миниатюры (для изображений)
     * Пример:
     *     $files = [
     *         ['path' => 'image1.jpg', 'thumb' => 'image1_thumb.jpg],
     *         ['path' => 'image2.jpg', 'thumb' => 'image2_thumb.jpg], 
     *         ...
     *     ]
     * @var array
     */
    public $files;
    
    /**
     * Разрешить удаления файлов
     * @var bool
     */
    public $allowDelete = false;
    
    /**
     * Действие удаления файлов
     * Для удаления требуется подключить DeleteFileAction 
     * в контроллере функции actions
     * allowDelete должно быть true
     * @var string|array
     */
    public $deleteAction = ['delete-files'];
    
    
    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();
        $this->deleteAction = \yii\helpers\Url::to($this->deleteAction);
    }
    
    /**
     * Уникальный идентификатор контейнера
     * @return string
     */
    protected function getIdConainer()
    {
        return 'widget_files_' . $this->id;
    }
    
    /**
     * Уникальный идентификатор файла (генерируется каждый раз новый!)
     * @return string
     */
    protected function getIdItem()
    {
        return uniqid($this->getId() . '_item_');
    }
    
    /**
     * Уникальный идентификатор для pjax
     * @return string
     */
    protected function getIdPjax()
    {
        return $this->getIdConainer() . '_pjax';
    }


    /**
     * Подготовка файлов для передачи в представление
     * @return array
     */
    protected function prepareFileItem($item)
    {        
        if (is_string($item)) {
            $item = [
                'basename' => basename($item),
                'path' => $item,
                'thumb' => $item,
            ];
        }
        else {
            if (!isset($item['basename'])) {
                $item['basename'] = basename($item['path']);
            }
        }
        return $item;
    }
    
    /**
     * {@inheritdoc}
     */
    public function run()
    {        
        if (!$this->checkFilesIsNull()) {
            return null;
        }
        \yii\widgets\Pjax::begin(['id' => $this->getIdPjax(), 'enablePushState' => false]);
        echo Html::beginTag('div', ['class' => 'card icon-addons', 'id' => $this->getIdConainer()]);
            if ($this->containerTitle) {
                echo Html::beginTag('div', ['class' => 'card-header']);
                    echo $this->containerTitle;
                echo Html::endTag('div');
            }
            echo $this->renderGallery($this->files);
            if ($this->allowDelete) {
                echo Html::beginTag('div', ['class' => 'card-footer', 'style' => 'display: none;']);
                    echo Html::button('Удалить', ['class' => 'btn btn-danger btn-sm btn-delete']);
                echo Html::endTag('div');             
            }
        echo Html::endTag('div');
        \yii\widgets\Pjax::end();
    }
    
    /**
     * Проверка переданы ли имена файлов
     * @return bool
     */
    protected function checkFilesIsNull()
    {
        if (!$this->files) {
            return false;
        }
        if (!is_array($this->files)) {
            $this->files = [$this->files];
        }
        return true;
    }
    
    /**
     * Рендеринг галлереи
     * @param array $files файлы
     * @return string
     */
    protected function renderGallery($files)
    {  
        $items = [];
        $items[] = Html::beginTag('div', ['class' => 'row my-4 px-4']);
        foreach($files as $file) {
            $items[] = $this->renderItem($file);
        }
        $items[] = Html::endTag('div');
        $items[] = $this->errorContainer();
        
        if ($this->allowDelete) {
            $this->registerJsDeleteFiles();
        }
        
        return implode(PHP_EOL, $items);
    }
    
    /**
     * Рендеринг файла
     * @param string $galleryItem
     * @return string
     */
    protected function renderItem($galleryItem)
    {
        $item = $this->prepareFileItem($galleryItem);
        $id = $this->getIdItem();
        $res = [];
        if ($this->allowDelete) {            
            $res[] = $this->renderCheckBoxDeleteFile($item['basename'], $item['path'], $id);
        }
        else {
            $res[] = Html::a($item['basename'], $item['path'], ['target' => '_blank', 'data-filename' => $item['basename']]) . Html::tag('br');
        }
        return implode(PHP_EOL, $res);
    }
    
    /**
     * Рендеринг флажка удаления файла
     * @param string $basename имя файла без пути
     * @param string $fullName полное имя файла
     * @param string $id идентификатор
     * @param string $inputName имя поля
     * @return string
     */
    protected function renderCheckBoxDeleteFile($basename, $fullName, $id, $addPreview = true, $inputName = 'files[]')
    {
        return Html::beginTag('div', ['class' => 'form-check'])
            . Html::checkbox($inputName, false, ['class' => 'form-check-input', 'value' => $fullName, 'id' => $id])
            . Html::label($basename, $id, ['class' => 'form-check-label', 'data-filename' => $basename])
            . ($addPreview ?     
                ' ' . Html::a('<i class="fas fa-external-link-alt"></i>', $fullName, ['target' => '_blank', 'alt' => 'Открыть файл']) : '')
            . Html::endTag('div');
    }

    /**
     * Html-контейнер для вывода ошибки
     * @return string
     */
    private function errorContainer()
    {
        return Html::beginTag('div', ['class' => 'alert alert-danger mx-3', 'style' => 'display: none;'])
             . Html::endTag('div');
    }
    
    /**
     * js-функция удаления файлов
     * @return void
     */
    private function registerJsDeleteFiles()
    {
        $idConainer = $this->getIdConainer();
        $view = $this->getView();
        $view->registerJs(<<<JS
            $('#$idConainer .form-check-input').on('click', function() {
                let checked = $('#$idConainer .form-check-input:checked');
                $('#$idConainer .card-footer').toggle(checked.length > 0);
            });          
            $('#$idConainer .btn-delete').on('click', function(){
                $('#$idConainer .alert-danger').html('');
                $('#$idConainer .alert-danger').hide();
                $.ajax({
                    method: 'post',
                    url: '$this->deleteAction',
                    data: $('#$idConainer .form-check-input:checked').serialize()
                })
                .done(function(data) {
                    $.pjax.reload({ container: '#{$this->getIdPjax()}'});
                })
                .fail(function(jqXHR) {
                    $('#$idConainer .alert-danger').show();
                    $('#$idConainer .alert-danger').html(jqXHR.status + ' ' + jqXHR.statusText + '<br />Url: $this->deleteAction');
                });
            });
        JS);    
    }
    
}
