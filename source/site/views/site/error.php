<?php
$this->pageTitle=Yii::app()->name . ' - Îøèáêà';
?>
<div class="well">
    <h2>Error <?php echo $code; ?></h2>

    <div class="error">
        <?php echo CHtml::encode($message); ?>
    </div>
</div>
