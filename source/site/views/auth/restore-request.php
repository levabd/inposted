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
<div class="well" style="background:#ffffff;margin-top:10px;"><!--вход на сайт-->
    <div class="well" style="background:#fffd74;margin:-19px -19px -10px -19px ; border: 1px solid #fffd74;">
        <a href="" style="color:#54211d;font-size:18px;text-decoration: underline;"><b>Send new pass</b></a>
    </div>
    <br>

    <div style="text-align:center;min-height:60px;">
        <?=$form->textField($model, 'username', ['style' => 'width:93%;'])?>
        <?=$form->error($model, 'username')?>
        <input type="submit" class="btn" style="text-decoration: underline;float:right;" value="Next"/>
    </div>
</div>
<?php $this->endWidget(); ?>
