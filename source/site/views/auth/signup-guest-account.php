<div class="signupFormPromo">
    <h1>Use Inposted free for 45 days. It's on us.</h1>
    <p>45-day unlimited free trial. No obligation, no credit card required.</p>
</div>
<div class="span5 well signupForm">
    <h2>Start your free trial</h2>
    <p>You'll be up and running in under a minute.</p>
<?php
/**
 * @var $model \site\models\User
 */
/** @var $form \CActiveForm */
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'signin-form',
    'enableAjaxValidation' => false,
    'enableClientValidation' => false,
    'focus' => array($model, 'fullName'),
    'errorMessageCssClass' => 'help-block',
    'htmlOptions' => array(
        'class' => 'form-horizontal'
    )
)); ?>
<fieldset>
    <div class="control-group <?=$model->hasErrors('fullName')?'error':''?>">
        <?=$form->textField($model, 'fullName', array('placeholder' => $model->getAttributeLabel('fullName'), 'class' => 'span5')); ?>
        <?=$form->error($model, 'fullName')?>
    </div>
    <div class="control-group <?=$model->hasErrors('company')?'error':''?>">
        <?=$form->textField($model, 'company', array('placeholder' => $model->getAttributeLabel('company'), 'class' => 'span5')); ?>
        <?=$form->error($model, 'company')?>
    </div>
    <div class="control-group <?=$model->hasErrors('email')?'error':''?>">
        <?=$form->textField($model, 'email', array('placeholder' => $model->getAttributeLabel('email'), 'class' => 'span5')); ?>
        <?=$form->error($model, 'email')?>
    </div>
    <div class="control-group <?=$model->hasErrors('password')?'error':''?>">
        <?=$form->passwordField($model, 'password', array('placeholder' => $model->getAttributeLabel('password'), 'class' => 'span5')); ?>
        <?=$form->error($model, 'password')?>
    </div>
    <div class="control-group">
        <button type="submit" class="btn btn-primary">Start your free trial</button>
    </div>
</fieldset>
<?php $this->endWidget(); ?>
</div>