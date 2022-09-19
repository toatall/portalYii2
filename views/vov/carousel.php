<?php
/* @var $this \yii\web\View */
/* @var $files array */
?>

<?= \yii\bootstrap5\Carousel::widget([
    'options' => [
        'id' => 'carosel-vov',
    ],
    'items' => $files,
]) ?>
<?php
$this->registerJs(<<<JS
    //$('.carousel').carousel();
JS
);
?>
<script type="text/javascript">
    $('.carousel').carousel();
</script>
