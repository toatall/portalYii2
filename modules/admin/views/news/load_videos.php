<?php 
/** @var \yii\web\View $this */
/** @var \app\models\news\News $modelNews */
/** @var app\models\Tree $modelTree */

use kartik\file\FileInput;
use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Alert;
use yii\bootstrap5\Html;
use yii\helpers\Url;

$this->title = 'Загрузка видео: ' . $modelNews->title;
$this->params['breadcrumbs'][] = ['label' => 'Новости раздела "' . $modelTree->name . '"', 'url' => ['index', 'idTree' => $modelNews->id_tree]];
$this->params['breadcrumbs'][] = ['label' => $modelNews->title, 'url' => ['view', 'id' => $modelNews->id]];
$this->params['breadcrumbs'][] = 'Загрузка видео';
?>
<div class="news-load-video mb-3">

    <h1 class="display-5 border-bottom">
        <?= Html::encode($this->title) ?>
    </h1>

    <?= Alert::widget([
        'options' => ['class' => 'alert-info'],
        'body' => <<<HTML
            <b>Внимание</b><br />
            Максимальный размер файла составляет 100 Мб. Рекомендуется по возможности максимально уменьшить размер файла, 
            что позволит пользователям просматривать видео без задержек.<br /><hr />
            Для сжатия видео можно воспользоваться бесплатной программой Convertilla (<a href="/files_static/software/convertilla/Convertilla.zip">скачать</a>). Программа не требует установки.
            Для использования необходимо запустить файл convertilla.exe, нажать кнопку "Открыть", выбрать видео-файл, указать формат, 
            в котором видео будет сохранено (например, MP4), выбрать качество "Другое", установить ползунок (например, на середину),
            указать имя сконвертированного файла (например, добавить в имя +) и нажать "Конвертировать". 
        HTML,
    ]) ?>

    <?php $form = ActiveForm::begin([
        'options'=> ['enctype' => 'multipart/form-data', 'autocomplete'=>'off'],
    ]); ?>

    <div class="card mt-3">
        <div class="card-header">
            Загрузка видео
        </div>
        <div class="card-body">
            <?= $form->field($modelNews, 'uploadVideos[]')->widget(FileInput::class, [
                'options' => [
                    'accept' => 'video/*',
                    'multiple' => true,
                ],
                'pluginOptions' => [
                    'showUpload' => false,
                    'theme' => 'fa5',
                ],
            ]) ?>
            <?php 
            $listVideo = $modelNews->getVideoFiles();   
            if (count($listVideo) > 0): ?>
                <hr />
                <?= $form->field($modelNews, 'deleteVideos', [])
                    ->checkboxList($listVideo, [
                        'item' => function($index, $label, $name, $checked, $value) {
                            return "<div class=\"checkbox\"><label><input type=\"checkbox\" name=\"{$name}\" value=\"" . basename($label) ."\"> " 
                                . '<span class="video">' . basename($label) . '</span> '
                                . (file_exists(Yii::getAlias('@webroot'. $label))
                                    ? ' (' . Yii::$app->storage->sizeText(Yii::$app->storage->size(Yii::getAlias('@webroot' . $label))) . ') '
                                    : ' ')
                                . Html::a('(просмотр)', Url::to($label, true), ['target' => '_blank']) 
                                . "</label></div>";
                        },
                    ])->label($modelNews->getAttributeLabel('deleteVideos') . ' (отметьте файлы для удаления)', ['class'=>'fw-bold border-bottom mb-2']) ?>
            <?php endif; ?>
        </div>
    </div>

    <div class="btn-group mt-3">
        <?= Html::submitButton('Загрузить', ['class' => 'btn btn-success']) ?>
        <?= Html::a('Назад', ['index', 'idTree' => $modelNews->id_tree], ['class' => 'btn btn-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>


</div>
