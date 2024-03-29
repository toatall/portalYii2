<?php
namespace app\modules\like\widgets;

use app\modules\like\models\Like;
use Yii;
use yii\base\InvalidConfigException;
use yii\bootstrap5\Html;
use yii\bootstrap5\Widget;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\widgets\Pjax;

/**
 * Кнопка для лайка
 */
class LikeWidget extends Widget 
{
    /**
     * Уникальный идентификатор 
     * @var string
     */
    public $unique;

    /**
     * Опции кнопки лайка и просмотра лайкеров
     */
    public $btnLikeOptions = [];   

    /**
     * Опции группы кнопок
     */
    public $containerOptions = ['class' => 'btn-group'];

    /**
     * Роли, кто может просматривать детализацию по статистике
     * @var array
     */
    public $roleViewLikers = [];

    /**
     * Показывать кнопку для просмотра кто лайкал
     */
    public $showLikers = true;
    
    public $disabled = false;

    public $showZero = false;

    
    public $btnLikeText = 'Мне нравится';
    public $btnLikeIcon = '<i class="fas fa-thumbs-up"></i>';
    public $btnUnlikeText = 'Мне нравится';
    public $btnUnlikeIcon = '<i class="far fa-thumbs-up"></i>';

    /**
     * @var Like
     */
    private $_modelLike;

    /**
     * Идентификатор pjax
     * @var string
     */
    private $_pjaxId;

    /**
     * @var string
     */
    private $_url;

    /**
     * {@inheritDoc}
     */
    public function init()
    {
        parent::init();       
        
        if (empty($this->unique)) {
            throw new InvalidConfigException('Не указан идентификатор $unique');
        }
        $this->_modelLike = Like::findDefinitely($this->unique, $this->roleViewLikers);
        $this->_pjaxId = 'pjax-like-' . $this->unique . '-' . $this->getId();
    }

    /**
     * {@inheritDoc}
     */
    public function run()
    {
        $this->_url = Url::current();
        $this->registerJs();        
        Pjax::begin(['id' => $this->_pjaxId, 'enablePushState' => false, 'timeout' => false, 'clientOptions' => ['withoutLoader' => true]]);
        if (!$this->disabled) {            
                echo Html::beginForm('', 'post', ['data-pjax' => true]);
                    echo Html::hiddenInput('like', true);
                    echo Html::beginTag('div', $this->containerOptions);                    
                        echo $this->renderBtnLike();
                        echo $this->renderBtnLikers();
                    echo Html::endTag('div');
                echo Html::endForm();            
        }
        else {
            echo Html::beginTag('div', $this->containerOptions);                    
                echo $this->renderBtnLike();
                echo $this->renderBtnLikers();
            echo Html::endTag('div');
        }
        Pjax::end();    
    }

    /**
     * Регистрация js скрипта
     */
    private function registerJs()
    {
        $loader = ArrayHelper::getValue($this->options, 'loader.template', '<span class="spinner-border spinner-border-sm ms-2"></span>');
        $updateInterval = ArrayHelper::getValue($this->options, 'autoupdate.interval', 30000); // default 30 sec
        $useUpdateInterval = ArrayHelper::getValue($this->options, 'autoupdate.use', true);

        $scipt = <<<JS
            $('#{$this->_pjaxId}').on('pjax:send', function(event, response, options) {
                if (options.autoupdate) { // не показывать loader, если передан параметр withoutLoader = true
                    return false
                }
                const btn = $(this).find('button[type="submit"]')
                btn.append('$loader')
                btn.prop('disabled', true)

                
            })
            $('#{$this->_pjaxId}').on('pjax:complete', function() {
                // скрыть все tooltip              
                $('.tooltip').hide()
            })
        JS;

        if ($useUpdateInterval) {
            $scipt .= "\n" . <<<JS
                window['intervalFunction-{$this->unique}'] = setInterval(() => {
                    if (document.querySelector('#{$this->_pjaxId}')) {
                        $.pjax.reload({ 
                            container: '#{$this->_pjaxId}', 
                            url: '{$this->_url}', 
                            push: false, 
                            replace: false, 
                            withoutLoader: true, 
                            autoupdate: true, 
                            timeout: false,
                        })
                    }
                    else {
                        clearInterval(window['intervalFunction-{$this->unique}'])                        
                    }
                }, $updateInterval)
            JS;
        }
        $this->view->registerJs($scipt);
    }

    /**
     * Рендеринг кнопки лайка
     * 
     * @return string
     */
    private function renderBtnLike()
    {            
        if ((int)Yii::$app->request->post('like') === 1) {
            $like = $this->_modelLike->likeDataCurrentUser;
            $this->_modelLike->likeToggle($like);
            $this->_modelLike->refresh();
        }

        $count = $this->_modelLike->count;
        $isLiked = $this->_modelLike->likeDataCurrentUser !== null;

        if ($isLiked) {
            Html::addCssClass($this->btnLikeOptions, 'btn btn-primary');
            $btnText = ArrayHelper::getValue($this->options, 'btnlike.content.text::liked', $this->btnLikeText);
            $btnIcon = ArrayHelper::getValue($this->options, 'btnlike.contetn.icon::unlike', $this->btnLikeIcon);
        }
        else {
            Html::addCssClass($this->btnLikeOptions, 'btn btn-light border');
            $btnText = ArrayHelper::getValue($this->options, 'btnlike.content.text::unliked', $this->btnUnlikeText);
            $btnIcon = ArrayHelper::getValue($this->options, 'btnlike.contetn.icon::unlike', $this->btnUnlikeIcon);
        }
        if ($count > 0 | $this->showZero) {
            $btnText .= ' ' . Html::tag('span', $count, [
                'class' => ArrayHelper::getValue($this->options, 'btnlike.count.class', 'fw-bolder'),
            ]);
        }
        if ($this->disabled) {
            $this->btnLikeOptions = array_merge($this->btnLikeOptions, ['disabled' => 'disabled']);
        }
        
        return Html::submitButton($btnIcon . ' ' . $btnText, $this->btnLikeOptions);
    }

    /**
     * Рендеринг кнопки просмотра всех лайков
     * 
     * @return string
     */
    private function renderBtnLikers()
    {
        if ($this->showLikers) {
            $btnText = ArrayHelper::getValue($this->options, 'btnlikers.content.text', '<i class="fas fa-chart-area"></i>');
            $id = 'btn-view-likers-' . $this->unique;
            $this->view->registerJs(<<<JS
                
                $('#$id').tooltip({
                    trigger: 'hover',
                    title: 'Статистика',
                })
                modalViewerLikers = new ModalViewer({ modalWidth: '70%' })
                $('#$id').on('click', function(){
                    modalViewerLikers.showModal($(this).attr('href'))
                    return false
                })

            JS);
            return Html::a($btnText, ['/like/like/index', 'idLike' => $this->_modelLike->id], 
                array_merge($this->btnLikeOptions, ['id' => $id, 'data-bs-toggle' => 'tooltip']));
        }
        return '';
    }

}