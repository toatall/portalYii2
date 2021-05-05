<?php
/* @var $this yii\web\View */
/* @var $resultQuery array */

use yii\helpers\Url;
?>
<table class="table table-bordered table-striped">
    <tr>
        <th>Логин</th>
        <th>ФИО</th>
        <th>Дата</th>
        <th>Количество вопросов</th>
        <th>Правильно отвеченых</th>
        <th>% правильных ответов</th>
        <th></th>
    </tr>
    <?php foreach ($resultQuery as $item):
        $id = md5($item['username'] . $item['id']);
        ?>
        <tr>
            <td><?= $item['username'] ?></td>
            <td><?= $item['fio'] ?></td>
            <td><?= Yii::$app->formatter->asDatetime($item['date_create']) ?></td>
            <td><?= $item['count_questions'] ?></td>
            <td><?= $item['count_right'] ?></td>
            <td><?= round(($item['count_right']/$item['count_questions'])*100) ?></td>
            <th>
                <button class="btn btn-default show-detail"
                    data-url="<?= Url::to(['/test/default/statistic-user-detail', 'idTestResult'=>$item['id'], 'userLogin'=>$item['username']]) ?>"
                    data-toggle="#container_result_<?= $id ?>">Подробнее</button>
            </th>
        </tr>
        <tr>
            <td colspan="6">
                <div id="container_result_<?= $id ?>" style="display: none;"></div>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
<script type="text/javascript">
    $('.show-detail').on('click', function () {
        if ($($(this).attr('data-toggle')).is(':visible')) {
            $($(this).attr('data-toggle')).hide();
            return;
        }

        let cont = $($(this).attr('data-toggle'));
        cont.html('<i class="fas fa-spin fa-spinner"></i>');
        $.get($(this).data('url'))
        .done(function(data) {
            cont.html(data);
        })
        .fail(function (jqXHR) {
            cont.html('<div class="alert alert-danger">' + jqXHR.status + ' ' + jqXHR.statusText + '</div>');
        });
        $($(this).attr('data-toggle')).show();
    });
</script>