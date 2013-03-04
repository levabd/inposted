<?php
/** @var $this \site\controllers\AuthController */
/**
 * @var $model \site\models\forms\Restore
 */
/** @var $form \CActiveForm */
$form = $this->beginWidget(
    'CActiveForm',
    [
    'id'                     => 'restore-form',
    'enableAjaxValidation'   => false,
    'enableClientValidation' => false,
    'focus'                  => array($model, 'username'),
    'errorMessageCssClass'   => 'text-error span6',
    'htmlOptions'            => ['class' => 'ajax'],
    ]
);
?>
<div class="well mini_post_white"><!--вход на сайт-->
    <div class="well yellow">
        <span class="ref_main"><b>Send new pass</b></span>
    </div>
    <br>

    <div class="send_np">
        <?=$form->textField($model, 'username', ['style' => 'width:93%;'])?>
        <?=$form->error($model, 'username')?>
        <input type="submit" class="btn mypre" value="Next"/>
    </div>
</div>
<?php $this->endWidget(); ?>
