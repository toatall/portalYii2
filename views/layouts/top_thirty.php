<?php
/* @var $this \yii\web\View */

use app\models\thirty\ThirtyThroughTime;

?>
<?php
// летящая картинка
$modelThoughTimeToday = new ThirtyThroughTime();
$itemThoughTimeToday = $modelThoughTimeToday->getTodayRecord();
$itShow = !Yii::$app->user->isGuest && !Yii::$app->user->identity->isNewSession();
if ($itemThoughTimeToday != null):
    ?>
    <style type="text/css">

        .link-logo-thirty {
            transform: scale(1);
            transition: transform 0.3s linear;
        }

        .link-logo-thirty:hover {
            transform: scale(0.95);
        }

        /* фотка вылетает */
        .box {
            align-self: flex-end;
            align-content: center;
            z-index: 9999999;
            /*position: absolute;
            top: -230px; right: 70px;*/
            position: fixed;
            top: -300px;
            right: -500px;
            width: 80vw;
            height: 80vh;
        }

        .bounce-2 {
            animation-name: bounce-2;
            animation-timing-function: cubic-bezier(1, 1, 1, 1);
            animation-iteration-count: 1;
            animation-duration: 20s;
            animation-delay: 5s;
            animation-fill-mode: forwards;
            transform: scale(0.001);
        <?php if ($itShow): ?>
            animation-play-state: paused;
        <?php endif; ?>
        }

        @keyframes bounce-2 {
            0% {
                transform: scale(0.001);
            }
            10% {
                transform: scale(1) translateX(-35vw) translateY(35vh);
            }
            80% {
                transform: scale(1) translateX(-35vw) translateY(35vh);
            }
            100% {
                transform: scale(0.001);
                visibility: hidden;
            }
        }

        /* затемнение */
        .black-wall {
            animation-name: black-well;
            animation-timing-function: cubic-bezier(1, 1, 1, 1);
            animation-iteration-count: 1;
            animation-duration: 20s;
            animation-delay: 5s;
            animation-fill-mode: forwards;
            background-color: transparent;
        <?php if ($itShow): ?>
            animation-play-state: paused;
        <?php endif; ?>
        }

        @keyframes black-well {
            5% {
                filter: grayscale(100%) blur(5px);
            }
            95% {
                filter: grayscale(100%) blur(5px);
            }
        }

        /* картинка мигает */
        .img-thirty {
            animation-name: img-thirty;
            animation-timing-function: cubic-bezier(1, 1, 1, 1);
            animation-iteration-count: 3;
            animation-duration: 1.5s;
            animation-delay: 25s;
        <?php if ($itShow): ?>
            animation-play-state: paused;
        <?php endif; ?>
        }

        @keyframes img-thirty {
            0% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.02);
                filter: brightness(110%);
            }
        }

    </style>
    <div class="box bounce-2 panel panel-default">
        <div class="panel-heading">
            <h3 style="font-weight: bolder;">Сквозь время</h3>
            <button type="button" class="close" style="margin-top: -50px;" id="btn-thirty-through-time-close"
                    data-dismiss="modal" aria-label="close">
                <span aria-label="Close">×</span>
            </button>
        </div>
        <div class="panel-body">
            <div class="col-sm-6">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <img src="<?= $itemThoughTimeToday['photo_old'] ?>" style="max-width: 50vw; max-height: 50vh; margin: 0 auto;"
                             class="thumbnail"/>
                    </div>
                    <div class="panel-footer">
                        <?= $itemThoughTimeToday['description_old'] ?>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <img src="<?= $itemThoughTimeToday['photo_new'] ?>" style="max-width: 50vw; max-height: 50vh; margin: 0 auto;"
                             class="thumbnail"/>
                    </div>
                    <div class="panel-footer">
                        <?= $itemThoughTimeToday['description_new'] ?>
                    </div>
                </div>
            </div>

        </div>
    </div>
<?php $this->registerJS(<<<JS
    
    $('#btn-thirty-through-time-close').on('click', function () {
        // убрать окошко
        $('.black-wall').css('animation-duration', '1ms');
        $('.box').hide();
    });
    
JS
);
?>
<?php endif; ?>

