<?php
$this->pageTitle=Yii::app()->name . ' - Ошибка';
?>
<div class="well">
    <h2>Ошибка <?php echo $code; ?></h2>

    <div class="error">
        <?php echo CHtml::encode($message); ?>
    </div>
</div>
