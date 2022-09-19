<?php
/** @var yii\web\View $this */
/** @var array $model */

use yii\bootstrap5\Html;

?>
<div id="container-form-vote">
    
    <div class="card mt-3">
        <div class="card-header">
            <h5>Задания</h5>
        </div>
        <div class="card-body">            
            <div>
                <!--table class="table table-bordered">
                    <tr>
                        <th>Команда (автор задания)</th>
                        <th>Наименование задания</th>
                        <th>Ответ команды (правильный / неправильный)</th>
                        <th>Дата</th>
                    </tr>
                <?php foreach($model as $item): ?>
                    <tr>
                        <td><?= $item['team_name'] ?></td>
                        <td>
                            <?= $item['title'] ?><br />
                            <?= Html::button('Подробнее', ['class'=>'btn btn-sm btn-outline-primary btn-detail', 'data-id'=>$item['id']]) ?>
                        </td>
                        <td><?= $item['is_right'] ? '<span class="badge badge-success">правильный</span>' : '<span class="badge badge-danger">неправильный / нет ответа</span>' ?></td>
                        <td><?= Yii::$app->formatter->asDateTime($item['date_show_1']) ?></td>
                    </tr>
                    <tr style="display: none;" id="tr-<?= $item['id'] ?>">
                        <td colspan="4">
                            <div class="w-50">
                                <?= $item['text'] ?>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </table-->
                
                <?php if ($model == null): ?>
                    <div class="alert alert-warning">Нет задания</div>
                <?php else: ?>
                    <?php foreach($model as $item): ?>
                        <div class="row col">
                            <?= $item['text'] ?>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>        
        </div>
    </div>

    <div id="container-failed"></div>
</div>


<?php $this->registerJs(<<<JS
    $('#form-vote').on('submit', function() {
        const form = $(this);
        const modal = $('#modal-dialog');
        const modalBody = modal.find('.modal-body');

        $.ajax({
            url: form.attr('action'),
            method: 'post',
            data: form.serialize()
        })
        .done(function(resp) {
            if (resp.toUpperCase() == "OK") {
                $.pjax.reload({ container: '#pjax-fort-boyard-teams' });
                modal.modal('hide');                
            }
            else {
                modalBody.html(resp);
            }
        })
        .fail(function(err) {
            $('#container-failed').html('<div class="alert alert-danger">' + err.responseText + '</div>');
        });

        return false;        
    });

    $('.btn-detail').on('click', function() {
        $('#tr-' + $(this).data('id')).toggle();
    });
JS); ?>