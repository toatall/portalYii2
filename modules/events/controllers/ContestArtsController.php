<?php

namespace app\modules\events\controllers;

use Yii;
use app\modules\events\models\ContestArts;
use app\modules\events\models\ContestArtsResults;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\UploadedFile;
use yii\web\HttpException;
use yii\db\Query;
use app\modules\events\models\ContestArtsVote;


/**
 * ContestArtsController implements the CRUD actions for ContestArts model.
 */
class ContestArtsController extends Controller
{
    /**
     * {@inheritdoc}
     * @param type $action
     * @return type
     */
    public function beforeAction($action)
    {
        Yii::$app->params['bsVersion'] = '4.x';
        return parent::beforeAction($action);
    }
    
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['index', 'answer', 'winner', 'statistic', 'vote'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'allow' => true,
                        'roles' => ['admin', 'events.contestAtrs'],
                    ],
                ],                
            ],
        ];
    }

    /**
     * 1. Поиск картинки для показа на сегодня, если она есть, то выводим ее
     * Правила голосования: 
     *  a. голосуют только сотрудники Управления
     *  b. время голосования с 9-00 по 16-00
     *  c. за свой отдел голосовать нельзя
     *  d. проголосовать можно только 1 раз
     * 2. Внизу вывод фотографий за прошлые дни
     * 3. Список тех, кто правильно ответил (топ)
     * 4. Голосование по номинациям
     * @return mixed
     */
    public function actionIndex()
    {
        $this->layout = 'contest';
        
        // 1.
        $modelsToday = $this->findModelToday();        
        // 2.    
        $modelLastArts = $this->findLastArts();
        // 3.
        $winners = ContestArts::getWinners();
        // 4.
        $modelVotes = null;
        if (Yii::$app->user->identity->isOrg('8600')) {
            $modelVotes = (new Query())
                ->select('t.*')
                ->distinct(true)
                ->from('{{%contest_arts}} t')
                ->leftJoin('{{%contest_arts_vote}} vote', 't.id=vote.id_contest_arts and vote.author=:author', [':author' => Yii::$app->user->identity->username])
                ->where(['not in', 'department_ad_group', explode(', ', Yii::$app->user->identity->memberof)])
                ->andWhere(['vote.id' => null])
                ->andWhere(['not', ['t.department_name' => Yii::$app->user->identity->department]])
                ->all();            
        }        

        return $this->render('index', [
            'modelsToday' => $modelsToday,
            'modelLastArts' => $modelLastArts,
            'winners' => $winners,
            'modelVotes' => $modelVotes,
        ]);
    }
    
    /**
     * Lists all ContestArts models.
     * @return mixed
     */
    public function actionAdmin()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => ContestArts::find(),
        ]);

        return $this->render('admin', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ContestArts model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new ContestArts model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ContestArts();
        $model->scenario = 'create';

        if ($model->load(Yii::$app->request->post())) {
            
            $model->imageOriginal = UploadedFile::getInstance($model, 'imageOriginal');
            $model->imageReproduced = UploadedFile::getInstance($model, 'imageReproduced');
            $model->imageQrCode = UploadedFile::getInstance($model, 'imageQrCode');
            if ($model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }            
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing ContestArts model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            $model->imageOriginal = UploadedFile::getInstance($model, 'imageOriginal');
            $model->imageReproduced = UploadedFile::getInstance($model, 'imageReproduced');
            $model->imageQrCode = UploadedFile::getInstance($model, 'imageQrCode');
            if ($model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing ContestArts model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        return $this->redirect(['admin']);
    }
    
    /**
     * Голос сотрудника
     * @param int $id
     * @return string
     * @throws HttpException
     */
    public function actionAnswer($id)
    {
        $model = $this->findModel($id);
        // проверки возможности проголосовать
        $allowStr = $model->isAllow();
        // проверка полей
        if ($allowStr) {
            throw new HttpException(599, $allowStr);
        }
        $imageName = Yii::$app->request->post('image_name') ?? null;
        $imageAuthor = Yii::$app->request->post('image_author') ?? null;
        if (!$imageName || !$imageAuthor) {
            throw new HttpException(599, 'Не все поля переданы! Обновите страницу и попробуйте снова!');
        }
        // сохранение
        $insert = Yii::$app->db->createCommand()
            ->insert('{{%contest_arts_results}}', [
                'id_arts' => $id,
                'author' => Yii::$app->user->identity->username,
                'image_name' => $imageName,
                'image_author' => $imageAuthor,
            ])
            ->execute();
        if ($insert) {
            return 'Ваш ответ принят!';
        }
        else {
            throw new HttpException(599, 'Не удалось сохранить ответ! Обновите страницу и попробуйте снова!');
        }
    }
    
    /**
     * Установка правильных ответов
     * @param int $id
     * @return type
     */
    public function actionSetRight($id)
    {
        $model = $this->findModel($id);
                
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return [
            'title' => 'Установка правильных ответов для картины ' . $model->image_original_author . ' ' . $model->image_original_title,
            'content' => $this->renderAjax('not-set', [
                'model' => $model,
                'data' => $model->getAnswers(),
            ]),
        ];
    }
    
    /**
     * Посчитать ответ верным
     * @param integer $idAnswer
     * @return string
     */
    public function actionSetAnswerYes($idAnswer)
    {
        $model = $this->findModelResult($idAnswer);
        $model->is_right = 1;
        if ($model->save()) {
            return 'OK';
        }
        return 'Не удалось сохранить, попробуйте позже!';        
    }
    
    /**
     * Посчитать ответ не верным
     * @param integer $idAnswer
     * @return string
     */
    public function actionSetAnswerNo($idAnswer)
    {
        $model = $this->findModelResult($idAnswer);
        $model->is_right = 0;
        if ($model->save()) {
            return 'OK';
        }
        return 'Не удалось сохранить, попробуйте позже!';        
    }
    
    /**
     * Статистика
     * @param integer $id
     * @return string
     */
    public function actionStatistic($id)
    {        
        $model = $this->findModel($id);
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return [
            'title' => 'Статистика',
            'content' => $this->renderAjax('statistic', [
                'model' => $model,
            ]),
        ];
    }
    
    /**
     * Информация по ответам
     * @param string $login
     * @return string
     */
    public function actionWinner($login)
    {
        $query = (new Query())
            ->select('main.image_original_author, main.image_original_title, res.date_create')
            ->from('{{%contest_arts_results}} res')
            ->rightJoin('{{%contest_arts}} main', 'res.id_arts=main.id')
            ->where([
                'res.is_right' => 1,
                'res.author' => $login,
            ])
            ->andWhere('main.date_show_2 <= cast(getdate() as date)')
            ->all();
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return [
            'title' => 'Правильные ответы',
            'content' => $this->renderAjax('winner', [
                'data' => $query,
            ]),
        ];
    }
    
    /**
     * Голосование за по номинациям
     * @param integer $id
     * @param integer $type
     */
    public function actionVote($id)
    {               
        $model = $this->findModel($id);
        if (($allowMessage = $model->isAllowVote())) {
            throw new HttpException(599, $allowMessage);
        }
        $rating_real_art = Yii::$app->request->post('rating_real_art') ?? 0;
        $rating_original_name = Yii::$app->request->post('rating_original_name') ?? 0;
        
        Yii::$app->db->createCommand()
            ->insert('{{%contest_arts_vote}}', [
                'id_contest_arts' => $id,
                'author' => Yii::$app->user->identity->username,
                'rating_original_name' => $rating_original_name,
                'rating_real_art' => $rating_real_art,
            ])
            ->execute();        
    }

    /**
     * Finds the ContestArts model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ContestArts the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ContestArts::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    
    /**
     * @param integer $id
     * @return ContestArtsResults
     * @throws NotFoundHttpException
     */
    protected function findModelResult($id)
    {
        if (($model = ContestArtsResults::findOne($id)) !== null) {
            return $model;
        }      
        throw new NotFoundHttpException('The requested page does not exist.');
    }
    
    /**
     * Задание (картина) на сегодня
     * @return ContestArts|null
     */
    protected function findModelToday()
    {
        //return ContestArts::find()->where('convert(varchar,date_show,104) = convert(varchar,getdate(),104)')->one();
        return ContestArts::find()->where('getdate() between date_show and date_show_2')->all();
    }
    
    /**
     * Все прошлые задания (картины)
     * @return ContestArts[]|null
     */
    protected function findLastArts()
    {
        return ContestArts::find()->where('date_show_2 <= cast(getdate() as date)')->all();
    }
        
    
}
