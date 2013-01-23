<?php
/** @var $this \site\controllers\AuthController */
/** @var $model \site\models\User */
?>
<div class="span5 well signupForm">
    <?php
    /** @var $form \CActiveForm */
    $form = $this->beginWidget(
        'CActiveForm',
        [
        'id'                     => 'signup-form',
        'enableAjaxValidation'   => false,
        'enableClientValidation' => false,
        'focus'                  => array($model, 'nickname'),
        'errorMessageCssClass'   => 'help-block',
        ]
    );
    ?>
    <fieldset>
        <div class="control-group <?=$model->hasErrors('nickname') ? 'error' : ''?>">
            <?=$form->textField($model, 'nickname', array('placeholder' => $model->getAttributeLabel('nickname'), 'class' => 'span5')); ?>
            <?=$form->error($model, 'nickname')?>
        </div>
        <div class="control-group <?=$model->hasErrors('email') ? 'error' : ''?>">
            <?=$form->textField($model, 'email', array('placeholder' => $model->getAttributeLabel('email'), 'class' => 'span5')); ?>
            <?=$form->error($model, 'email')?>
        </div>
        <div class="control-group <?=$model->hasErrors('password') ? 'error' : ''?>">
            <?=$form->passwordField($model, 'password', array('placeholder' => $model->getAttributeLabel('password'), 'class' => 'span5')); ?>
            <?=$form->error($model, 'password')?>
        </div>
        <div class="control-group">
            <button type="submit" class="btn btn-primary">Next</button>
        </div>
    </fieldset>
    <?php $this->endWidget(); ?>
</div>