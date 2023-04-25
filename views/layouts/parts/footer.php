<?php 
/** @var yii\web\View $this */

use app\models\Footer;
use yii\bootstrap5\Html;

$footerData = Footer::getLinks();

?>

<footer class="footer mt-3 bg-light border-top pt-3" style="height: auto;">
    <div class="container-fluid">
        <div class="row px-5">
            <?php foreach($footerData as $sectionName => $links): ?>
                <div class="col-4 mb-3">
                    <h5 class="fw-bolder"><?= $sectionName ?></h5>
                    <ul class="list-unstyled">
                        <?php foreach($links as $link): 
                            $options = [];
                            if ($link['target']) {
                                $options['target'] = $link['target'];
                            }
                            $options = array_merge($options, (json_decode($link['options']) ?? []));
                        ?>
                        <li>
                            <?= Html::a($link['text'], $link['url'], $options) ?>
                        </li>
                        <?php endforeach ?>
                    </ul>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <hr />
    <div class="pb-2 text-center">
        <p>&copy; <?= Html::encode(Yii::$app->name) ?> <?= date('Y') ?></p>
    </div>
</footer>