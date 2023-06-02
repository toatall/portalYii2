<?php
namespace app\modules\admin\modules\grantaccess\widgets;

use Yii;
use yii\base\InvalidConfigException;
use yii\bootstrap5\Widget;
use yii\helpers\Url;

/**
 * Предоставление прав пользователям
 * 
 * @example 
 * 
 * GrantAccessWidget::widget([
 *     'uniques' => [
 *         ['id' => 'role_editor', 'label' => 'Роль редактора'],
 *         ['id' => 'role_moderator', 'label' => 'Роль модератора'],
 *     ],
 * ]);
 * 
 * или
 * 
 * GrantAccessWidget::widget([
 *     'uniques' => [
 *         'role_editor',
 *         'role_moderator',
 *     ],
 * ]);
 * 
 * 
 * Для проверки можно воспользоваться стандартной проверкой доступа
 * Yii::$app->user->can('role_editor');
 */
class GrantAccessWidget extends Widget
{
    /**
     * Уникальные идентификаторы по которым
     * будут предоставлены права
     * 
     * При проверке прав необходимо 
     * использоваться данный идентификатор
     * 
     * Для именования каждого элемента следует 
     * использовать запись в виде массива:
     * 
     * 
     * @var array 
     */
    public $uniques;    

    /**
     * @var string
     */
    public $urlIndex = '/admin/grantaccess/default/index';

    /**
     * @inheritDoc
     */
    public function init()
    {
        parent::init();
        if (empty($this->uniques)) {
            throw new InvalidConfigException("The 'uniques' option is required.");
        }
    }

    /**
     * @inheritDoc
     */
    public function run()
    {        
        if (!Yii::$app->user->can('admin')) {
            return null;
        }
        return $this->render('index', [
            'items' => $this->prepare($this->uniques),
        ]);
    }

    /**
     * Подготовка массива для DropDown
     * @param string $unique
     * @return array
     */
    private function prepare($uniques)
    {
        $result = [];
        foreach((array)$uniques as $unique) {
            if (is_array($unique)) {
                $result[] = [
                    'label' => $unique['label'],
                    'url' => Url::to([$this->urlIndex, 'unique'=>$unique['id']]),
                    'linkOptions' => ['class' => 'mv-link'],
                ];                
            }
            else {
                $result[] = [
                    'label' => $unique,
                    'url' => Url::to([$this->urlIndex, 'unique'=>$unique]),
                    'linkOptions' => ['class' => 'mv-link'],
                ];
            }
        }
        return $result;
    }

}