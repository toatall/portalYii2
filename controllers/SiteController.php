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
                        'actions' => ['logout', 'index', 'telephone', 'hall-fame', 'screen-resolution'],
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
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['login']);
        }
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

        // ldap аутентификация
        if (Yii::$app->params['user']['useLdapAuthenticated']) {
            $model = new LoginLdap();
            if (!$model->login()) {
                return $this->render('loginLdapError');
            }
            // return $this->goBack();
            return $this->render('save-user-info');
        }

        // обычная аутентификация
        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            // return $this->goBack();
            return $this->render('save-user-info');
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
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
     * @param int $width
     * @param int $height
     * @return string
     */
    public function actionScreenResolution($width, $height)
    {
        if (!\Yii::$app->user->isGuest) {
            \Yii::$app->user->identity->saveInformation($width, $height);
        }
        return $this->goBack();
    }


}
