<?php
namespace app\widgets;

use app\models\conference\AbstractConference;
use Yii;
use yii\bootstrap4\Alert;
use yii\db\Query;
use yii\bootstrap4\Html;

/**
 * Alert for approve a conference
 *
 * @author toatall
 */
class AlertConferenceApprove extends \yii\bootstrap\Widget
{
    
    /**
     * @var int
     */
    private $isApprove;
    
    /**
     * @var string
     */
    public $message = 'Требуется Ваше согласование заявки на проведение мероприятий (собрания, ВКС)';

    /**
     * @var string
     */
    public $url = ['/conference/request-approve'];


    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();
        $this->isApprove = (new Query())
            ->from('{{%conference}}')
            ->where(['status' => AbstractConference::STATUS_APPROVE])            
            ->exists();       
    }
    
    /**
     * {@inheritdoc}
     */
    public function run()
    {
        if ($this->isApprove) {
            $appendClass = isset($this->options['class']) ? ' ' . $this->options['class'] : '';

            $button = Html::a('Перейти', $this->url, ['class'=>'btn btn-outline-info']);
           
            $message = "<span class=\"far fa-bell fa-1x\"></span><strong> Внимание! $this->message</strong>&nbsp;&nbsp;&nbsp;$button";

            echo Alert::widget([
                'body' => $message,
                'closeButton' => false,
                'options' => array_merge($this->options, [
                    'id' => $this->getId() . '-' . 'alert-info',
                    'class' => 'alert-info ' . $appendClass,
                ]),
            ]);
        }
    }
}
