<?php
/** @var $this \site\controllers\AuthController */
/** @var $model \site\components\oauth\UserInfoForm */
/** @var $form \CActiveForm */
$model = $form;

$form = $this->beginWidget(
    'CActiveForm', array(
                        'id'                     => 'oauth-form',
                        'enableAjaxValidation'   => false,
                        'enableClientValidation' => false,
                        'focus'                  => array($model, 'email'),
                        'errorMessageCssClass'   => 'help-block',
                        'htmlOptions'            => array(
                            'class' => 'form-horizontal',
                        )
                   )
);
?>
    <div class="well">
        <fieldset>
            <div class="control-group">
                <div class="controls"><?=$model->getHeader()?></div>
            </div>

            <?php if ($success = User()->getSuccess()): ?>
                <div class="alert alert-success"><?=$success?></div>
            <?php endif?>
            <?php if ($error = User()->getError()): ?>
                <div class="alert alert-error"><?=$error?></div>
            <?php endif?>

            <?php if ($model->isAttributeSafe('username')): ?>
                <div class="control-group <?= $model->hasErrors('username') ? 'error' : '' ?>">
                    <?php echo $form->label($model, 'username', array('class' => 'control-label')); ?>
                    <div class="controls">
                        <?=$form->textField($model, 'username')?>
                        <?=$form->error($model, 'username')?>
                    </div>
                </div>
            <?php endif;#($model->isAttributeSafe('username')):?>

            <?php if ($model->isAttributeSafe('email')): ?>
                <div class="control-group <?= $model->hasErrors('email') ? 'error' : '' ?>">
                    <?php echo $form->label($model, 'email', array('class' => 'control-label')); ?>
                    <div class="controls">
                        <?=$form->textField($model, 'email')?>
                        <?=$form->error($model, 'email')?>
                    </div>
                </div>
            <?php endif;#($model->isAttributeSafe('email')):?>

            <?php if ($model->isAttributeSafe('password')): ?>
                <div class="control-group <?= $model->hasErrors('password') ? 'error' : '' ?>">
                    <?php echo $form->label($model, 'password', array('class' => 'control-label')); ?>
                    <div class="controls">
                        <?=$form->passwordField($model, 'password'); ?>
                        <?=$form->error($model, 'password')?>
                    </div>
                </div>
            <?php endif;#($model->isAttributeSafe('password')):?>

            <div class="control-group">
                <div class="controls">
                    <button type="submit" class="btn btn-inverse" tabindex="1">Применить</button>
                </div>
            </div>
        </fieldset>
    </div>
<?php $this->endWidget(); ?>