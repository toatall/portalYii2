<?php

namespace app\modules\kadry\modules\beginner;

use Yii;

/**
 * Contest module definition class
 */
class Module extends \yii\base\Module
{    

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();        
        Yii::configure($this, ['params' => require __DIR__ . '/config/params.php']);       
        $this->setComponents([
            'thumbStore' => [
                'class' => dicr\file\LocalFileStore::class,
                'path' => '@webroot/files/test/thumb',
                'url' => '@web/files/test/thumb',
            ],
            'fileStore' => [
                'class' => dicr\file\LocalFileStore::class,
                'path' => '@webroot/files/test/file',
                'thumbFileConfig' => [
                    'store' => 'thumnStore',
                ],
            ],
        ]);
    }
    

}
