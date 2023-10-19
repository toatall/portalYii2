<?php

namespace app\widgets;

use app\models\AutomationRoutine;
use kartik\growl\Growl;
use Yii;
use yii\bootstrap5\Widget;
use yii\helpers\Url;

/**
 *
 * 
 * @author toatall
 */

class AutomationRoutineNotifyWidget extends Widget
{    
    /**
     * Сообщение уведомления
     * @var string
     */
    private $message;

    /**
     * Идентификатор модели, которую удалось найти, 
     * для дальнейшего перехода к просмотру (оценке, загрузке)
     * @var int
     */
    private $idModelAutomationRoutine;

    /**
     * Текст кнопки для перенаправления
     * @var string
     */
    private $btnRedirectTitle;

    /**
     * Текст кнопки для отмены
     * @var string
     */
    private $btnRejectTitle;

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();
    }

    /**
     * {@inheritdoc}
     */
    public function run()
    {
        if (Yii::$app->request->cookies->has(AutomationRoutine::cookieKeyNotify())) {
            return;
        }
        if (Yii::$app->user->identity == null) {
            return;
        }
        
        if ($this->checkNotify()) {
            $id = 'growl-automation-routine';
            echo Growl::widget([
                'id' => $id,
                'type' => Growl::TYPE_GROWL,
                'title' => '<i class="fas fa-robot"></i> Автоматизация рутиных операций',                
                'titleOptions' => [
                    'class' => 'fs-5 fw-bold'
                ],
                'showSeparator' => true,
                'body' => $this->render('automation-rouine-notify-body', [
                    'widgetId' => $id,
                    'message' => $this->message,
                    'idModelAutomationRoutine' => $this->idModelAutomationRoutine,
                    'btnRedirectTitle' => $this->btnRedirectTitle,
                    'btnRejectTitle' => $this->btnRejectTitle,
                ]), 
                'delay' => 3000,
                'closeButton' => null,
                'pluginOptions' => [
                    'delay' => 0,
                    'placement' => [
                        'from' => 'bottom',
                    ],
                ],
            ]);
            $urlRedirect = Url::to(['/automation-routine/view', 'id'=>$this->idModelAutomationRoutine]);
            $urlClose = Url::to(['/automation-routine/close']);
            $urlReject = Url::to(['/automation-routine/reject', 'id'=>$this->idModelAutomationRoutine]);
            $this->view->registerJs(<<<JS
                
                // кнопка перенаправления
                $(document).on('click', '#automation-routine-notify-btn-redirect', function(){
                    $(this).prop('disabled', true)
                    $.post('$urlClose')
                    .always(function(){
                        window.location.href = '$urlRedirect'
                    })
                })

                // кнопка отказа
                $(document).on('click', '#automation-routine-notify-btn-reject', function(){
                    const alert = bootstrap.Alert.getOrCreateInstance('#$id')
                    alert.close()
                    $.post('$urlReject')
                })
                
                // кнопка закрыть
                $(document).on('click', '#automation-routine-notify-btn-close', function(){
                    const alert = bootstrap.Alert.getOrCreateInstance('#$id')
                    alert.close()
                    $.post('$urlClose')
                })

            JS, \yii\web\View::POS_READY);
        }
    }

    /**
     * Дней активности, за которое выполняется поиск
     * @return int
     */
    protected function activityDays()
    {
        return 7;
    }

    /**
     * Проверка информации для уведомления
     * @return bool
     */
    protected function checkNotify()
    {
        // 1 проверка по количествам загрузок программного модуля
        // которые данный пользователь делал в последние 14 дней 
        $query1 = $this->getRecords("
            select top 1 t.[[id]], t.[[title]], count(distinct t_d.[[id]]) [count_download] from {{%automation_routine}} t
                left join {{%automation_routine_downloads}} t_d on t.id = t_d.[[id_automation_routine]] 
                left join {{%automation_routine_rate}} t_r on t.[[id]] = t_r.[[id_automation_routine]] and t_r.[[author]] = :author1  
                left join {{%automation_routine_feedback}} t_f on t.[[id]] = t_f.[[id_automation_routine]] and t_f.[[author]] = :author2 and result = :result
            where t_d.[[author]] = :author3 and t_r.[[id]] is null and t_f.[[id]] is null and DATEDIFF(day, t_d.[[date_create]], getdate()) < :days
            group by t.[[id]], t.[[title]]
            order by count(distinct t_d.[[id]]) desc        
        ", [
            ':author1' => Yii::$app->user->identity->username, 
            ':author2' => Yii::$app->user->identity->username, 
            ':author3' => Yii::$app->user->identity->username, 
            ':days' => $this->activityDays(),
            ':result' => 'reject',
        ]);
        if ($query1) {
            $this->message = sprintf('Вы недавно загружали программный модуль "%s". <br />Оцените, пожалуйста, его работу.', $query1['title']);
            $this->idModelAutomationRoutine = $query1['id'];
            $this->btnRedirectTitle = '<i class="far fa-star"></i> Оценить';
            $this->btnRejectTitle = '<i class="far fa-times-circle"></i> Не хочу оценивать';
            return true;
        }

        // 2 поиск по просмотрам
        $query2 = $this->getRecords("
            select top 1 t.[[id]], t.[[title]], count(distinct his_det.[[id]]) from {{%automation_routine}} t
                left join {{%history}} his on his.[[url]] = '/automation-routine/view?id=' + cast(t.[[id]] as nvarchar)
                left join {{%history_detail}} his_det on his_det.[[id_history]] = his.[[id]] 
                left join {{%automation_routine_rate}} t_r on t.[[id]] = t_r.[[id_automation_routine]] and t_r.[[author]] = :author1 
                left join {{%automation_routine_feedback}} t_f on t.[[id]] = t_f.[[id_automation_routine]] and t_f.[[author]] = :author2 and result = :result       
                left join {{%automation_routine_downloads}} t_d on t.id = t_d.[[id_automation_routine]] and t_d.[[author]] = :author3
            where his_det.author = :author4 and t_r.[[id]] is null and t_f.[[id]] is null and t_d.[[id]] is null
                and DATEDIFF(day, DATEADD(s, his_det.[[date_create]], '01-01-1970'), getdate()) < :days
            group by t.[[id]], t.[[title]]
            order by count(distinct his_det.[[id]]) desc
        ", [
            ':author1' => Yii::$app->user->identity->username, 
            ':author2' => Yii::$app->user->identity->username, 
            ':author3' => Yii::$app->user->identity->username, 
            ':author4' => Yii::$app->user->identity->username, 
            ':days' => $this->activityDays(),
            ':result' => 'reject',
        ]);
        if ($query2) {
            $this->message = sprintf('Вы недавно просматривали программный модуль "%s", но не загрузили его.<br />Не желаете его загрузить сейчас?.', $query2['title']);
            $this->idModelAutomationRoutine = $query2['id'];
            $this->btnRedirectTitle = '<i class="fas fa-download"></i> Загрузить';
            $this->btnRejectTitle = '<i class="far fa-times-circle"></i> Не хочу загружать';
            return true;
        }
        
        // сохранить куки, чтобы повторно не выполнялись скрипты
        AutomationRoutine::cookieSave();
        return false;
    }
    
    /**
     * @param string $query
     * @param array $params
     * @return array
     */
    private function getRecords($query, $params = [])
    {
        return Yii::$app->db->createCommand($query, $params)
            ->queryOne();
    }

}