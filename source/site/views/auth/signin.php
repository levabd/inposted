<div class="span5 well loginForm">
<?php
/**
 * @var $model Signin
 */
$model;
/** @var $form \CActiveForm */
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'signin-form',
    'enableAjaxValidation' => false,
    'enableClientValidation' => false,
    'focus' => array($model, 'username'),
    'errorMessageCssClass' => 'help-block',
    'htmlOptions' => array(
        'class' => 'form-horizontal',
    )
)); ?>
<fieldset>
    <?php if($success = User()->getSuccess()):?>
            <div class="alert alert-success"><?=$success?></div>
    <?php endif?>
    <?php if($error = User()->getError()):?>
        <div class="alert alert-error"><?=$error?></div>
    <?php endif?>
    <div class="control-group <?=$model->hasErrors('username')?'error':''?>">
        <?php echo $form->label($model, 'username', array('class' => 'control-label')); ?>
        <div class="controls">
            <?=$form->textField($model, 'username', array('placeholder' => $model->getAttributeLabel('username'),'tabindex'=>1)); ?>
            <?=$form->error($model, 'username')?>
        </div>
    </div>
    <div class="control-group <?=$model->hasErrors('password')?'error':''?>">
        <?php echo $form->label($model, 'password', array('class' => 'control-label')); ?>
        <div class="controls">
            <?=$form->passwordField($model, 'password', array('placeholder' => $model->getAttributeLabel('password'),'tabindex'=>1)); ?>
            <span class="help-inline"><a id="restore" href="<?=$this->createUrl('restore')?>">I forgot</a></span>
            <?=$form->error($model, 'password')?>
        </div>
    </div>
    <div class="control-group">
        <div class="controls">
            <label class="checkbox">
                <?=$form->checkbox($model, 'rememberMe', array('tabindex'=>1)); ?>
                <?=$model->getAttributeLabel('rememberMe'); ?>
            </label>
            <button type="submit" class="btn btn-primary" tabindex="1">Sign in</button>
        </div>
    </div>
    <div class="control-group">
        <div class="controls">
            Don't have an account? <?=CHtml::link('Sign up', array('signup'), array('tabindex'=>1))?>
        </div>
    </div>
</fieldset>

<?php $this->endWidget(); ?>
</div>
<script type="text/javascript">
    $(function(){
        var $form = $('#signin-form');
        $form.attr('action', $form.attr('action') + window.location.hash);

        var $username = $form.find('#Signin_username');
        var $restore = $('#restore');
        $restore.click(function(e){
            var name = $username.val();
            var url = e.target.href;

            if(name){
                url += '?user=' + encodeURIComponent(name);
            }
            window.location = url;
            e.preventDefault();
        })
    })
</script>