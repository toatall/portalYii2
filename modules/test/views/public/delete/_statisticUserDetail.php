<?php
/* @var $this yii\web\View */
/* @var $resultQuery array */

?>
<table class="table">
    <?php foreach ($resultQuery as $result): ?>
        <tr>
            <td class="<?= $result['is_right'] ? 'bg-success' : 'bg-danger' ?>"><?= $result['name'] ?></td>
        </tr>
    <?php endforeach; ?>
</table>