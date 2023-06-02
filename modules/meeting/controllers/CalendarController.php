<?php

namespace app\modules\meeting\controllers;

use app\components\Controller;
use app\modules\meeting\models\search\MeetingFindAll;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\StringHelper;
use yii\helpers\Url;

/**
 * Calendar controller for the `conference` module
 */
class CalendarController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index', 'locations', 'data'],
                        'roles' => ['@'],
                    ],                    
                ],
            ],
        ];
    }    

    
    /**
     * Renders the calendar for the module
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Renders the calendar by locations
     * @return string
     */
    public function actionLocations()
    {
        return $this->render('locations');
    }

    /**
     * @param string $start
     * @param string $end
     * @param string $filterType
     * @return array
     */
    public function actionData($start, $end, $filterType = '')
    {      
        $model = new MeetingFindAll();
        $findModel = $model->findAllMeeting($start, $end, $filterType);
        
        $events = [];
        foreach($findModel as $item) {
            
            $accessViewerAllFields = $item->modelClass()::isViewerAllFields();
            
            $title = $item->getTitle(!$accessViewerAllFields);

            $events[] = [
                'id' => $item->id,
                'title' => StringHelper::truncateWords($title, 5),
                'start' => date('c', $item->date_start),
                'end' => date('c', $item->date_start + $item->duration),
                'duration' => $item->duration,
                'url' => Url::to(['/meeting/' . $item->modelClass()::getType() . '/view', 'id' => $item->id]),
                'extendedProps' => [
                    'fullTitle' => $accessViewerAllFields ? $item->theme : '',
                    'description' => $item->getDescription(),
                ],               
                'className' => 'text-white border-0 p-1 ' /*. ($item->isFinished() ? 'text-muted ' : '')*/ . $item::getColor(),
                'resourceIds' => (array)$item->place,

            ];
        }
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $events;
    }
    

}
