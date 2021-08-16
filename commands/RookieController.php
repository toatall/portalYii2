<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use Yii;
use yii\console\Controller;
use yii\console\ExitCode;

/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class RookieController extends Controller
{

    /**
     * Наполнение таблицы названиями фотографий
     */
    public function actionPhotohunterSeek()
    {
        $photoPath = '/public/content/rookie/photohunter/photos';

        $db = Yii::$app->db->createCommand();
        // By departments:
        // - 02
        $db->insert('{{%rookie_photohunter_photos}}', [
            'code_no' => '8600',
            'id_department' => 2, 
            'image' => "{$photoPath}/02.jpg", 
            'thumb' => "{$photoPath}/02_thumb.jpg", 
            'nomination' => 'Смотрите! Новый вид спорта',
            'title' => null, 
            'description' => 'Общий отдел', 
            'date_create' => Yii::$app->formatter->asDatetime('now'),
        ])->execute();
        // - 03
        $db->insert('{{%rookie_photohunter_photos}}', [
            'code_no' => '8600',
            'id_department' => 3, 
            'image' => "{$photoPath}/03.jpeg", 
            'thumb' => "{$photoPath}/03_thumb.jpeg", 
            'nomination' => 'Маска',
            'title' => null, 
            'description' => 'Отдел кадров', 
            'date_create' => Yii::$app->formatter->asDatetime('now'),
        ])->execute();
        // - 04
        $db->insert('{{%rookie_photohunter_photos}}', [
            'code_no' => '8600',
            'id_department' => 4, 
            'image' => "{$photoPath}/04.jpg", 
            'thumb' => "{$photoPath}/04_thumb.jpg", 
            'nomination' => 'Смотрите! Новый вид спорта',
            'title' => null, 
            'description' => 'Отдел обеспечения', 
            'date_create' => Yii::$app->formatter->asDatetime('now'),
        ])->execute();
        // - 05
        $db->insert('{{%rookie_photohunter_photos}}', [
            'code_no' => '8600',
            'id_department' => 5, 
            'image' => "{$photoPath}/05.jpg", 
            'thumb' => "{$photoPath}/05_thumb.jpg", 
            'nomination' => 'Маска',
            'title' => 'Охотник за коронавирусом!', 
            'description' => 'Отдел безопасности', 
            'date_create' => Yii::$app->formatter->asDatetime('now'),
        ])->execute();
        // - 07
        $db->insert('{{%rookie_photohunter_photos}}', [
            'code_no' => '8600',
            'id_department' => 7, 
            'image' => "{$photoPath}/07.jpg", 
            'thumb' => "{$photoPath}/07_thumb.jpg", 
            'nomination' => 'Маска',
            'title' => 'За едой втихушку в уголке сидела, маска не мешала - палочками ела', 
            'description' => 'Отдел досудебного урегулирования налоговых споров', 
            'date_create' => Yii::$app->formatter->asDatetime('now'),
        ])->execute();
        // - 08
        $db->insert('{{%rookie_photohunter_photos}}', [
            'code_no' => '8600',
            'id_department' => 8, 
            'image' => "{$photoPath}/08.jpg", 
            'thumb' => "{$photoPath}/08_thumb.jpg", 
            'nomination' => 'Смотрите! Новый вид спорта',
            'title' => 'Офисные гонки на креслах', 
            'description' => 'Отдел информационных технологий', 
            'date_create' => Yii::$app->formatter->asDatetime('now'),
        ])->execute();
        // - 09
        $db->insert('{{%rookie_photohunter_photos}}', [
            'code_no' => '8600',
            'id_department' => 9, 
            'image' => "{$photoPath}/09.jpg", 
            'thumb' => "{$photoPath}/09_thumb.jpg", 
            'nomination' => 'Время ловить улыбки',
            'title' => 'Утро в деревне', 
            'description' => 'Отдел обеспечения процедур банкротства', 
            'date_create' => Yii::$app->formatter->asDatetime('now'),
        ])->execute();
        // - 10
        $db->insert('{{%rookie_photohunter_photos}}', [
            'code_no' => '8600',
            'id_department' => 10, 
            'image' => "{$photoPath}/10.jpg", 
            'thumb' => "{$photoPath}/10_thumb.jpg", 
            'nomination' => 'В тесноте, да не в обиде',
            'title' => null, 
            'description' => 'Отдел регистрации и учета налогоплательщиков', 
            'date_create' => Yii::$app->formatter->asDatetime('now'),
        ])->execute();
        // - 11
        $db->insert('{{%rookie_photohunter_photos}}', [
            'code_no' => '8600',
            'id_department' => 11, 
            'image' => "{$photoPath}/11.jpeg", 
            'thumb' => "{$photoPath}/11_thumb.jpeg", 
            'nomination' => 'Тяжело в учении – легко в бою!',
            'title' => null, 
            'description' => 'Аналитический отдел', 
            'date_create' => Yii::$app->formatter->asDatetime('now'),
        ])->execute();
        // - 13
        $db->insert('{{%rookie_photohunter_photos}}', [
            'code_no' => '8600',
            'id_department' => 13, 
            'image' => "{$photoPath}/13.jpg", 
            'thumb' => "{$photoPath}/13_thumb.jpg", 
            'nomination' => 'В тесноте, да не в обиде',
            'title' => 'аля Сталлоне согласовывает перерасчет', 
            'description' => 'Контрольный отдел №1', 
            'date_create' => Yii::$app->formatter->asDatetime('now'),
        ])->execute();
        // - 14
        $db->insert('{{%rookie_photohunter_photos}}', [
            'code_no' => '8600',
            'id_department' => 14, 
            'image' => "{$photoPath}/14.jpg", 
            'thumb' => "{$photoPath}/14_thumb.jpg", 
            'nomination' => 'Маска',
            'title' => null,
            'description' => 'Отдел налогообложения имущества и доходов физических лиц и администрирования страховых взносов', 
            'date_create' => Yii::$app->formatter->asDatetime('now'),
        ])->execute();
        // - 16
        $db->insert('{{%rookie_photohunter_photos}}', [
            'code_no' => '8600',
            'id_department' => 16, 
            'image' => "{$photoPath}/16.jpeg", 
            'thumb' => "{$photoPath}/16_thumb.jpeg", 
            'nomination' => 'Тяжело в учении – легко в бою!',
            'title' => null,
            'description' => 'Отдел камерального контроля', 
            'date_create' => Yii::$app->formatter->asDatetime('now'),
        ])->execute();
        // - 18
        $db->insert('{{%rookie_photohunter_photos}}', [
            'code_no' => '8600',
            'id_department' => 18, 
            'image' => "{$photoPath}/18.jpg", 
            'thumb' => "{$photoPath}/18_thumb.jpg", 
            'nomination' => 'Время ловить улыбки',
            'title' => 'Если будешь улыбаться, все мечты начнут сбываться',
            'description' => 'Отдел контроля налоговых органов', 
            'date_create' => Yii::$app->formatter->asDatetime('now'),
        ])->execute();
        // - 19
        $db->insert('{{%rookie_photohunter_photos}}', [
            'code_no' => '8600',
            'id_department' => 19, 
            'image' => "{$photoPath}/19.jpg", 
            'thumb' => "{$photoPath}/19_thumb.jpg", 
            'nomination' => 'Тяжело в учении – легко в бою!',
            'title' => null,
            'description' => 'Контрольный отдел №2', 
            'date_create' => Yii::$app->formatter->asDatetime('now'),
        ])->execute();
        // - 20
        $db->insert('{{%rookie_photohunter_photos}}', [
            'code_no' => '8600',
            'id_department' => 20, 
            'image' => "{$photoPath}/20.jpg", 
            'thumb' => "{$photoPath}/20_thumb.jpg", 
            'nomination' => 'Смотрите! Новый вид спорта',
            'title' => 'Формально-легитимный футбол',
            'description' => 'Контрольно-аналитический отдел', 
            'date_create' => Yii::$app->formatter->asDatetime('now'),
        ])->execute();
        // - 22
        $db->insert('{{%rookie_photohunter_photos}}', [
            'code_no' => '8600',
            'id_department' => 22, 
            'image' => "{$photoPath}/22.jpg", 
            'thumb' => "{$photoPath}/22_thumb.jpg", 
            'nomination' => 'В тесноте, да не в обиде',
            'title' => null,
            'description' => 'Отдел информационной безопасности',
            'date_create' => Yii::$app->formatter->asDatetime('now'),
        ])->execute();

        // FKU
        $db->insert('{{%rookie_photohunter_photos}}', [
            'code_no' => 'n8600',
            'id_department' => null, 
            'image' => "{$photoPath}/n8600.jpg", 
            'thumb' => "{$photoPath}/n8600_thumb.jpg", 
            'nomination' => 'В тесноте, да не в обиде',
            'title' => 'Весело и дружно ФКУ',
            'description' => 'Филиал ФКУ «Налог-Сервис» в Ханты-Мансийском автономном округе – Югре',
            'date_create' => Yii::$app->formatter->asDatetime('now'),
        ])->execute();

        return ExitCode::OK;
    }

    /**
     * Удаление фотографий
     * @param int $id
     * @return int Exit code
     */
    public function actionPhotohunterRemove($id=null)
    {
        $command = Yii::$app->db->createCommand();
        $command->delete('{{%rookie_photohunter_photos}}', ($id==null ? ['id'=>$id] : ''))->execute();
        return ExitCode::OK;
    }


    /**
     * This command echoes what you have entered as the message.
     * @param string $message the message to be echoed.
     * @return int Exit code
     */
    public function actionIndex($message = 'hello world')
    {
        echo $message . "\n";
        return ExitCode::OK;
    }
}
