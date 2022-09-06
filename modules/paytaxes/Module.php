<?php

namespace app\modules\paytaxes;

/**
 * paytaxes module definition class
 */
class Module extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'app\modules\paytaxes\controllers';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();     
    }

    /**
     * Роль для модерирования
     * @return string
     */
    public static function roleEditor()
    {
        return 'pay-taxes.editor';
    }

}
