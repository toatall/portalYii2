<?php
namespace app\models\conference;


use Yii;
use yii\db\Query;
use yii\helpers\ArrayHelper;

/**
 * Видеоконференции по сервису Контур.Толк
 * 
 */
class VksKonturTalk extends AbstractConference
{

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['type_conference', 'theme', 'date_start', 'duration'], 'required'], 
            [['code_org'], 'safe'],
            [['date_start'], function($attribute) {
                $query = self::find()                    
                    ->where(['type_conference' => $this::getType()])
                    ->andWhere('cast(:time as datetime) between {{date_start}} and cast({{date_start}} as datetime) + cast({{duration}} as datetime)', [
                        ':time' => $this->date_start
                    ])
                    ->andFilterWhere(['not', ['id' => $this->id]])
                    ->one();
                if ($query) {
                    $this->addError($attribute, '<strong>Пересечение с другим ВКС по Контур.Толк!</strong><br />'
                        . 'Начало: ' . $query->date_start . '<br />'
                        . 'Продолжительность: ' . $query->duration . '<br />'
                        . 'Тема: ' . $query->theme . '<br />'
                        . 'Налоговый орган: ' . $query->organization->fullName
                    );
                }  
            }],          
        ];
    }

    /**
     * @return string
     */
    public static function roleModerator()
    {
        return Yii::$app->params['conference']['kontur.talk']['roles']['moderator'];
    }

    /**
     * @return int
     */
    public static function getType()
    {
        return self::TYPE_VKS_KONTUR_TALK;
    }

    /**
     * @return string
     */
    public static function getModule()
    {
        return 'vks-kontur-talk';
    }

    /**
     * @return string
     */
    public static function getTypeLabel() 
    {
        return 'ВКС по Контур.Толк';
    }

    /**
     * Список организаций
     * @return string[]
     */
    public function getDropDownOrganizations()
    {
        $query = (new Query())
            ->from('{{%organization}}')
            ->where(['date_end' => null]);

        if (!Yii::$app->user->can('admin')) {
            $query->andWhere(['code' => Yii::$app->user->identity->default_organization]);
        }
        return ArrayHelper::map($query->all(), 'code', 'name');
    }


    /**
     * Проверка прав модератора или выше
     * @return bool
     */
    public function isModerator()
    {
        if (Yii::$app->user->isGuest) {
            return false;
        }
        if (Yii::$app->user->can('admin')) {
            return true;
        } 
        if (!Yii::$app->user->can(self::roleModerator())) {
            return false;
        }
        return $this->code_org === Yii::$app->user->identity->default_organization;
    }
    
    
   

}