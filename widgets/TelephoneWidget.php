<?php
namespace app\widgets;

use yii\bootstrap4\Html;

/** 
 * @author toatall
 */
class TelephoneWidget extends \yii\bootstrap\Widget
{
    /**
     * 
     */
    public $data;


    public function run()
    {
        $this->renderDataDepartment($this->data);
    }

    private function renderDataDepartment($item)
    {
        if (empty($item)) {
            return;
        }

        foreach ($item as $name => $itemData) {
            echo Html::beginTag('div', ['class' => 'card mb-2']);            
                echo Html::beginTag('div', ['class' => 'card-header']);     
                    echo $name;
                echo Html::endTag('div');
                echo Html::beginTag('div', ['class' => 'card-body']);    
                    $this->renderDataDepartment($itemData['department_sub']);
                    $this->renderDataUser($itemData['users']);
                echo Html::endTag('div');
            echo Html::endTag('div');
        }
    }
    
    private function renderDataUser($item)
    {
        if (empty($item)) {
            return;
        }
        echo '<table class="table">';
        foreach ($item as $name => $itemData) {
            echo '<tr>';
            echo '<td>' . $name . '</td>';
            echo '<td>' . $itemData['telephone'] . '<br />' . $itemData['telephone_dop'] . '</td>';
            echo '<td>' . $itemData['post']  . '</td>';
            //echo '<td>' . $itemData['mail']  . '</td>';
            echo '<td>' . Html::a($itemData['mail'], 'mailto:' . $itemData['mail']) . '</td>';
            echo '<td>' . $itemData['location']  . '</td>';
            echo '<tr>';
        }
        echo '</table>';
    }

}