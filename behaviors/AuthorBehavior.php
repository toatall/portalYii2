<?php
namespace app\behaviors;

use yii\base\Behavior;
use yii\db\ActiveRecord;
use app\models\User;

/**
 * AuthorBehavior
 *
 * @author toatall
 */
class AuthorBehavior extends Behavior
{    
    /**
     * Аттрибут `Автор`
     * @var string 
     */
    public $author_at = 'author';
    
    /**
     * {@inheritdoc}
     */
    public function events(): array 
    {
        return [
            ActiveRecord::EVENT_BEFORE_VALIDATE => 'getAuthor',
        ];
    }        
    
    /**
     * Получение имени пользователя
     */
    public function getAuthor()
    {   
        /** @var ActiveRecord $owner **/ 
        $owner = $this->owner;
        
        if ($owner->hasProperty($this->author_at))
        {
            $this->owner->{$this->author_at} = User::getUsername();
        }
    }
    
}