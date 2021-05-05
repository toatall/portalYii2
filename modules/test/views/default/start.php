<?php
/* @var $this yii\web\View */
/* @var $model \app\modules\test\models\Test */
/* @var $testData array */
/* @var $attempts array */

use yii\helpers\Url;
use yii\helpers\Html;
use app\modules\test\models\TestQuestion;

/*
 * Логика в JS:
 * Сделать Wizard
 * Переключение с помощью вкладок или кнопками вперед и назад
 * В конце кнопка Готово
 *
 *
 * Изначальные данные - массив вопросов и ответов
 * [
 *    [
 *       'id' - идентификатор
 *       'name' - наименование вопроса
 *       'type' - тип вопроса (0 - radio, 1 - checkbox)
 *       'file' - файл
 *       'answers' - ответы
 *          [
 *              [
 *                  'id' - идентификатор
 *                  'name' - наименование
 *                  'file' - файл
 *              ]
 *          ]
 *    ]
 * ]
 *
 * В результат нужно отправить post:
 *
 * [
 *      'questions' => [], // какие вопросы были выбраны
 *      'answers' => [
 *          'id' => [ // идентификатор вопроса
 *              [] => [] | "" // ответ или ответы
 *          ]
 *      ]
 * ]
 *
 */
//$renderHelper = new RenderFileHelper();
$seconds = $model->getTimeLimitSeconds();
?>
<div id="test-div"></div>
<?= Html::beginForm('', 'post', ['id'=>'test_form']) ?>
    <?php if ($seconds > 0): ?>
        <div>
            <h3 class="label label-default" style="font-size: x-large">Осталось: <span id="time-seconds">00:00</span></h3>
        </div>
    <?php endif; ?>
    <input type="hidden" name="Test[id]" value="<?= $model->id ?>" />
    <ul class="pagination" id="tabs-test" role="tablist">
        <?php for ($i=0; $i<count($testData); $i++): ?>
            <li role="presentation"<?= $i==0 ? ' class="active"' : '' ?>>
                <a href="#question_<?= $testData[$i]['id'] ?>" aria-controls="question_<?= $testData[$i]['id'] ?>" role="tab" data-toggle="tab">
                    <?= '#' . ($i+1) ?>
                </a>
            </li>
        <?php endfor; ?>
    </ul>

    <div class="tab-content">
        <?php for ($i=0; $i<count($testData); $i++): ?>
            <input type="hidden" name="Test[questions][]" value="<?= $testData[$i]['id'] ?>" />
            <div role="tabpanel" class="tab-pane <?= $i==0 ? ' in active' : '' ?>" id="question_<?= $testData[$i]['id'] ?>">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <?php
                        // Название вопроса
                        echo $testData[$i]['name'] . '<br />';
                        // Файл
                        if (!empty($testData[$i]['file'])) {
                            echo Html::a(basename($testData[$i]['file']), $testData[$i]['file'], ['target'=>'_blank']);
                        }
                        ?>
                    </div>
                    <div class="panel-body">
                        <?php
                        // вывести ответы
                        foreach ($testData[$i]['answers'] as $data) {
                            ?>
                            <div class="form-group">
                                <?php

                                if ($testData[$i]['type'] == TestQuestion::TYPE_QUESTION_RADIO) {
                                    ?>
                                    <div class="radio">
                                        <label>
                                            <input type="radio" name="Test[answers][<?= $testData[$i]['id'] ?>]" value="<?= $data['id'] ?>" />
                                            <?= $data['name'] ?><br />
                                            <?php
                                            // Файл
                                            if ($testData[$i]['file'] != '') {
                                                echo Html::a(basename($data['file']), $data['file'], ['target'=>'_blank']);
                                            }
                                            ?>
                                        </label>
                                    </div>
                                    <?php
                                }
                                elseif ($testData[$i]['type'] == TestQuestion::TYPE_QUESTION_CHECK) {
                                    ?>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="Test[answers][<?= $testData[$i]['id'] ?>][]" value="<?= $data['id'] ?>" />
                                            <?= $data['name'] ?>
                                        </label>
                                    </div>
                                    <?php
                                }
                                else {
                                    echo 'Тип вопроса не опознан!';
                                }

                                ?>
                            </div>
                            <?php
                        };
                        ?>
                    </div>
                </div>

                <div class="btn-group">
                    <?php
                    // кнопка назад
                    if ($i > 0) {
                        echo Html::button('<i class="fas fa-arrow-circle-left"></i> Назад', ['class'=>'btn btn-primary btn-previous']);
                    }
                    // кнопка вперед
                    if ($i < count($testData)-1) {
                        echo Html::button('<i class="fas fa-arrow-circle-right"></i> Далее', ['class'=>'btn btn-primary btn-next']);
                    }
                    // кнопка назад
                    if ($i == count($testData)-1) {
                        echo Html::submitButton('<i class="fas fa-share-square"></i> Завершить', ['class'=>'btn btn-success']);
                    }
                    ?>
                </div>
            </div>
        <?php endfor; ?>
    </div>

<?= Html::endForm(); ?>

<script type="text/javascript">

    $(document).ready(function () {

        var autoEnd = false;

        $('.btn-previous').on('click', function () {
            $('#tabs-test > .active').prev('li').find('a').trigger('click');
        });

        $('.btn-next').on('click', function () {
            $('#tabs-test > .active').next('li').find('a').trigger('click');
        });

        $('form#test_form').submit(function (e) {

            if (!autoEnd && !confirm('Вы уверены, что хотите завершить?')) {
                return false;
            }
            var serialize = $('form#test_form').serialize();
            var action = $('form#test_form').attr('action');

            var modalBody = $(modalViewer.modalBody);
            modalBody.html('<img src="/img/loader_fb.gif" width="48" />');
            $.ajax({
                type: 'post',
                url: action,
                data: serialize
            })
            .done(function (data) {
                modalBody.html(data.content);
            })
            .fail(function (jqXHR) {
                modalBody.html('<div class="alert alert-danger">' + jqXHR.status + ' ' + jqXHR.statusText + '</div>');
            });

            e.preventDefault();

            return false;

        });

        <?php if ($seconds > 0): ?>
        // timer
        var timer;
        var countSec = <?= $seconds ?>;

        function countDown() {
            var dt = new Date(0);
            dt.setSeconds(countSec);
            $('#time-seconds').html(dt.toISOString().substr(11, 8));
            countSec--;
            if (countSec <= 0) {
                // click finish
                clearTimeout(timer);
                autoEnd = true;
                $('#test_form').submit();
            }
            if (!$(modalViewer.modalId).is('.in')) {
                clearTimeout(timer);
            }
        }

        timer = setInterval(countDown, 1000);

        <?php endif; ?>

    });
</script>