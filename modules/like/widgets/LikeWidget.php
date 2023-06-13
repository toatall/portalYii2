<?php
namespace app\modules\like\widgets;

use app\modules\like\models\Like;
use Yii;
use yii\base\InvalidConfigException;
use yii\bootstrap5\Html;
use yii\bootstrap5\Widget;
use yii\helpers\ArrayHelper;
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
     * {@inheritDoc}
     */
    public function init()
    {
        parent::init();       
        
        if (empty($this->unique)) {
            throw new InvalidConfigException('Не указан идентификатор $unique');
        }
        $this->_modelLike = Like::findDefinitely($this->unique, $this->roleViewLikers);
        $this->_pjaxId = 'pjax-like-' . $this->unique;
    }

    /**
     * {@inheritDoc}
     */
    public function run()
    {
        $this->registerJs();

        Pjax::begin(['id' => $this->_pjaxId, 'enablePushState' => false, 'timeout' => false, 'clientOptions' => ['withoutLoader' => true]]);
            echo Html::beginForm('', 'post', ['data-pjax' => true]);
                echo Html::hiddenInput('like', true);
                echo Html::beginTag('div', $this->containerOptions);                    
                    echo $this->renderBtnLike();
                    echo $this->renderBtnLikers();
                echo Html::endTag('div');
            echo Html::endForm();
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
                        $.pjax.reload({ container: '#{$this->_pjaxId}', withoutLoader: true, autoupdate: true, timeout: false })
                    }
                    else {
                        clearInterval(window['intervalFunction-{$this->unique}'])
                        // document.querySelector('#{$this->_pjaxId}').intervalFunction = null
                        console.log('not fount #{$this->_pjaxId}')
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
            $btnText = ArrayHelper::getValue($this->options, 'btnlike.content.text::liked', 'Мне нравится');
            $btnIcon = ArrayHelper::getValue($this->options, 'btnlike.contetn.icon::unlike', '<i class="fas fa-thumbs-up"></i>');
        }
        else {
            Html::addCssClass($this->btnLikeOptions, 'btn btn-light border');
            $btnText = ArrayHelper::getValue($this->options, 'btnlike.content.text::unliked', 'Мне нравится');
            $btnIcon = ArrayHelper::getValue($this->options, 'btnlike.contetn.icon::unlike', '<i class="far fa-thumbs-up"></i>');
        }
        if ($count > 0) {
            $btnText .= ' ' . Html::tag('span', $count, [
                'class' => ArrayHelper::getValue($this->options, 'btnlike.count.class', 'fw-bolder'),
            ]);
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
        if ($this->showLikers/* && $this->_modelLike->isViewLikers()*/) {                 
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
            // Html::addCssClass($this->btnLikeOptions, 'mv-link');
            return Html::a($btnText, ['/like/like/index', 'idLike' => $this->_modelLike->id], 
                array_merge($this->btnLikeOptions, ['id' => $id, 'data-bs-toggle' => 'tooltip']));
        }
        return '';
    }

}