<?php

namespace app\modules\project\modules\thirty;

/**
 * [[project]] module definition class
 */
class Module extends \yii\base\Module
{   
    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();
        $this->setComponents([
            'dbThirty' => require __DIR__ . '/config/db.php',
        ]);
    }    
}
