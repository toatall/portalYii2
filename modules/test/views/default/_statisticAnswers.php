<?php
/* @var $this yii\web\View */
/* @var $resultQuery array */

use yii\helpers\Url;

$num=0;
?>
<table class="table table-bordered">
    <tr>
        <th>#</th>
        <th>Вопрос</th>
        <th>Количество ответов</th>
        <th>Количество правильных ответов</th>
        <th>% правильных ответов</th>
    </tr>
    <?php foreach ($resultQuery as $result): ?>
        <?php
        $num++;
        $persent = round(($result['count_right'] / $result['count_answered']) * 100);
        ?>
        <tr>
            <td><?= $num; ?></td>
            <td><?= $result['name'] ?></td>
            <td><?= $result['count_answered'] ?></td>
            <td><?= $result['count_right'] ?></td>
            <td>
                <div class="text-center">
                    <span class="label label-default" style="font-size: large;"><?= $persent ?>%</span>
                </div>
                <br />
                <button class="btn btn-default btn-show-answer-detail"
                    data-toggle="#container_answer_detail_<?= $result['id'] ?>"
                    data-url="<?= Url::to(['/test/default/statistic-answers-detail', 'idQuestion'=>$result['id']]) ?>">Подробнее</button>
            </td>
        </tr>
        <tr>
            <td colspan="5">
                <div id="container_answer_detail_<?= $result['id'] ?>" style="display: none;"></div>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
<script type="text/javascript">
    $('.btn-show-answer-detail').on('click', function (){
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