<?php

namespace app\modules\restricteddocs;

use Yii;

/**
 * Информационный ресурс по предоставлению информации ограниченного доступа
 * @author alexeevich
 */
class Module extends \yii\base\Module
{
    
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'app\modules\restricteddocs\controllers';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();
        // custom initialization code goes here        
    }

}
