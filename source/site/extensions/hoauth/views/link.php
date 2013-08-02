<?php
/**
 * @var HOAuthWidget $this
 * @var string $provider name of provider
 */
?>
<p>
  <a href="<?php echo Yii::app()->createUrl($this->route . '/oauth', array('provider' => $provider)); ?>" class="zocial <?php  echo strtolower($provider) ?>">Зайти с <?php  echo $provider ?></a>
</p>
