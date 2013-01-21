<?php if ($msg = User()->getError()): ?>
<div class="alert alert-error"><?=$msg?></div>
<? endif ?>
<?php
/**
 * @var $model \site\models\User
 */
/** @var $form \CActiveForm */
$form = $this->beginWidget(
    'CActiveForm',
    array(
         'id' => 'contact-form',
         'enableAjaxValidation' => false,
         'enableClientValidation' => false,
         'focus' => array($model, 'name'),
         'errorMessageCssClass' => 'help-block',
         'htmlOptions' => array(
             'class' => 'form-horizontal'
         )
    )
);
?>
<fieldset>
    <div class="control-group <?=$model->hasErrors('name') ? 'error' : ''?>">
        <?=$form->labelEx($model, 'name', array('class' => 'control-label'))?>
        <div class="controls">
            <?=$form->textField($model, 'name'); ?>
            <?=$form->error($model, 'name')?>
        </div>
    </div>
    <div class="control-group <?=$model->hasErrors('email') ? 'error' : ''?>">
        <?=$form->labelEx($model, 'email', array('class' => 'control-label'))?>
        <div class="controls">
            <?=$form->textField($model, 'email'); ?>
            <?=$form->error($model, 'email')?>
        </div>
    </div>
    <div class="control-group <?=$model->hasErrors('url') ? 'error' : ''?>">
        <?=$form->labelEx($model, 'url', array('class' => 'control-label'))?>
        <div class="controls">
            <?=$form->textField($model, 'url'); ?>
            <?=$form->error($model, 'url')?>
        </div>
    </div>
    <div class="control-group <?=$model->hasErrors('subject') ? 'error' : ''?>">
        <?=$form->labelEx($model, 'subject', array('class' => 'control-label'))?>
        <div class="controls">
            <?=$form->textField($model, 'subject'); ?>
            <?=$form->error($model, 'subject')?>
        </div>
    </div>
    <div class="control-group <?=$model->hasErrors('body') ? 'error' : ''?>">
        <?=$form->labelEx($model, 'body', array('class' => 'control-label'))?>
        <div class="controls">
            <?=$form->textArea($model, 'body'); ?>
            <?=$form->error($model, 'body')?>
        </div>
    </div>
    <div <?=!$model->hasErrors('verifyCode') ? 'style="display: none"' : ''?>
            class="control-group <?=$model->hasErrors('verifyCode') ? 'error' : ''?>">
        <?=$form->labelEx($model, 'verifyCode', array('class' => 'control-label'))?>
        <div class="controls">
            <?=$form->textField($model, 'verifyCode'); ?>
            <img id="captcha-image" src="<?=$this->createUrl('captcha', array('v' => uniqid()))?>"/>
            <?=$form->error($model, 'verifyCode')?>
        </div>
    </div>
    <div class="control-group">
        <div class="controls">
            <button type="submit" class="btn btn-primary">Send message</button>
        </div>
    </div>
</fieldset>
<?php $this->endWidget(); ?>
<script type="text/javascript">
    $(function(){
        var $form = $('#contact-form');
        var $submit = $form.find('button[type=submit]');
        $form.submit(function(){
            $submit.attr('disabled',true);
        })
        $('<?=CHtml::hiddenField('skipVerify',true)?>').appendTo($form);
    });
</script>