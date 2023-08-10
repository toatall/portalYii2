<?php

/** @var \yii\web\View $this */

$file = '/files_static/8600/sport/schedule111.pdf';
?>

<object data="<?= $file ?>" style="width: 100%; height: 100vh;" type="application/pdf">
    <embed data="<?= $file ?>" type="application/pdf"></embed>
    <p>Ваш браузер не поддерживает просмотр pdf.</p>
    <a href="<?= $file ?>">Скачать</a>
</object>