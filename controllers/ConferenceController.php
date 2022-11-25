<?php

namespace app\controllers;

use app\models\conference\ConferenceSearch;
use app\models\conference\VksFnsSearch;
use app\models\conference\VksUfnsSearch;
use app\models\conference\VksExternalSearch;
use Yii;
use app\models\conference\Conference;
use yii\filters\AccessControl;
use app\components\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\db\Query;
use yii\helpers\Url;
use app\models\conference\EventsAll;
use yii\helpers\StringHelper;
use app\models\conference\AbstractConference;
use app\models\conference\VksKonturTalk;
use app\models\conference\VksKonturTalkSearch;
use yii\base\DynamicModel;
use yii\data\ActiveDataProvider;
use yii\web\HttpException;
use yii\web\Response;
use yii\web\ServerErrorHttpException;

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
                    'request-delete' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['conference', 'vks-ufns', 'vks-fns', 'vks-external', 'view', 'calendar', 
                            'calendar-data', 'table', 'resources', 'request', 'request-create', 'request-update',
                            'request-delete', 'request-view',
                            'vks-kontur-talk-index',
                        ],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['request-approve', 'request-approve-view'],
                        'allow' => true,
                        'roles' => ['permConferenceApprove'],
                    ],
                    [
                        'actions' => ['vks-kontur-talk-create', 'vks-kontur-talk-update', 'vks-kontur-talk-delete'],
                        'allow' => true,
                        'roles' => ['VKS.KonturTalk.moderator', 'admin'],                       
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
            AbstractConference::TYPE_VKS_KONTUR_TALK => [
                'view' => 'view/viewKonturTalk',
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
        Yii::$app->response->format = Response::FORMAT_JSON;
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
     * Заявки для утверждения
     */
    public function actionRequest()
    {
        $requests = Conference::findPublic()->where(['not', ['approve_author' => null]]);
        
        if (!Yii::$app->user->can('permConferenceApprove')) {
            $requests->andWhere([
                'editor' => Yii::$app->user->identity->username,
            ]);
        }
        else {
            $requests->andWhere(['not', [
                 'status' => Conference::STATUS_COMPLETE,
            ]]);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $requests,
        ]);
        
        return $this->render('request/index', [
            'dataProvider' =>  $dataProvider,
        ]);
    }
    
    /**
     * Создание заявки
     * @return string
     */
    public function actionRequestCreate()
    {
        $model = new Conference();
        $model->status = Conference::STATUS_APPROVE;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $model->notifyMailAddressAppeal();
            return $this->redirect(['request']);
        }

        return $this->render('request/create', [
            'model' => $model,
        ]);
    }   
    
    /**
     * Изменение заявки
     * @return string
     */
    public function actionRequestUpdate($id)
    {
        $model = $this->findModelRequest($id);
        
        if ($model->load(Yii::$app->request->post())) {
            $model->status = Conference::STATUS_APPROVE;
            if ($model->save()) {
                $model->notifyMailAddressAppeal();
                return $this->redirect(['request']);
            }
        }

        return $this->render('request/update', [
            'model' => $model,
        ]);        
    }
    
    /**
     * Удаление заявки
     * Только, если статус на завершено
     * @return string
     */
    public function actionRequestDelete($id)
    {
        $model = $this->findModelRequest($id);
        if ($model->status === AbstractConference::STATUS_COMPLETE) {
            throw new ServerErrorHttpException('Запрещено удаление утвержденной заявки');
        }
        $model->delete();
        return $this->redirect('request');
    }

    /**
     * Просмотр заявки
     * @return string
     */
    public function actionRequestView($id)
    {
        $model = $this->findModelRequest($id);
        
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;        
        return [
            'title' => $model->getTitle(),
            'content' => $this->renderAjax('request/view', [
                'model' => $model,
            ]),
        ];
    }

    /**
     * Список заявок для согласования
     * @return string
     */
    public function actionRequestApprove()
    {
        $requests = Conference::findPublic()
            ->where(['status' => Conference::STATUS_APPROVE]);
        
        $dataProvider = new ActiveDataProvider([
            'query' => $requests,
        ]);
        
        return $this->render('request/approve', [
            'dataProvider' =>  $dataProvider,
        ]);
    }

    /**
     * Согласование или отказ в согласовании заявки
     * @param int $id
     * @return string
     */
    public function actionRequestApproveView($id)
    {
        $model = $this->findModelRequest($id);
        if ($model->status !== AbstractConference::STATUS_APPROVE) {
            throw new ServerErrorHttpException('Заявка уже ппрошла процедуру согласования');
        }

        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $formData = new DynamicModel(['result', 'denied_message']);
        $formData->addRule('result', 'boolean')
            ->addRule('result', 'required', ['message'=>'Выберите "Согласовать" или "Отказать"'])            
            ->addRule('denied_message', 'string', ['max'=>2000])
            ->addRule('denied_message', 'required', ['when' => function($model) { return $model->result === '0'; }, 'message'=>'Укажите причину отказа']);            
        
        if ($formData->load(Yii::$app->request->post()) && $formData->validate()) {              
            // save
            if ($formData->result) {
                $model->status = AbstractConference::STATUS_COMPLETE;
                $model->denied_text = null;                
            }
            else {
                $model->status = AbstractConference::STATUS_DENIED;
                $model->denied_text = $formData->denied_message;
            }
            $model->approve_author = Yii::$app->user->identity->username;
            $model->save();
            return 'OK';
        }

        return [
            'title' => 'Согласование "' . $model->typeLabel() . ' в ' . $model->date_start . '"',
            'content' => $this->renderAjax('request/approve-view', [
                'formData' => $formData,
                'model' => $model,
            ]),
        ];

    }



    /** ------------------- < Видеоконференция сервиса Контур.Толк > ------------------- */

    /**
     * @return string
     */
    public function actionVksKonturTalkIndex()
    {
        $searchModel = new VksKonturTalkSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('vksKonturTalk', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * @return string
     */
    public function actionVksKonturTalkCreate()
    {
        $model = new VksKonturTalk();
        $model->status = $model::STATUS_APPROVE;

        Yii::$app->response->format = Response::FORMAT_JSON;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return ['content' => 'ok'];
        }

        return [
            'title' => 'Добавление ' . $model::getTypeLabel(),
            'content' =>  $this->renderAjax('vksKonturTalkForm', [
                'model' => $model,
            ]),
        ];
    }

    /**
     * @param int $id
     * @return string
     */
    public function actionVksKonturTalkUpdate($id)
    {
        $model = $this->findModelKonturTalk($id);
        
        Yii::$app->response->format = Response::FORMAT_JSON;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return ['content' => 'ok'];
        }

        return [
            'title' => 'Измнение ' . $model::getTypeLabel(),
            'content' =>  $this->renderAjax('vksKonturTalkForm', [
                'model' => $model,
            ]),
        ];
    }

    /**
     * @param int $id
     * @return string
     */
    public function actionVksKonturTalkDelete($id)
    {
        $model = $this->findModelKonturTalk($id);
        $model->delete();
        return 'OK';
    }


    /** ------------------- < / Видеоконференция сервиса Контур.Толк > ------------------- */


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
     * @return Conference
     * @throws NotFoundHttpException
     */
    protected function findModelRequest($id)
    {
        $model = Conference::findPublic()
            ->where(['id'=>$id])
            ->andWhere(['not', ['status' => Conference::STATUS_COMPLETE]]);
        if (!Yii::$app->user->can('permConferenceApprove')) {
            $model->andWhere(['editor' => Yii::$app->user->identity->username]);
        }
        
        if (($result = $model->one()) !== null) {
            return $result;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * @param int $id
     * @return VksKonturTalk
     */
    public function findModelKonturTalk($id)
    {
        $model = VksKonturTalk::findPublic()
            ->andWhere(['id' => $id])
            ->one();
        if ($model === null || !$model->isModerator()) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
        return $model;
    }

}
