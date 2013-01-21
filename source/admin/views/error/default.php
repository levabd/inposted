<?php
/** @var $this \base\ErrorController */
/** @var $code integer*/
/** @var $type string*/
/** @var $errorCode integer*/
/** @var $message string*/
/** @var $file string*/
/** @var $line integer*/
/** @var $trace string*/
/** @var $traces array*/
/** @var $version string*/
/** @var $time integer*/
/** @var $admin string*/

$this->pageTitle=Yii::app()->name . ' - Error';
?>

<h2>Error <?php echo $code; ?></h2>

<div class="error">
<?php echo CHtml::encode($message); ?>
</div>