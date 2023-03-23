<?php
namespace app\models\menu;


use yii\db\Query;
use yii\helpers\Url;

/**
 * Список организаций
 * @package app\models\menu
 */
class MenuNewsOrganizations implements ISubMenu
{

    /**
     * @var string
     */
    public $templateItem = '<li class="{class-li}"><a href="{url}" class="{class-a}">{text}</a> {submenu}</li>';


    /**
     * @inheritDoc
     */
    public function renderMenu()
    {
        return '<ul class="dropdown-menu">' . $this->render(null) . '</ul>';
    }

    /**
     * @return string
     */
    protected function render($codeParent=null) 
    {
        $query = new Query();
        $query->from('{{%organization}}')
            ->where(['code_parent' => $codeParent])
            ->andWhere(['<>', 'code', '8600'])
            ->orderBy('code asc');

        $resultQuery = $query->all();

        $resultMenu = '';
        
        foreach ($resultQuery as $item) {

            $subMenu = $this->render($item['code']);
            
            $li = $this->templateItem;
            
            $url = Url::to(['/organization/view', 'id'=>$item['code']]);
                       
            $classA = ''; $classLi = '';
            if ($subMenu != '')  { 
                $classA = 'dropdown-item dropdown-toggle submenu';
                $classLi = 'dropdown-submenu';
            }
            else {
                $classA = 'dropdown-item';
            }

            if ($this->isActive($url)) {
                $classA .= ' active';
            }

            $replacePairs = [
                '{url}' => $url,
                '{class-a}' => $classA,
                '{text}' => $item['name'],
                '{class-li}' => $classLi,
            ];           
            
            if ($subMenu != '') {
                $replacePairs['{submenu}'] = '<ul class="dropdown-menu">' . $subMenu .'</ul>';
            }
            else {
                $replacePairs['{submenu}'] = '';
            }

            $resultMenu .= strtr($li, $replacePairs);

        }        

        return $resultMenu;
    }

    /**
     * @return boolean
     */
    protected function isActive($url)
    {
        return Url::current() == $url;
    }
}