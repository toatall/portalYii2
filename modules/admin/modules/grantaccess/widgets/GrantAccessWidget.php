<?php
namespace app\modules\admin\modules\grantaccess\widgets;

use Yii;
use yii\base\InvalidConfigException;
use yii\bootstrap5\Widget;
use yii\helpers\Url;

/**
 * Предоставление прав пользователям
 * 
 */
class GrantAccessWidget extends Widget
{
    /**
     * Уникальный идентификатор по которому 
     * будут предоставлены права
     * 
     * При проверке прав необходимо 
     * использоваться данный идентификатор
     * @var string 
     */
    public $unique;

    /**
     * Код организации
     * 
     * Если нет необходимости ограничивать по коду организации,
     * то нужно оставить данное свойство пустым
     * @var string 
     */
    public $codeOrg;

    /**
     * @var string
     */
    public $urlIndex = '/admin/grantaccess/default/index';


    /**
     * {@inheritDoc}
     */
    public function init()
    {
        parent::init();
        if (empty($this->unique)) {
            throw new InvalidConfigException("The 'unique' option is required.");
        }
    }

    /**
     * {@inheritDoc}
     */
    public function run()
    {        
        if (!Yii::$app->user->can('admin')) {
            return null;
        }

        $urlIndex = [$this->urlIndex, 'unique'=>$this->unique];
        if ($this->codeOrg) {
            $urlIndex = array_merge($urlIndex, ['orgCode' => $this->codeOrg]);
        }

        return $this->render('index', [
            'url' => [
                'index' => Url::to($urlIndex),
            ],
        ]);
    }

}