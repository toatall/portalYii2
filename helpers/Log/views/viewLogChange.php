<?php
/* @var $this \yii\web\View */
/* @var $array_str array */

?>
<div class="well" style="padding:10px;">
    <?php if (count($array_str) > 0) {
        echo $array_str[0];
        unset($array_str[0]);
    }
    ?>&nbsp;
    <i id="detail-log-change" class="icon-circle-arrow-down" style="cursor: pointer;"></i>
    <div id="content-log-change" style="display: none;">
        <?php
        foreach ($array_str as $val)
        {
            echo $val.'<br />';
        }
        ?>
    </div>

</div>

<?php
$this->registerJs(<<<JS
    $('#detail-log-change').click(function(){
        $('#content-log-change').slideToggle();

        if ($('#detail-log-change').hasClass('icon-circle-arrow-down')) {
            $('#detail-log-change').removeClass('icon-circle-arrow-down').addClass('icon-circle-arrow-up');
        } else {
            $('#detail-log-change').removeClass('icon-circle-arrow-up').addClass('icon-circle-arrow-down');
        }
    });
JS
);
?>