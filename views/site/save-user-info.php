<?php
/** @var \yii\web\View $this */

use yii\helpers\Url;


$url = Url::to(['/site/screen-resolution', 'width'=>'_w_', 'height'=>'_h_']);
?>

<div class="alert alert-info lead">
    Вход выполнен!
    <br />
    <a href="<?= $url ?>" class="btn btn-secondary mt-3">На главную страницу</a>
</div>

<script type="text/javascript">
    const url = '<?= $url ?>'.replace('_w_', screen.width).replace('_h_', screen.height);
    window.location = url;
</script>