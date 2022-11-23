<?php
namespace app\widgets;

use yii\bootstrap5\Html;
use yii\bootstrap5\Widget;
use app\assets\fancybox\FancyboxAsset;



/** 
 * @author toatall
 */
class TelephoneWidget extends Widget
{

    /**
     * Код организации
     * @var string
     */
    public $orgCode;

    /**
     * Массив данных
     * @var array
     */
    public $data;

    /**
     * Выделить цветом строку с unid
     * @var string
     */
    public $selectUnid;

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();
        FancyboxAsset::register($this->view);
        $this->view->registerJs(<<<JS
            $('.fancybox').fancybox({
                caption: function(instance, item) {
                    const caption = $(this).data('caption') || '';
                    const tel = $(this).data('telephones') || '';
                    const post = $(this).data('post');                  
                    return '<span class="lead">' + caption + '</span>'
                        + '<br />' + post
                        + '<br /><span class="lead">' + tel + '</span>';
                }
            });
        JS);             
    }

    
    /**
     * {@inheritdoc}
     */
    public function run()
    {       
        echo Html::beginTag('div', ['class' => 'card mt-2 mb-2']);
            
            echo Html::beginTag('div', ['class' => 'card-header']);
                echo Html::button('Показать структуру', ['class' => 'btn btn-primary', 'data-bs-toggle' => 'collapse', 'data-bs-target' => '#departments']);
            echo Html::endTag('div');        

            echo Html::beginTag('div', ['class' => 'card-body collapse', 'id' => 'departments']);
                echo Html::beginTag('div', ['class' => 'list-group']);
                echo $this->renderScruct($this->data);
                echo Html::endTag('div');       
            echo Html::endTag('div');       

        echo Html::endTag('div');     

        echo $this->renderItems($this->data);        
    }

    /**
     * ---------------------------------------------------------
     *                     < Обработка структуры >
     * ---------------------------------------------------------
     */

    /**
     * Вывод структуры
     * @param array $data массив данных
     * @param int $level уровень (используется для отступов)
     * @return string
     */
    private function renderScruct($data, $level=1)
    {
        $result = '';       
        if (is_array($data)) {
            if (!isset($data['type'])) {
                foreach ($data as $item) {
                    $row = $this->renderScructRow($item, $level);
                    if ($row != null) {              
                        $result .= $row;
                    }
                }
            }
            else {
                $result .= $this->renderScructRow($data, $level);
            }
        }
        return $result;        
    }

    /**
     * @param array $item 
     * @param int $level уровень (используется для отступов)
     * @return string
     */
    private function renderScructRow($item, $level)
    {
        $row = '';
        if (!isset($item['type']) || $item['type'] != 'dep') {
            return null;
        }       
        if (!isset($item['childs']) || !$item['childs']) {
            return null;
        }
        $row .= '<a href="#' . $item['unid'] . '" class="list-group-item list-group-item-action" style="padding-left: ' . $level .'rem;">' . $item['depName'] . '</a>';
        if (isset($item['childs'])) {
            $row .= $this->renderScruct($item['childs'], ($level+2));
        }
        return $row;
    }

    /**
     * ---------------------------------------------------------
     *                     < / Обработка структуры >
     * ---------------------------------------------------------
     */


    /**
     * ---------------------------------------------------------
     *                     < Обработка всех данных >
     * ---------------------------------------------------------
     */

    /**
     * Вывод даных об отделах, сотрудниках
     * @param array $data
     * @return string
     */
    private function renderItems($data)
    {
        $out = '';
        if (empty($data) && !is_array($data)) {
            return null;
        }

        if (!isset($data['type'])) {
            foreach ($data as $item) {
                if (!isset($item['type'])) {
                    continue;
                }
                $out .= $this->renderRow($item);
            }
        }
        else {
            $out .= $this->renderRow($data);
        }
        return $out;
    }

    /**
     * Вывод отдельного элемента структуры
     * @param array $item
     * @return string
     */
    private function renderRow($item)
    {
        $select = ($this->selectUnid == $item['unid']) ? ' alert-success rounded' : '';
        $out = '';        

        if ($item['type'] == 'dep') {
            if (isset($item['childs'])) {
                $out .= Html::beginTag('div', ['class' => 'card mb-2']);
                    $out .= Html::beginTag('div', ['class' => 'card-header fs-5']);
                        $out .= Html::tag('a', $item['depName'], ['name' => $item['unid']]);
                    $out .= Html::endTag('div');
                    $out .= Html::beginTag('div', ['class' => 'card-body']);                               
                        $out .=$this->renderItems($item['childs']);                                        
                    $out .= Html::endTag('div');
                $out .= Html::endTag('div');
            }
        }
        
        if ($item['type'] == 'person') {
            if (!empty($item['personTel1']) || !empty($item['personTel2'])) {           
                $img = $item['photo'];
                $out .= Html::beginTag('a', ['name'=>$item['unid']]);
                $out .= Html::endTag('a');
                $out .= Html::beginTag('div', ['class' => 'row border-bottom pt-2 pb-2 fs-6' . $select]);
                    $out .= '<div class="col-4 d-flex align-items-center" name="'.$item['unid'].'">'
                        . Html::a(Html::img($img, ['style' => 'width: 5rem;', 'class' => 'img-thumbnail me-3']), $img, [
                                'class' => 'fancybox',
                                'data-caption' => $item['personFullName'],
                                'data-post' => $item['personPost'],
                                'data-telephones' => $item['personTel2'] . '<br />' . $item['personTel1'],                                
                            ])
                        . Html::tag('span', $item['personFullName']) . '</div>';
                    $out .= '<div class="col-2 d-flex align-items-center"><i class="fas fa-phone"></i>&nbsp;<span>' 
                        . ($item['personTel2'] ? $item['personTel2'] . '<br />' : '') . $item['personTel1'] . '</span></div>';
                    $out .= '<div class="col-2 d-flex align-items-center">' . $item['personPost'] . '</div>';
                    $out .= '<div class="col-2 d-flex align-items-center">' . Html::a($item['personNotesName'], 'mailto:' . $item['personNotesName']) . '</div>';
                    $out .= '<div class="col-2 d-flex align-items-center">'
                        . ($item['personLocation'] ? '<i class="fas fa-door-open"></i>&nbsp;' . $item['personLocation'] : '')
                        . '</div>';
                $out .= Html::endTag('div');    
            }    
        }
        return $out;
    }

    /**
     * ---------------------------------------------------------
     *                     < / Обработка всех данных >
     * ---------------------------------------------------------
     */


}