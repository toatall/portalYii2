<?php
/* @var $this \yii\web\View */
/* @var $result array */
/* @var $list bool */

use yii\helpers\Url;

$this->title = 'Аллея славы';

?>
<h1 style="font-weight: bolder; color: white;"><?= $this->title ?></h1>
<hr />

<?php if ($list): ?>
    <?php foreach ($result as $res): ?>
        <a href="<?= Url::to(['alley', 'id'=>$res['id']]) ?>">
            <div class="thumbnail" style="float: left; width:220px; margin-right:5px;">
                <img src="/images/vov/thumb_<?= $res['photo'] ?>" class="thumbnail" />
                <div style="color:#000; padding:9px; height:150px;">
                    <h3 style="text-align: center;"><?= $res['fio'] ?></h3>
                </div>
            </div>
        </a>
    <?php endforeach; ?>
<?php else: ?>
    <center>
        <table>
            <tr>
                <td style=""></td>
                <td>
                    <a href="/images/vov/<?= $result['photo'] ?>" target="_blank">
                        <img style="width:700px;" align="center" src="/images/vov/<?= $result['photo'] ?>" class="thumbail" />
                    </a>
                    <?php if ($result['photo2']): ?>
                        <br /><br />
                        <a href="/images/vov/<?= $result['photo2'] ?>" target="_blank">
                            <img border="0" style="width:700px;" align="center" src="/images/vov/<?= $result['photo2'] ?>" class="thumbail" />
                        </a>
                    <?php endif; ?>
                </td>
                <td></td>
            </tr>
        </table>
        <br />
        <div>
            <h1 style="color:#DA7808;"><?= $result['fio'] ?></h1>
            <hr style="color:#DA7808;" />
            <h2 style="color:#DA7808;"><?= $result['title'] ?></h2>
        </div>
        <div style="padding:10px;color:#bbb;"><?php
            $str = $result['text1'];
            $str = str_replace(array("[quote_1]"), '"', $str);
            $str = str_replace(array("[quote_2]"), "'", $str);
            echo $str;
            ?></div>
    </center>
    <hr />
    <a href="<?= Url::to(['/vov/alley']) ?>" class="btn btn-danger">Назад</a>
<?php endif; ?>

<?php
$this->registerCss('.thumbnail h3 { color:#DA7808; font-weight: bold; font-size:18px; }');
$this->registerJs(<<<JS
    $('.wrap').css('background-color', '#6D0200');
JS
);

