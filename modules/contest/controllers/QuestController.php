<?php

namespace app\modules\contest\controllers;

use app\modules\contest\models\quest\Crossword;
use app\modules\contest\models\quest\Linked;
use app\modules\contest\models\quest\MarkText;
use app\modules\contest\models\quest\Quest;
use app\modules\contest\models\quest\Questions;
use app\modules\contest\models\quest\Tasks;
use app\modules\contest\models\quest\TaxGroup;
use Yii;
use yii\db\Query;
use app\components\Controller;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\web\Response;

/**
 * Quest controller for the `admin` module
 */
class QuestController extends Controller
{

    public $layout = 'quest';
    
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
     * Главная страница
     * @return string
     */
    public function actionIndex()
    {
        $results = $this->checkResults();

        $links = [
            [
                'url' => Url::to(['/contest/quest/step01']),
                'title' => "<div class='text-center'><strong>Станция «Поисковая»</strong><br />Установите соответствие между терминами и определениями<br /><b class='text-warning'>10 заданий – 5 минут</b></div>",
                'finish' => isset($results[1]),
            ],
            [
                'url' => Url::to(['/contest/quest/step02']),
                'title' => "<strong>Станция «Занимательная»</strong><br />Решите кроссворд<br /><b class='text-warning'>10 заданий – 5 минут</b>",
                'finish' => isset($results[2]),
            ],
            [
                'url' => Url::to(['/contest/quest/step03']),
                'title' => "<strong>Станция «Налоговая полиция»</strong><br />Найдите ошибки в тексте<br /><b class='text-warning'>10 заданий – 5 минут</b>",
                'finish' => isset($results[3]),
            ],
            [
                'url' => Url::to(['/contest/quest/step04']),
                'title' => "<strong>Станция «Налоговое ориентирование»</strong><br />Распределите виды налогов по группам<br /><b class='text-warning'>10 заданий – 5 минут</b>",
                'finish' => isset($results[4]),
            ],
            [
                'url' => Url::to(['/contest/quest/step05']),
                'title' => "<strong>Станция «Любознательная»</strong><br />Викторина<br /><b class='text-warning'>10 заданий – 5 минут</b>",
                'finish' => isset($results[5]),
            ],
            [
                'url' => Url::to(['/contest/quest/step06']),
                'title' => "<strong>Станция «Задачкино»</strong><br />Решите задачи на вычисление налогов<br /><b class='text-warning'>10 заданий – 5 минут</b>",
                'finish' => isset($results[6]),
            ],            
        ];

        return $this->render('index', [
            'links' => $links,
            'results' => $this->checkResults(),
        ]);
    }

    /**
     * Этапы выполненные текущим пользователем
     * @see QuestionController::actionIndex
     * @return array|null
     */
    protected function checkResults()
    {
        return (new Query())
            ->from('{{%contest_quest}}')
            ->where(['username' => Yii::$app->user->identity->username])
            ->indexBy('step')
            ->all();
    }

    /**
     * Этап 1 (связи между блоками)
     * @return string
     */
    public function actionStep01()
    {
        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post('result'); 
            if (!is_array($post)) {
                $post = [];
            }
            $balls = Linked::checkResult($post);
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'balls' => $balls,
                'result' => $post,
            ];
        }

        $result = Quest::findResult(1);
        
        $listA = Linked::getListA();
        $listB = Linked::getListB();
        return $this->render('step01', [
            'listA' => $listA,
            'listB' => $listB,
            'result' => $result,
            'existingLinks' => json_encode(isset($result['data']) ? unserialize($result['data']) : []),
        ]);
    }

    /**
     * Этап 2 (кроссворд)
     * @return string
     */
    public function actionStep02()
    {        
        $crossword = Crossword::generateCrossword();

        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post('answer');
            $check = Crossword::checkResult($crossword, $post);           
            Crossword::saveResult($post, $check);
        }

        // поиск результатов
        $result = Quest::findResult(2);
        $resultData = isset($result['data']) ? unserialize($result['data']) : [];
        
        return $this->render('step02', [            
            'data' => $crossword,
            'result' => $result,
            'resultData' => $resultData,
            'words' => Crossword::getWords(),
            'savedData' => isset($resultData['post']) ? $resultData['post'] : [],
        ]);
    }

    /**
     * Этап 3 (поиск ошибок в тексте)
     * @return string
     */
    public function actionStep03()
    {              
        $post = [];
        if (Yii::$app->request->isPost) {            
            $post = Yii::$app->request->post('result');
            $balls = MarkText::checkResult($post);
            Quest::saveResult(3, $balls, $post);            
        }

        $result = Quest::findResult(3);
        $data = isset($result['data']) ? unserialize($result['data']) : [];

        return $this->render('step03', [
            'text' => MarkText::getText($data),
            'result' => $result,            
        ]);
    }

    /**
     * Этап 4 (перемещение по группам)
     * @return string
     */
    public function actionStep04()
    {
        if (Yii::$app->request->isPost) {            
            $post = Yii::$app->request->post();
            $balls = TaxGroup::checkResult($post);
            Quest::saveResult(4, $balls, $post);
        }

        $result = Quest::findResult(4);
        $groups = TaxGroup::getData($result);
        
        return $this->render('step04', [
            'groups' => $groups,
            'result' => $result,
        ]);
    }

    /**
     * Этап 5. Ответы на вопросы
     * @return string
     */
    public function actionStep05()
    {
        if (Yii::$app->request->isPost) {            
            $post = Yii::$app->request->post();
            $balls = Questions::checkResult($post);
            Quest::saveResult(5, $balls, $post);
        }

        $result = Quest::findResult(5);
        $data = isset($result['data']) ? unserialize($result['data']) : [];
        $questions = Questions::getData();

        return $this->render('step05', [
            'questions' => $questions,
            'result' => $result,
            'data' => $data,
        ]);
    }

    /**
     * Этап 6 (задачи)
     * @return string
     */
    public function actionStep06()
    {
        if (Yii::$app->request->isPost) {            
            $post = Yii::$app->request->post();
            $balls = Tasks::checkResult($post);
            Quest::saveResult(6, $balls, $post);
        }

        $result = Quest::findResult(6);
        $data = isset($result['data']) ? unserialize($result['data']) : [];
        $questions = Tasks::getData();

        return $this->render('step06', [
            'questions' => $questions,
            'result' => $result,
            'data' => $data,
        ]);
    }
    
}
