<?php
namespace app\widgets;

use yii\bootstrap5\Html;
use yii\bootstrap5\Widget;

/** 
 * @author toatall
 */
class TelephoneWidget extends Widget
{
    /**
     * @var array
     */
    public $data;

    /**
     * Выделить unid
     * @var string
     */
    public $selectUnid;

    
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
     * 
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


    private function renderScructRow($item, $level)
    {
        $row = '';
        if (!isset($item['type'])) {
            return null;
        }
        if ($item['type'] != 'dep') {
            return null;
        }        
        $row .= '<a href="#' . $item['unid'] . '" class="list-group-item list-group-item-action" style="padding-left: ' . $level .'rem;">' . $item['depName'] . '</a>';
        if (isset($item['childs'])) {
            $row .= $this->renderScruct($item['childs'], ($level+2));
        }
        return $row;
    }


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

    private function renderRow($item)
    {
        $select = ($this->selectUnid == $item['unid']) ? ' alert-success rounded' : '';
        $out = '';        

        if ($item['type'] == 'dep') {
            if (isset($item['childs'])) {
                $out .= Html::beginTag('div', ['class' => 'card mb-2']);
                    $out .= Html::beginTag('div', ['class' => 'card-header']);
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
                $out .= Html::beginTag('div', ['class' => 'row border-bottom mb-2 pb-2' . $select]);
                    $out .=  '<div class="col-4"><a name="' . $item['unid'] . '"><i class="far fa-user"></i> ' . $item['personFullName'] . '</a></div>';
                    $out .= '<div class="col-2"><i class="fas fa-phone"></i> ' . $item['personTel2'] . '<br />' . $item['personTel1'] . '</div>';
                    $out .= '<div class="col-2">' . $item['personPost'] . '</div>';
                    $out .= '<div class="col-2">' . Html::a($item['personNotesName'], 'mailto:' . $item['personNotesName']) . '</div>';
                    $out .= '<div class="col-2"><i class="fas fa-door-open"></i> ' . $item['personLocation'] . '</div>';
                $out .= Html::endTag('div');      
            }    
        }
        return $out;
    }


}