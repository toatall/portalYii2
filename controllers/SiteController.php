<?php

namespace app\controllers;

use app\models\HallFame;
use app\models\LoginLdap;
use app\models\Telephone;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use app\components\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use yii\helpers\Url;

class SiteController extends Controller
{
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
                        'actions' => ['logout', 'index', 'telephone', 'hall-fame', 'save-user-agent-info', 'sport'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['login', 'index'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['error'],
                        'allow' => true,
                        'roles' => ['@', '?'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {        
        return $this->render('index');
    }

    /**
     * Login action.
     *
     * @return Response|string
     * @throws \yii\base\Exception
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $this->windowsAuthenticate();
        
        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {           
            return $this->redirect('save-user-agent-info');
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Переадресация, если включена windows-аутентефикация
     */
    protected function windowsAuthenticate()
    {
        if (!Yii::$app->params['user']['useWindowsAuthenticate'] ?? false) {
            return;
        }
        return $this->goHome();
    }

    /**
     * Сохранение информации о разрешении экрана 
     * и строки агента браузера пользователя
     *
     * @param int $width
     * @param int $height
     * @return string|Response
     */
    public function actionSaveUserAgentInfo($forward = false, $width = null, $height = null)
    {
        if ($forward) {            
            Yii::$app->user->identity->saveInformation((int)$width, (int)$height);
            if (strpos(Yii::$app->request->referrer, Yii::$app->requestedRoute) === false) {
                $url = Yii::$app->request->referrer;
            }
            else {
                $url = Yii::$app->homeUrl;
            }            
            return $this->redirect($url);
        }
        return $this->render('save-user-agnet-info');
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }    

    /**
     * Доска почета
     * @param null $year
     * @return string
     */
    public function actionHallFame($year = null)
    {
        $model = new HallFame($year);
        return $this->render('hallFame', [
            'model' => $model,
        ]);
    }

    /**
     * Телефоны
     * @return string
     */
    public function actionTelephone()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Telephone::find()->orderBy('id_organization asc'),
        ]);

        return $this->render('telephone', [
            'dataProvider' => $dataProvider,
        ]);
    }    

    /**
     * Спорт
     * @return mixed
     */
    public function actionSport()
    {
        return $this->render('/static/sport/index');
    }


}
