<?php

namespace app\modules\calendar\controllers;

use Yii;
use app\modules\calendar\models\Calendar;
use app\modules\calendar\models\CalendarColor;
use yii\bootstrap5\Html;
use yii\filters\AccessControl;
use app\components\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\Response;

/**
 * DefaultController implements the CRUD actions for Calendar model.
 */
class DefaultController extends Controller
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
                        'actions' => ['view', 'json-data'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'allow' => true,
                        'roles' => ['admin', 'calendar-moderator'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Вывод всех событий по указанной дате
     * @return string
     */
    public function actionView($date)
    {
        $query = $this->findByDate($date);
        $model = ArrayHelper::map($query, 'id', 'full', 'type_text');
        $modelColor = $this->findDateColor($date);
        if ($modelColor===null) {
            $modelColor = new CalendarColor([
                'date' => $date,
                'org_code' => Yii::$app->user->identity->current_organization,
            ]);
        }

        Yii::$app->response->format = Response::FORMAT_JSON;

        if (isset($_POST['hasEditable'])) {
            $modelColor->load($_POST);
            $value = $modelColor->displayDateWithColor;
            $resultSaveColor = false;
            if (empty($value)) {
                $resultSaveColor = $modelColor->delete();
            }
            else {
                $resultSaveColor = $modelColor->save();
            }

            return [
                'output' => $value, 
                'message'=> $resultSaveColor === false ? '<span class="text-danger">При сохранении возникли ошибки</span>' : '',
                'debug' => $modelColor->getErrors(),
            ];
        }

        return [
            'header' => $this->renderAjax('title', [
                'model' => $modelColor,
                'date' => $date,      
            ]),
            'content' => $this->renderAjax('view', [
                'model' => $model,
                'date' => $date,   
                'modelColor' => $modelColor,             
            ]),
        ];
    }

    /**
     * Creates a new Calendar model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($date)
    {
        $model = new Calendar();
        $model->date = $date;
        $model->org_code = Yii::$app->user->identity->current_organization;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {            
            return $this->redirectPjax(['view', 'date'=>$date]);
        }

        return $this->renderAjax('create', [
            'model' => $model,
            'date' => $date,
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
            return $this->redirectPjax(['view', 'date'=>$model->date]);
        }

        return $this->renderAjax('update', [
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
        $model = $this->findModel($id);
        $model->delete();
        return $this->redirectPjax(['view', 'date'=>$model->date]);
    }    



    public function actionJsonData($date=null)
    {               
        if ($date == null) {
            $date = Yii::$app->formatter->asDate('now');
        }
        $resultQuery = $this->baseQuery($date)->queryAll();
        return json_encode($this->prepareData($resultQuery));
    }

    /**
     * Подготовка данных
     * @return array
     */
    private function prepareData($data)
    {
        $result = [];

        foreach ($data as $item) {

            $date = Yii::$app->formatter->asDate($item['date']);
            $description = Html::encode($item['description']);
            $string = "<span class='badge calendar-badge-{$item['color']} f-size-08 text-white white-space-normal text-left'>{$description}</span>";
            $typeText = $item['type_text'];
           
            $colorClass = '';
            if (!empty($item['color_date'])) {
                $colorClass .= 'badge calendar-badge-' . $item['color_date'];
            }
            else {
                $colorClass .= 'font-weight-bold';
            }

            if (!isset($result[$date])) {
                $result[$date] = [];
            }
            $d = &$result[$date];          

            if (isset($d['content'][$typeText])) {
                $d['content'][$typeText] .= '<br />' . $string;
            }
            else {
                $d['content'][$typeText] = $string;
            }

            $d['title'] = isset($d['title']) ? $d['title'] : $date;            
            $d['content'] = isset($d['content']) ? $d['content'] : [$typeText => $string];


            // если день рождения
            if ($item['is_birthday']) {                
                $d['dopColorClass'] = $item['color_date'];
            }
            // если не день рождения
            else {
                $d['colorClass'] = isset($d['colorClass']) ? $d['colorClass'] : $colorClass;                
            }
        }

        return $result;        
    }

    /**
     * Настройка базового запроса (с определенными фильтрами, сортировкой, ...)
     * @return \yii\db\Command
     */
    private function baseQuery($date)
    {      
        $sql = "
        select * from (
            
            select 
                 t.date
                ,t.description
                ,t.color 
                ,t.type_text
                ,color.color as color_date
                ,t.is_global
                ,0 is_birthday
                ,t.sort
                ,t.date_create
            from {{%calendar}} t
            outer apply (
                select top 1 * from p_calendar_color color 
                where color.date = t.date 
                order by case when color.org_code=:org_code_color then 1 else 0 end desc
            ) color
            where t.date_delete is null                
                and t.date >= :date1_1 and t.date <= :date1_2
                and (t.org_code = :org_code_t or t.is_global = 1)
            
            union select 
                DATEADD(YEAR,YEAR(GETDATE())-YEAR(t_b.date),t_b.date) [date]
               ,t_b.fio + case when t_b.department is not null then ' (' + t_b.department + ')' else '' end description
               ,:color_text color
               ,:type_text type_text
               ,:color_date color_date
               ,0 is_global
               ,1 is_birthday
               ,0 sort
               ,t_b.date_create
            from {{%calendar_bithdays}} t_b
            where DATEADD(YEAR,YEAR(GETDATE())-YEAR(t_b.date),t_b.date) >= :date2_1 
                and DATEADD(YEAR,YEAR(GETDATE())-YEAR(t_b.date),t_b.date) <= :date2_2
                and t_b.org_code = :org_code_t_b

        ) as t
        order by 
               t.is_global asc
              ,t.sort desc
              ,t.date_create asc
        ";

        $orgCode = Yii::$app->user->isGuest ? null : Yii::$app->user->identity->current_organization;

        $params = Yii::$app->params['calendar']['birhdays'];

        return Yii::$app->db->createCommand($sql, [
            ':org_code_color' => $orgCode,
            ':org_code_t' => $orgCode,
            ':org_code_t_b' => $orgCode,
            ':date1_1' => Yii::$app->formatter->asDate(strtotime('-1 months', strtotime($date))),
            ':date1_2' => Yii::$app->formatter->asDate(strtotime('+2 months', strtotime($date))),
            ':date2_1' => Yii::$app->formatter->asDate(strtotime('-1 months', strtotime($date))),
            ':date2_2' => Yii::$app->formatter->asDate(strtotime('+2 months', strtotime($date))),
            ':color_text' => $params['color_text'],
            ':type_text' => $params['type_text'],
            ':color_date' => $params['color_day'],
        ]);
    }

    /**
     * Перенаправление с учетом pjax (без перегрузки страницы)
     * @return string
     */
    private function redirectPjax($url, $container=null)
    {
        return $this->renderAjax('../redirect-pjax', [
            'url' => Url::to($url),
            'container' => $container ?? '#pjax-calendar-view',
        ]);
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
                'org_code' => Yii::$app->user->identity->current_organization,
            ])
            ->all();
    }

    /**
     * Поиск цвета даты
     * @return CalendarColor|null
     */
    private function findDateColor($date)
    {
        return CalendarColor::find()->where([
            'date' => $date,
            'org_code' => Yii::$app->user->identity->current_organization,
        ])->one();
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

        
}
