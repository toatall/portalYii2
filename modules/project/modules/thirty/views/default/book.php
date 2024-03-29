<?php
/** @var yii\web\View $this */

use yii\bootstrap5\Html;

?>
<div style="font-size: 20px;" class="p-3">
<p>Уважаемые коллеги, юбилей налоговых органов продолжается и мы рады представить электронную версию юбилейной книги Управления в онлайн-формате, а также видеопрезентацию о книге.</p>
<p>Книга размещена в разделе «30 лет ФНС» и ознакомиться с ней можно по ссылке . Презентацию книги вы можете посмотреть в приложенном видео.</p>
<p>Идея создания юбилейной книги витала еще в 2018 года, когда мы обновляли экспозицию музея.</p>
<p>Когда думали над идеей и концепцией книги, для нас стало ясно, что концепция должна быть только одна – это наша история, наши сотрудники. </p>
<p>В книге постарались отразить все важные моменты жизни нашего Управления за 30 лет,  которое на сегодня состоит из 11 территориальных налоговых органов. Также в нашей жизни активно участвует и Ханты-Мансийский филиал ФКУ налог-Сервис.</p>
<p>Книга создана на основе материалов инспекций, архивных данных Управления. Над созданием книги трудилась целая команда. В выходные и вне рабочее время, было отсмотрено огромное количество материала разных лет, а сколько пересмотрено фотографий!</p>
<p>Начиналась книга с 50 разворотов (это 100 страниц), в процессе работы объем собираемого материала рос, и книга выросла в итоге до 216 страниц. Книга весит более 1 кг 600 гр.</p>
</div>

<hr />
<?= Html::a('<i class="fas fa-book"></i> Просмотр книги', ['/thirty/view-book'], ['class' => 'btn btn-primary', 'target'=>'_blank']) ?>

<hr />

<div class="card">
    <div class="card-header">
        Презентация юбилейной книги
    </div>
    <div class="card-body text-center">
        <p>
            <video controls="" width="1000">
                <source src="/files_static/thirty/Презентация1.mp4">
            </video>
        </p>
    </div>
    <div class="card-footer">
        <a class="btn btn-secondary" href="/files_static/thirty/Презентация1.mp4">Скачать</a>
    </div>
</div>