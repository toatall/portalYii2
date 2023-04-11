<?php

namespace app\modules\contest\modules\space\models;

use app\models\User;
use Yii;
use yii\db\Query;
use yii\helpers\FileHelper;

/**
 * This is the model class for table "{{%contest_space}}".
 *
 * @property int $id
 * @property string $org_code
 * @property string $title
 * @property int|null $date_create
 */
class Space extends \yii\db\ActiveRecord
{

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%contest_space}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['org_code', 'title'], 'required'],
            [['date_create'], 'integer'],
            [['org_code'], 'string', 'max' => 5],
            [['title'], 'string', 'max' => 300],
        ];
    }

    /**
     * Каталог с файлами
     * @return string
     */
    protected function getUploadPath()
    {
        $path = Yii::$app->params['modules']['contest']['space']['uploadPath'];
        return strtr($path, ['{id}' => $this->id]);
    }


    public function getFiles()
    {
        $files = [];
        $path = Yii::getAlias('@webroot') . $this->getUploadPath();
        if (is_dir($path)) {
            $files = FileHelper::findFiles($path, array_merge([
                'filter' => function(string $filename):bool {
                    $mimeType = \yii\helpers\FileHelper::getMimeType($filename);
                    return in_array(substr($mimeType, 0, 5), ['image', 'video']) || strpos($mimeType, 'pdf') !== false;
                },
            ]));
        }
        return array_map(function($value) {
            return [
                'file' => $this->getUploadPath() . basename($value),
                'mime' => FileHelper::getMimeType($value),
            ];
        }, $files);
    }

    public function getLikeModel()
    {
        return $this->hasOne(SpaceLike::class, ['id_space' => 'id'])->where(['author' => Yii::$app->user->identity->username]);
    }

    /**
     * Лайкнул ли пользователь 
     * @return bool
     */
    // public function isLike()
    // {
    //     return (new Query())
    //         ->from('{{%contest_space_like}}')
    //         ->where([
    //             'author' => Yii::$app->user->identity->username,
    //             'id_space' => $this->id,
    //         ])
    //         ->exists();
    // }

    public function like()
    {
        $model = $this->likeModel;

        if ($model) {
            $this->unlink('likeModel', $model, true);                     
        }
        else {
            $modelLike = new SpaceLike([
                'id_space' => $this->id,
                'author' => User::getUsername(),
            ]);
            $this->link('likeModel', $modelLike);
        }
        $this->save();
    }

    public function countLike()
    {
        return (new Query())
            ->from('{{%contest_space_like}}')
            ->where([
                'id_space' => $this->id,
            ])
            ->count(); 
    }



}
