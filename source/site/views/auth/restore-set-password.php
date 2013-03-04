<?php
/** @var $this \site\controllers\AuthController */
/** @var $model \site\models\forms\Restore */
/** @var $form \CActiveForm */
$form = $this->beginWidget(
    'CActiveForm', array(
                        'id'                     => 'restore-form',
                        'enableAjaxValidation'   => false,
                        'enableClientValidation' => false,
                        'focus'                  => array($model, 'password'),
                        'errorMessageCssClass'   => 'help-block',
                        'htmlOptions'            => array(
                            'class' => 'form-horizontal',
                        )
                   )
);
?>
    <div class="well">
        <fieldset>
            <?php if ($success = User()->getSuccess()): ?>
                <div class="alert alert-success"><?=$success?></div>
            <?php endif?>
            <?php if ($error = User()->getError()): ?>
                <div class="alert alert-error"><?=$error?></div>
            <?php endif?>
            <div class="control-group <?=$model->hasErrors('username') ? 'error' : ''?>">
                <?php echo $form->label($model, 'username', array('class' => 'control-label')); ?>
                <div class="controls">
                    <span class="uneditable-input"><?=$model->username?></span>
                </div>
            </div>
            <div class="control-group <?=$model->hasErrors('password') ? 'error' : ''?>">
                <?php echo $form->label($model, 'password', array('class' => 'control-label')); ?>
                <div class="controls">
                    <?=$form->passwordField($model, 'password', array('placeholder' => $model->getAttributeLabel('password'), 'tabindex' => 1)); ?>
                    <?=$form->error($model, 'password')?>
                </div>
            </div>
            <div class="control-group">
                <div class="controls">
                    <button type="submit" class="btn btn-inverse" tabindex="1">Set password</button>
                </div>
            </div>
        </fieldset>
    </div>
<?php $this->endWidget(); ?>