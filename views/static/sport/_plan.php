<?php

/** @var \yii\web\View $this */

$file = '/files_static/8600/sport/plan/Приложение.pdf';
?>

<div class="card card-body my-1">
    <h5>
        <a href="/files_static/8600/sport/plan/gf_~3738285.pdf" target="_blank"><i class="fas fa-file-pdf"></i> Приказ</a>
    </h5>
</div>

<object data="<?= $file ?>" style="width: 100%; height: 100vh;" type="application/pdf">
    <embed data="<?= $file ?>" type="application/pdf"></embed>
    <p>Ваш браузер не поддерживает просмотр pdf.</p>
    <a href="<?= $file ?>">Скачать</a>
</object>