<?php
namespace app\models\json;

/**
 * Конструктор для фильтрации, сортировки, группировки данных массива
 * 
 * @example
 * ```php
 * $model = new JsonQuery([
 *     ['id' => 1, 'title' => 'title 1', 'code' => 'en', ...],
 *     ['id' => 2, 'title' => 'title 2', 'code' => 'ru', ...],
 *     ['id' => 3, 'title' => 'title 3', 'code' => 'ru', ...],
 *     ['id' => 4, 'title' => 'title 4', 'code' => 'en', ...],
 * ]);
 * $result = $model
 *     ->where(fn($item) => $item['id'] > 1)
 *     ->order('id', SORT_DESC)
 *     ->group('code')
 *     ->all();
 * ```
 * 
 * @author toatall
 * @version 2022-12-01
 * @see JsonModel
 */
class JsonQuery
{

    /**
     * @var mixed
     */
    private $callableClass;

    /**
     * @var array
     */
    private $jsonData = [];

    /**
     * @var callable|null
     */
    private $where;

    /**
     * @var string|array
     */
    private $order;

    /**
     * @var string|null
     */
    private $group;

    /**
     * @var bool
     */
    private $index = false;


    /**
     * Создание коструктора для фильтрации и сортировки данных
     * @param array $jsonData массив
     */
    public function __construct($jsonData, $callableClass)
    {
        $this->jsonData = $jsonData;
        $this->callableClass = $callableClass;
    }

    /**
     * Фильтрация данных с помощью анонимной функции
     * 
     * @example
     * ```php
     * $someModel->find()->where(
     *     fn($item) => $item['id'] > 100
     * )
     * ```
     * или
     * ```php
     * $someModel->find()->where(function($item) {
     *     return strncmp($item['code'], '99', 2) === 0;
     * })
     * ```
     * 
     * @param callable $functionFilter
     * @return $this
     * @see self::filterData()
     */
    public function where(callable $functionFilter)
    {
        $this->where = $functionFilter;
        return $this;
    }


    /**
     * Сортировка
     * Возможно использование сортировки только по одному полю
     * @param string $sortField наименование поля для сортироки
     * @param int $sortOrder порядок сортировки (по умолчанию SORT_ASC)
     * @return $this
     * @see self::sortingData()
     */
    public function order($sortField, $sortOrder = SORT_ASC)
    {
        $this->order = [$sortField => $sortOrder];
        return $this;
    }

    /**
     * Группировка
     * @param string $groupField
     * @return $this
     * @see self::groupingData()
     */
    public function group($groupField)
    {
        $this->group = $groupField;
        return $this;
    }

    /**
     * Индексирование списка
     * @param bool $flag
     * @return $this
     * @see self::indexation()
     */
    public function index($flag=true)
    {
        $this->index = $flag;
        return $this;
    }

    /**
     * Получение первой строки результирующих данных
     * @return array|null
     * @see self::processing()
     */
    public function one()
    {
        $data = $this->processing();
        if (is_array($data) && !empty($data)) {
            return reset($data);
        }
        return null;
    }

    /**
     * Получение результирующих данных
     * @return array|null
     * @see self::processing()
     */
    public function all()
    {
        $data = $this->processing();
        if ($data) {            
            return $data;
        }
        return null;
    }

    /**
     * Выполнение всех задач
     * @return array
     * @see self::filterData()
     * @see self::sortingData()
     * @see self::groupingData()
     */
    protected function processing()
    {
        $data = $this->filterData();
        $data = $this->sortingData($data);        
        $data = $this->indexing($data);
        $data = $this->wrapper($data);    
        $data = $this->groupingData($data);    
        return $data;
    }

    /**
     * Фильтр данных
     * @return array
     * @see self::where()
     */
    protected function filterData()
    {        
        if (!is_callable($this->where)) {
            return $this->jsonData;
        }       
        return array_filter($this->jsonData, $this->where);
    }

    /**
     * Процедура сортировка данных
     * @param array $data данные для сортировки
     * @return array
     * @see self::order()
     */
    protected function sortingData($data)
    {
        if (!$this->order || !is_array($this->order)) {
            return $data;
        }
        foreach($this->order as $sortField => $sortType) {
            usort($data, function($a, $b) use ($sortField, $sortType) {  
                if ($a[$sortField] == $b[$sortField]) {
                    return 0;
                }
                if ($sortType == SORT_DESC) {
                    return ($a[$sortField] < $b[$sortField]) ? 1 : -1;
                }
                return ($a[$sortField] < $b[$sortField]) ? -1 : 1;
            });
        }                
        return $data;
    }

    /**
     * Группировака данных
     * @param array $data
     * @see self::group()
     */
    protected function groupingData($data)
    {
        if (!$this->group) {
            return $data;
        }
        $result = [];
        foreach($data as $item) {
            if ($item instanceof JsonModel) {
                $result[$item->{$this->group}][] = $item;
            }
            else {
                $result[$item[$this->group]][] = $item;
            }
        }
        return $result;
    }

    /**
     * Индексирование данных
     * @param array $data входящий массив
     * @see self::index()
     * @return array
     */
    protected function indexing($data)
    {
        if (!$this->index) {
            return $data;
        }
        array_walk($data, function(&$item, $key) { 
            $item['index'] = md5(serialize($item)); 
            $item['id'] = $key;
        });
        return $data;
    }

    /**
     * Обертывание каждой строки массива в вызываемый класс
     * @param array $data исходный массив
     * @return array of callableClass
     */
    protected function wrapper($data)
    {
        if (!$data || !is_array($data)) {
            return $data;
        }
        $res = [];
        foreach($data as $item) {           
            $res[] = new $this->callableClass($item);          
        }
        return $res;
    }

}