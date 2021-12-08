<?php

namespace app\modules\admin\controllers;

use Yii;
use app\models\calendar\Calendar;
use app\models\calendar\CalendarData;
use app\models\calendar\CalendarSearch;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * CalendarController implements the CRUD actions for Calendar model.
 */
class CalendarController extends Controller
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
                    'delete-calendar-data' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['admin', 'calendar-moderator'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Calendar models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CalendarSearch();
        $searchModel->searchMonth = date('01.m.Y');
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Calendar model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        return $this->render('view', [
            'model' => $model,
            'dataProviderCalendarData' => new ActiveDataProvider([
                'query' => $model->getData(),
            ]),
        ]);
    }

    /**
     * Creates a new Calendar model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Calendar();
        $model->scenario = 'create';
        $model->code_org = Yii::$app->user->identity->current_organization;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Массовое создание событий
     * @return mixed
     */
    public function actionCreateMulti()
    {
        $model = new Calendar();
        $model->scenario = 'create-multi';
        $model->code_org = Yii::$app->user->identity->current_organization;

        $modelData = new CalendarData();
        $modelData->id_calendar = 9999;
        
        if ($model->load(Yii::$app->request->post()) && $modelData->load(Yii::$app->request->post())) {
                        
            if ($model->validate() && $modelData->validate(['description', 'type_text'])) {
                
                $dates = explode('/', $model->dates);
                // сохранение по каждой дате
                foreach ($dates as $date) {
                    $date = trim($date);
                    // сохранение даты
                    $modelCalendar = $this->findByDate($date);
                    if ($modelCalendar === null) {
                        $modelCalendar = new Calendar([
                            'date' => $date,
                            'code_org' => Yii::$app->user->identity->current_organization,                            
                        ]);
                        $modelCalendar->save();
                    }
                    //  сохранение события                   
                    (new CalendarData([
                        'id_calendar' => $modelCalendar->id,
                        'type_text' => $modelData->type_text,
                        'color' => $modelData->color,
                        'description' => $modelData->description,
                        'is_global' => $modelData->is_global,
                        'sort' => $modelData->sort,
                    ]))->save();                    
                }
                return $this->redirect(['index']);
            }
        }

        return $this->render('create-multi', [
            'model' => $model,
            'modelData' => $modelData,
        ]);
    }

    /**
     * Updates an existing Calendar model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Calendar model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Добавление событие в дату календаря
     * @return string
     */
    public function actionCreateCalendarData($idCalendar)
    {        
        $modelCalendar = $this->findModel($idCalendar);
        $model = new CalendarData();
        $model->id_calendar = $idCalendar;

        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            if (Yii::$app->request->isAjax) {                
                return 'OK';
            }
            else {
                return $this->redirect(['/admin/view/calendar', 'id' => $idCalendar]);
            }
        }

        if (Yii::$app->request->isAjax) {             
            return [
                'title' => "Добавление события для даты {$modelCalendar->date}",
                'content' => $this->renderAjax('calendar-data/create', [
                    'model' => $model,
                    'modelCalendar' => $modelCalendar,
                ]),
            ];
        }
        else {
            return $this->render('calendar-data/create', [
                'model' => $model,
                'modelCalendar' => $modelCalendar,
            ]);
        }
    }

    /**
     * Обновление события (в дате)
     * @param int $id
     * @return string
     */
    public function actionUpdateCalendarData($id)
    {
        $model = $this->findModelCalendarData($id);
        $modelCalendar = $model->calendar;

        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            if (Yii::$app->request->isAjax) {                
                return 'OK';
            }
            else {
                return $this->redirect(['/admin/view/calendar', 'id' => $model->id_calendar]);
            }
        }

        Yii::$app->view->title = "Изменение события для даты {$modelCalendar->date}";
        if (Yii::$app->request->isAjax) {             
            return [
                'title' => "Изменение события для даты {$modelCalendar->date}",
                'content' => $this->renderAjax('calendar-data/update', [
                    'model' => $model,
                    'modelCalendar' => $modelCalendar,
                ]),
            ];
        }
        else {
            return $this->render('calendar-data/update', [
                'model' => $model,
                'modelCalendar' => $modelCalendar,
            ]);
        }
    }

    /**
     * Deletes an existing CalendarData model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDeleteCalendarData($id)
    {
        $model = $this->findModelCalendarData($id);
        $model->delete();

        return $this->redirect(['/admin/calendar/view', 'id'=>$model->id_calendar]);
    }


    /**
     * Finds the Calendar model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Calendar the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        $query = Calendar::find()->where(['id'=>$id]);
        if (\Yii::$app->user->can('calendar-moderator')) {
            $query->andWhere(['code_org' => Yii::$app->user->identity->current_organization]);
        }
        $model = $query->one();
        
        if (($model) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * Поиск модели по дата
     * @param string $date
     * @return Calendar|null
     */
    private function findByDate($date)
    {
        return Calendar::find()->where([
                'date' => $date,
                'code_org' => Yii::$app->user->identity->current_organization,
            ])
            ->one();
    }

    /**
     * @param integer $id
     * @return CalendarData the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModelCalendarData($id)
    {
        if (($model = CalendarData::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
