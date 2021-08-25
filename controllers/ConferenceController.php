<?php

namespace app\controllers;

use app\models\conference\ConferenceSearch;
use app\models\conference\VksFnsSearch;
use app\models\conference\VksUfnsSearch;
use app\models\conference\VksExternalSearch;
use Yii;
use app\models\conference\Conference;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\db\Query;
use yii\helpers\Url;
use app\models\conference\EventsAll;
use yii\helpers\StringHelper;
use app\models\conference\AbstractConference;
use yii\web\HttpException;

/**
 * ConferenceController implements the CRUD actions for Conference model.
 */
class ConferenceController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
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
     * Собрания
     * @return string
     */
    public function actionConference()
    {
        $searchModel = new ConferenceSearch();        
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('conference', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * ВКС с УФНС
     * @return string
     */
    public function actionVksUfns()
    {
        $searchModel = new VksUfnsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('vksUfns', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * ВКС с ФНС
     * @return string
     */
    public function actionVksFns()
    {
        $searchModel = new VksFnsSearch();        
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        
        return $this->render('vksFns', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    
    /**
     * ВКС внешние
     * @return string
     */
    public function actionVksExternal()
    {
        $searchModel = new VksExternalSearch();        
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('vksExternal', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    
    /**
     * Displays a single Conference model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);        
        $action = Url::to(['/admin/' . $model->strType() . '/update', 'id'=>$model->id]);
        $conferenceTypes = [
            AbstractConference::TYPE_VKS_UFNS => [
                'view' => 'view/viewVksUfns',                
            ],
            AbstractConference::TYPE_VKS_FNS => [
                'view' => 'view/viewVksFns',
            ],
            AbstractConference::TYPE_CONFERENCE => [
                'view' => 'view/viewConference',
            ],
            AbstractConference::TYPE_VKS_EXTERNAL => [
                'view' => 'view/viewVksExternal',
            ],
        ];
        
        if (isset($conferenceTypes[$model->type_conference])) {
            $render = [
                'view' => $conferenceTypes[$model->type_conference]['view'],
                'params' => [
                    'model' => $model,
                    'action' => $action,
                ],
            ];            
        }
        else {
            throw new HttpException(500, 'Не найдено подходящее представление');
        }                

        if (Yii::$app->request->isAjax) {
            $title = $model->accessShowAllFields() ? $model->theme . ' <br /><small>' . $model->typeLabel() . '</small>'
                : $model->typeLabel();
            
            $resultJson = [
                'title' => $title,
                'content' => $this->renderAjax($render['view'], $render['params']),
            ];
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return $resultJson;
        }

        return $this->render($render['view'], $render['params']);
    }
    
    /**
     * Calendar event
     * @return mixed
     */
    public function actionCalendar()
    {              
        //$this->checkRights(new Conference());
        return $this->render('calendar');
    }
    
    /**
     * Данные для календаря
     * @param type $start
     * @param type $end
     */
    public function actionCalendarData($start, $end, $filterType=null)
    {
        $query = EventsAll::findEvents(Yii::$app->formatter->asDate($start), Yii::$app->formatter->asDate($end));
        if (!empty($filterType)) {
            $query->andWhere(['in', 'type_conference', explode(',', $filterType)]);
        }        
                
        $events = [];
        foreach ($query->all() as $item) {
            /* @var $item EventsAll */
            $accessShowAllFields = $item->accessShowAllFields();
            
            $events[] = [
                'id' => $item->id,
                'title' => ($item->isCrossedMe() ? '<i class="fas fa-exclamation-circle text-danger" title="Пересечение по времени"></i> ' : '')
                    . ($item->isCrossedI() ? '<i class="fas fa-exclamation-triangle text-danger" title="Пересечение по времени"></i> ' : '')
                    . StringHelper::truncateWords($item->getTitle(), 5),
                'start' => date('c', strtotime($item->date_start)),
                'end' => date('c', strtotime($item->date_end)),
                'duration' => $item->duration,
                'url' => Url::to(['/conference/view', 'id' => $item->id]),
                'extendedProps' => [
                    'fullTitle' => $accessShowAllFields ? $item->theme : '',
                    'description' => $item->getDescription(),
                ],
                'color' => $item->getEventColor(),
                'className' => $item->isFinished() ? 'text-muted' : '',
                'resourceIds' => $item->arrPlace,
            ];
        }               
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $events;
    }
    
    /**
     * @return mixed
     */
    public function actionTable()
    {        
        return $this->render('table');
    }
    
    /**
     * Кабинеты
     * @return mixed
     */
    public function actionResources()
    {        
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $query = (new Query())
            ->from('{{%conference_location}}')
            ->orderBy(['val' => SORT_ASC])
            ->all();
        $result = array_map(function($value) {
            return ['id' => $value['val'], 'title' => $value['val']];
        }, $query);
        
        return $result;
    }
    
    /**
     * Не утвержденные заявки
     * (создают пользователи без проли conferenceManager)
     */
    public function actionRequest()
    {
        $requests = Conference::findPublic()
            ->andWhere([
                'editor' => Yii::$app->user->identity->username,
            ])
            ->andWhere(['not', [
                'status' => Conference::STATUS_COMPLETE,
            ]]);
        $dataProvider = new \yii\data\ActiveDataProvider([
            'query' => $requests,
        ]);
        
        return $this->render('request/index', [
            'dataProvider' =>  $dataProvider,
        ]);
    }
    
    /**
     * Создание события
     * @return string
     */
    public function actionRequestCreate()
    {
        $model = new Conference();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['request/view', 'id' => $model->id]);
        }

        return $this->render('request/create', [
            'model' => $model,
        ]);
    }
    
    public function actionRequestView($id)
    {
        
    }
    
    public function actionRequestUpdate($id)
    {
        
    }
    
    public function actionRequestDelete($id)
    {
        
    }

    /**
     * Finds the Conference model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Conference the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Conference::findPublic()->andWhere(['id' => $id])->one()) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    
    /**
     * 
     * @param int $id
     * @return string
     * @throws NotFoundHttpException
     */
    protected function findModelRequest($id)
    {
        $model = Conference::findPublic()->andWhere(['not', ['status' => Conference::STATUS_COMPLETE]]);
        if (!Yii::$app->user->can('conferenceManager')) {
            $model->andWhere(['editor' => Yii::$app->user->identity->username]);
        }
        
        if ($model->one() !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

}
