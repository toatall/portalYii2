<?php
namespace app\modules\meeting\models\traits;

use app\models\User;
use Yii;

/**
 * @property User $authorModel
 */
trait RelationsTrait
{    
    
    public function getAuthorModel()
    {
        return $this->hasOne(User::class, ['username' => 'author']);
    }
}