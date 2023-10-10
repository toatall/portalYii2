<?php

/** @var \yii\web\View $this */
/** @var array $result */
/** @var array $raions */
/** @var bool isPrint */

use app\assets\AppAsset;
use yii\helpers\Url;

$isPrint = $isPrint ?? false;

AppAsset::register($this);
?>

<?php if ($isPrint): ?>
    <?php $this->registerCss(<<<CSS
        kbd {
            color: #000 !important;
            background-color: #fff !important;
        }
    CSS); ?>
<?php endif; ?>

<div <?= ($isPrint ? 'class="d-flex justify-content-center container-fluid w-75"' : '') ?>>

    <div>

    <?php if ($isPrint): ?>
        <p class="mt-5">По состоянию на <?= Yii::$app->formatter->asDate($result[0]['date']) ?></p>
        <hr />  
    <?php endif; ?>

    <table class="table table-hover w-100" id="table-statistic" style="<?= $isPrint ? 'inherit' : 'font-size: 0.80rem;' ?>">
        <thead class="thead-light">
            <tr>
                <th>Налоговый орган</th>
                <th>
                    Начисления (прогнозируемые), тыс. рублей
                </th>
                <th>
                    Поступления (с 01.01.2023), тыс. рублей
                </th>
                <th>
                    КПЭ показатель
                </th>
                <th>
                    Оставшаяся сумма до 95%
                </th>
                <th>
                    Прирост КПЭ показателя с предыдущей даты
                </th>
                <!-- <th>
                    Достижение КПЭ (95 %)
                </th> -->
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($result as $item) :
                $region = isset($raions[$item['code']]) ? $raions[$item['code']] : '';
                $sms1 = Yii::$app->formatter->asDecimal($item['sms_1'] ?? 0);
                $sms2 = Yii::$app->formatter->asDecimal($item['sms_2'] ?? 0);
                $sms3 = Yii::$app->formatter->asDecimal($item['sms_3'] ?? 0);
                $sumLeftAll = Yii::$app->formatter->asDecimal($item['sum_left_all'] ?? 0, 0);
                $sumLeftNifl = Yii::$app->formatter->asDecimal($item['sum_left_nifl'] ?? 0);
                $sumLeftTn = Yii::$app->formatter->asDecimal($item['sum_left_tn'] ?? 0);
                $sumLeftZn = Yii::$app->formatter->asDecimal($item['sum_left_zn'] ?? 0);
                $growthSms = Yii::$app->formatter->asDecimal($item['growth_sms'] ?? 0);
                $kpe_persent = Yii::$app->formatter->asDecimal($item['kpe_persent'] ?? 0);
                $sizeNumValues = $isPrint ? 'inherit' : '0.77rem';
            ?>
                <tr data-org="<?= $item['code'] ?>" data-region="<?= $region ?>">
                    <td style="font-size: <?= $isPrint ? 'inherit': '0.8rem;' ?>">
                        <?= $item['name_short'] ?>
                    </td>
                    <td>
                        <kbd style="font-size: <?= $sizeNumValues ?>;">
                            <?= Yii::$app->formatter->asDecimal($item['sum1'], 0) ?>
                        </kbd>
                    </td>
                    <td>
                        <kbd style="font-size: <?= $sizeNumValues ?>;">
                            <?= Yii::$app->formatter->asDecimal($item['sum2'], 0) ?>
                        </kbd>
                    </td>
                    <td>
                        <kbd style="font-size: <?= $sizeNumValues ?>;" data-bs-toggle="popover" data-bs-trigger="hover" data-bs-original-title="КПЭ показатели" data-bs-html="true" data-bs-content="НИФЛ: <?= $sms1 ?><br />Транспортный налог: <?= $sms2 ?><br />Земельный налог: <?= $sms3 ?>">
                            <?= Yii::$app->formatter->asDecimal($item['sms']) ?>
                        </kbd>
                    </td>
                    <td>
                        <kbd style="font-size: <?= $sizeNumValues ?>;" data-bs-toggle="popover" data-bs-trigger="hover" data-bs-original-title="Оставшаяся сумма до 95%" data-bs-html="true" data-bs-content="НИФЛ: <?= $sumLeftNifl ?><br />Транспортный налог: <?= $sumLeftTn ?><br />Земельный налог: <?= $sumLeftZn ?>">
                            <?= $sumLeftAll ?>
                        </kbd>
                    </td>
                    <td>
                        <kbd style="font-size: <?= $sizeNumValues ?>;">
                            <?= $growthSms ?>
                        </kbd>
                    </td>
                    <!-- <td>
                        <kbd style="font-size: <?= $sizeNumValues ?>;">
                            <?= $kpe_persent ?>
                        </kbd>
                    </td> -->
                    <td>
                        <?php if (!$isPrint): ?>
                        <button class="btn btn-outline-secondary btn-chart-ifns" data-org="<?= $item['code'] ?>" data-url="<?= Url::to(['/paytaxes/default/chart-data', 'org' => $item['code']]) ?>">
                            <i class="fas fa-chart-pie"></i>
                        </button>
                        <?php endif; ?>
                    </td>
                </tr>
                <tr style="display: none;" id="sms_data_<?= $item['code'] ?>" data-org="<?= $item['code'] ?>" data-region="<?= $region ?>">
                    <td colspan="8">
                        <div class="row">
                            <div class="col">
                                <strong>КПЭ показатели</strong><br />
                                НИФЛ: <?= $sms1 ?>
                                <br />Транспортный налог: <?= $sms2 ?>
                                <br />Земельный налог: <?= $sms3 ?>
                            </div>
                            <div class="col">
                                <strong>Оставшаяся сумма до 95%</strong><br />
                                НИФЛ: <?= $sumLeftNifl ?>
                                <br />Транспортный налог: <?= $sumLeftTn ?>
                                <br />Земельный налог: <?= $sumLeftZn ?>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr style="display: none;" id="chart_<?= $item['code'] ?>" data-org="<?= $item['code'] ?>" data-region="<?= $region ?>">
                    <td colspan="8">
                        <div style="max-width:100%;">

                        </div>
                    </td>
                </tr>
                <tr style="display: none;" id="chart2_<?= $item['code'] ?>" data-org="<?= $item['code'] ?>" data-region="<?= $region ?>">
                    <td colspan="8">
                        <div style="max-width:100%">

                        </div>
                    </td>
                </tr>
                <tr style="display: none;" id="chart3_<?= $item['code'] ?>" data-org="<?= $item['code'] ?>" data-region="<?= $region ?>">
                    <td colspan="8">
                        <button class="btn-show-chart-pr-year btn btn-secondary btn-sm">Показать график по дням за 2022 год</button>
                        <div style="max-width:100%; display:none;"></div>
                    </td>
                </tr>

            <?php endforeach; ?>

        </tbody>
    </table>

    </div>

</div>
<?php $this->registerJs(<<<JS
    $('[data-bs-toggle="popover"]').popover()
JS); ?>