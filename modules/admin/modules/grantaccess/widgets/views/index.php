<?php
/** @var \yii\web\View $this */
/** @var array $items */

use yii\bootstrap5\Dropdown;
?>
<a href="#" data-bs-toggle="dropdown" class="dropdown-toggle btn btn-primary">
    <i class="fas fa-user-shield"></i> Управление доступом &nbsp;&nbsp;<i class="far fa-share-square"></i>
</a>
<?php
    echo Dropdown::widget([
        'items' => $items,            
    ]);
?>