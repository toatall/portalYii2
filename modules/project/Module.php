<?php

namespace app\modules\project;

/**
 * [[thirty]] module definition class
 */
class Module extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();

        $this->setModules([
            'thirty' => [
                'class' => 'app\modules\project\modules\thirty\Module',
            ],            
        ]);
    }
}
