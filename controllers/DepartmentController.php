<?php

namespace app\controllers;

use app\models\department\DepartmentCard;
use app\models\menu\MenuBuilder;
use app\models\OP;
use app\models\page\Page;
use app\models\page\PageSearch;
use app\models\Tree;
use Yii;
use app\models\department\Department;
use app\models\Organization;
use yii\data\ActiveDataProvider;
use yii\db\Expression;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use app\components\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\UploadedFile;

/**
 * DepartmentController implements the CRUD actions for Department model.
 */
class DepartmentController extends Controller
{
    /**
     * @var Department
     */
    protected $modelDepartment;

    /**
     * @var Tree
     */
    protected $modelTree;


    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Department models.
     * @return mixed
     */
    public function actionIndex($org=null)
    {
        if ($org==null) {
            $org='8600';
        }

        $modelOrganization = Organization::findOne($org);
        $dataProvider = new ActiveDataProvider([
            'query' => Department::find()->where(['id_organization'=>$org])->orderBy(['department_index' => SORT_ASC, 'department_name' => SORT_ASC]),
            'pagination' => [
                'pageSize' => false,
            ],
        ]);

        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('crud/index', [
                'dataProvider' => $dataProvider,
                'modelOrganization' => $modelOrganization,
            ]);
        }

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'modelOrganization' => $modelOrganization,
        ]);
    }

    /**
     * @return mixed
     */
    public function actionJsonListByOrg($selected = '')
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        if (isset($_POST['depdrop_parents'])) {           
            $parents = $_POST['depdrop_parents'];
            if ($parents != null) {
                $org_code = $parents[0] ?? 0;
                $data = Department::dropDownList($org_code);
                $list = [];
                foreach($data as $key=>$value) {
                    $list[] = ['id' => $key, 'name' => $value];
                }
                return [
                    'output' => $list,
                    'selected' => $selected,
                ];
            }
        }
        return [
            'output' => '',
            'selected' => '',
        ];
    }

    /**
     * Создание отдела
     * @param string $org код организации
     * @return mixed
     */
    public function actionCrudCreate($org)
    {
        if (!Department::isRoleModerator($org)) {
            throw new ForbiddenHttpException();
        }

        $model = new Department();
        $model->id_organization = $org;
        $model->id_tree = 0;

        Yii::$app->response->format = Response::FORMAT_JSON;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {            
            return ['content' => 'OK', 'updateId' => '#org_container_2'];
        }
    
        return [
            'title' => 'Создание отдела',
            'content' => $this->renderAjax('crud/form', [
                'model' => $model,
            ]),
        ];
    }

    public function actionCrudUpdate($id)
    {
        $model = $this->findModel($id);

        if (!Department::isRoleModerator($model->id_organization)) {
            throw new ForbiddenHttpException();
        }

        Yii::$app->response->format = Response::FORMAT_JSON;
        if ($model->load(Yii::$app->request->post()) && $model->save()) {            
            return ['content' => 'OK', 'updateId' => '#org_container_2'];
        }
        return [
            'title' => 'Изменение отдела' . $model->department_name,
            'content' => $this->renderAjax('crud/form', [
                'model' => $model,
            ]),
        ];
    }

    public function actionCrudDelete($id)
    {
        $model = $this->findModel($id);

        if (!Department::isRoleModerator($model->id_organization)) {
            throw new ForbiddenHttpException();
        }

        if ($model->delete()) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['content' => 'OK'];
        }
    }

    /**
     * Карточки сотрудников
     * @param int $id ид отдела
     * @return string
     */
    public function actionCrudCards($id)
    {
        $modelDepartment = $this->findModelDepartment($id);
        $this->structCards($modelDepartment->id);

        return $this->renderAjax('struct', [
            'model' => $modelDepartment,
            'arrayCard' => $this->structCards($id),            
        ]);
    }




    /**
     * Отображение материала отдела
     * 1. Если $idTree != null и в текущем классе имеется
     * функция с именем "render_$module$", где $module$ - имя модуля (Tree->module)
     * то вызываем эту функцию и передаем ей управление (изначально было задумано так
     * для рейтингов, но может пригодится где-то еще)
     *
     * 2. Если $idTree != null, то смотрим в настройки отдела.
     * 2.1. Если Department->general_page_type == 0 (отображать первую новость), то
     * нужно попытаться найти первую новость и показать ее (отсортировав по id),
     * если не удалось найти то установить флаг того, что ничего не найдено
     * 2.2. Если Department->general_page_type == 1 (показывать новость из списка), то
     * нужно найти новость в модели News с id_tree = Department->general_page_tree_id
     * (+ условия не удалена и не заблокирована), если нашлась то показать ее, иначе
     * установить флаг того, что ничего не найдено
     * 2.3. Если Department->general_page_type == 2 (показывать структуру отдела), то
     * проверить включена ли опция Department->use_card и если да, то вывести структуру,
     * иначе установить флаг того, что ничего не найдено
     * 2.4. Если имеется флаг, что ничего не найдено, то проверить есть ли дочерние объекты
     * в модели Tree и если есть, то вывести эту структуру, иначе вывести, что нет данных
     *
     * 3. Если присутсвует (и существует) $idTree, но не проходит условия п.1, то:
     * 3.1. Проверяем если есть только 1 новость, то выводим ее, причем сразу в представлении view
     * 3.2. Если новостей больше, то вывести просто их список (представление index)
     *
     * Где Department - это модель текущего отдела (напимер, так: $this->model = $this->loadModel($id))
     *
     * @author toatall
     * @uses
     */
    public function actionView($id, $idTree = null)
    {
        $model = $this->findModelDepartment($id);

        if ($idTree == null) {
            return $this->showDepartment($model);
        } else {
            $this->findModelTree($idTree);
            return $this->showTreeNode($idTree, $model);
        }
    }

    /**
     * @param $id
     * @return string
     * @throws HttpException
     * @throws NotFoundHttpException
     */
    public function actionStruct($id)
    {
        $model = $this->findModel($id);
        return $this->render('struct', [
            'model' => $model,
            'arrayCard' => $this->structCards($id),
        ]);
    }

    /**
     * Главная страница отдела
     * @return string
     */
    protected function showDepartment($modelDep)
    {
        $modelDepartment = $modelDep;
        $this->view->title = $modelDepartment->department_name;

        // отображение новости
        if ($modelDepartment->general_page_type == Department::GP_SHOW_FIRST_NEWS) {
            $modelPage = $this->findQueryPage()
                ->andWhere([
                    'id_tree' => $modelDepartment->id_tree,
                ])
                ->one();

            if ($modelPage != null) {
                return $this->render('/news/view', [
                    'model' => $modelPage,
                ]);
            }
        }

        // новость из списка
        if ($modelDepartment->general_page_type == Department::GP_SHOW_NEWS_FROM_LIST && is_numeric($modelDepartment->general_page_id_tree)) {
            $modelPage = $this->findQueryPage()
                ->andWhere([
                    'id_tree' => $modelDepartment->id_tree,
                    'id' => $modelDepartment->general_page_id_tree,
                ])
                ->one();

            if ($modelPage != null) {
                return $this->render('/news/view', [
                    'model' => $modelPage,
                ]);
            }
        }

        // структура отдела
        if ($modelDepartment->general_page_type == Department::GP_SHOW_STRUCT && $modelDepartment->use_card) {
            return $this->render('struct', [
                'model' => $modelDepartment,
                'arrayCard' => $this->structCards($modelDepartment->id),
            ]);
        }

        // показать дерево отдела
        $departmentTree = $modelDepartment->departmentTree($modelDepartment->id_tree, $modelDepartment->id);
        if ($departmentTree != null) {
            return $this->render('tree', [
                'model' => $modelDepartment,
                'departmentTree' => $departmentTree,
            ]);
        }

        return $this->render('noData', [
            'model' => $modelDepartment,
        ]);
    }

    /**
     * @return \app\models\news\NewsQuery
     */
    private function findQueryPage()
    {
        return Page::find()
            ->where([
                'date_delete' => null,
                'flag_enable' => true,
            ])
            ->andFilterWhere(['<', 'date_start_pub', (new Expression('getdate()'))])
            ->andFilterWhere(['>', 'date_end_pub', (new Expression('getdate()'))]);
    }

    /**
     * @todo изменить на namespace в модулях
     * @param $module
     * @return mixed|null
     * @throws HttpException
     */
    protected function isModule($module)
    {
        $modules = [
            'rating',
        ];
        return in_array($module, $modules);
    }

    /**
     * @param $id
     * @return mixed|string
     * @throws HttpException
     * @throws NotFoundHttpException
     */
    protected function showTreeNode($id, $modelDepartment)
    {
        $breadcrubms = $this->breadcrumbsTreePath($id, $modelDepartment);
        $this->view->title = $this->modelTree->name;

        // 1. подгрузка по модулю (например, рейтинги)
        if ($this->isModule($this->modelTree->module)) {
            return $this->render('ajaxData', [
                'modelDepartment' => $this->modelDepartment,
                'url' => Url::to(['/rating/index', 'idTree'=>$id]),
                'breadcrumbs' => $breadcrubms,
            ]);
        }

        // 2. Если не указан модуль, то показать подразделы
        if ($this->modelTree->module == null) {
            $treeDepartment = $this->modelDepartment->departmentTree($id, $this->modelDepartment->id, true);
            if ($treeDepartment != null) {
                return $this->render('tree', [
                    'model' => $this->modelDepartment,
                    'departmentTree' => $treeDepartment,
                ]);
            }
        }

        // 3. Поиск новостей по текущему разделу
        $query = new PageSearch();
        $query->id_tree = $id;
        $dataProvider = $query->searchPublic(null);
        $resultDataProvider = $dataProvider->getModels();

        // 4. Если только 1 новость, то выводим ее
        if (($dataProvider->getTotalCount() === 1)) {
            return $this->render('/news/view', [
                'model' => array_shift($resultDataProvider),
            ]);            
        }

        return $this->render('news', [
            'dataProvider' => $dataProvider,
            'modelTree' => $this->modelTree,
            'modelDepartment' => $this->modelDepartment,
            'breadcrumbs' => $breadcrubms,
        ]);
    }

    /**
     * Карточки сотрудников отдела
     * @return array
     */
    protected function structCards($idDepartment)
    {
        /** @var  DepartmentCard[]  $query */
        $query = DepartmentCard::find()
            ->where(['id_department' => $idDepartment])
            ->all();

        $arrayCard = [];
        foreach ($query as $card) {
            $arrayCard[$card->user_level][] = [
                'id' => $card->id,
                'user_photo' => $card->getUserPhotoFile(),
                'user_fio' => $card->user_fio,
                'user_rank' => $card->user_rank,
                'user_position' => $card->user_position,
                'user_telephone' => $card->user_telephone,
                'user_resp' => $card->user_resp,
            ];
        }
        ksort($arrayCard);
        return $arrayCard;
    }

    /**
     * Finds the Department model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Department the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     * @throws HttpException
     */
    protected function findModelDepartment($id)
    {
        if (($this->modelDepartment = Department::findOne($id)) !== null) {
            $this->loadMenu();
            return $this->modelDepartment;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * @param $id
     * @return Tree|\yii\db\ActiveQuery
     * @throws NotFoundHttpException
     */
    protected function findModelTree($id)
    {
        if (($this->modelTree = Tree::findPublic()->andWhere(['id'=>$id])->one()) !== null) {
            return $this->modelTree;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * @param $id
     * @return OP|null
     * @throws NotFoundHttpException
     */
    protected function findModelOP($id)
    {
        if (($model = OP::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * Finds the Department model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Department the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Department::/*backendFindByPk($id)*/findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * Строка навигации
     * @param $idTree
     * @return array
     */
    private function breadcrumbsTreePath($idTree, $modelDepartment)
    {
        if ($modelDepartment->id_tree == $idTree)
            return [];

        $query = new Query();
        $resultQuery = $query
            ->from('{{%tree}}')
            ->where([
                'id'=>$idTree,
                'date_delete'=>null,
            ])
            ->one();

        if ($resultQuery==null)
            return [];

        return ArrayHelper::merge($this->breadcrumbsTreePath($resultQuery['id_parent'], $modelDepartment), [[
            'label' => $resultQuery['name'],
            'url' => ['department/view', 'id'=>$modelDepartment->id, 'idTree'=>$idTree],
        ]]);
    }


    /**
     * Загрузка меню
     * @throws HttpException
     */
    protected function loadMenu()
    {
        $model = $this->modelDepartment;
        if ($model == null) {
            return;
        }
        $menu = [];

        if ($model->use_card) {
            $menu[] = [
                'label' => 'Структура',
                'url' => ['department/struct', 'id'=>$model->id],
            ];
        }

        if ($departmentMenu = $this->modelDepartment->getMenu()) {
            if ($model->use_card) {
                $menu[] = ['label'=>'', 'options'=>['class'=>'dropdown-divider']];
            }
            $menu = ArrayHelper::merge($menu, $departmentMenu);
        }

        // "Отраслевые" проекты
        if ($model->department_index == $this->getOPDepartmentIndex()) {
            $menu = ArrayHelper::merge($menu, [['label' => '"Отраслевые" проекты', 'url' => ['department/op']]]);
        }

        MenuBuilder::addLeftAdd($menu);
        
    }


    /**
     * @return string
     * @throws HttpException
     * @throws NotFoundHttpException
     */
    public function actionOp()
    {
        $modelDepartment = $this->findModel($this->getOPDepartmentIndex());
        $model = new OP();

        return $this->render('op/index', [
            'modelDepartment' => $modelDepartment,
            'data' => $model->getData(),
            'model' => $model,
        ]);
    }

    /**
     * @return mixed
     * @throws HttpException
     */
    private function getOPDepartmentIndex()
    {
        if (!isset(Yii::$app->params['department']['OP']['index'])) {
            throw new HttpException(500, 'Не указан индекс отдела в настройках params (department.OP.index)');
        }
        return Yii::$app->params['department']['OP']['index'];
    }

    /**
     * @param $idSection
     * @param int $section
     * @return string|\yii\web\Response
     * @throws HttpException
     * @throws NotFoundHttpException
     */
    public function actionOpCreate($idSection, $section=0)
    {
        $modelDepartment = $this->findModel($this->getOPDepartmentIndex());
        $model = new OP();
        $model->id_op_group = $idSection;
        $model->type_section = $section;

        if (!$model->isEditor()) {
            throw new ForbiddenHttpException();
        }

        if ($model->load(Yii::$app->request->post())) {
            $model->file = UploadedFile::getInstance($model, 'file');
            if ($model->save()) {
                return $this->redirect(['op']);
            }
        }

        return $this->render('op/create', [
            'model' => $model,
            'modelDepartment' => $modelDepartment,
        ]);
    }

    /**
     * @param $id
     * @return string|\yii\web\Response
     * @throws HttpException
     * @throws NotFoundHttpException
     */
    public function actionOpUpdate($id)
    {
        $model = $this->findModelOP($id);
        $modelDepartment = $this->findModel($this->getOPDepartmentIndex());

        if (!$model->isEditor()) {
            throw new ForbiddenHttpException();
        }

        if ($model->load(Yii::$app->request->post())) {
            $model->file = UploadedFile::getInstance($model, 'file');
            if ($model->save()) {
                return $this->redirect(['op']);
            }
        }

        return $this->render('op/update', [
            'model' => $model,
            'modelDepartment' => $modelDepartment,
        ]);
    }

    /**
     * @param $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionOpDelete($id)
    {
        $model = $this->findModelOP($id);

        if (!$model->isEditor()) {
            throw new ForbiddenHttpException();
        }

        $model->delete();

        return $this->redirect(['op']);
    }



}
