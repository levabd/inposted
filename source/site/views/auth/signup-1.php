<?php
/** @var $this \site\controllers\AuthController */
/** @var $model \site\models\User */

/** @var $form \CActiveForm */
$form = $this->beginWidget(
    'CActiveForm',
    [
    'id'                     => 'signup-form',
    'enableAjaxValidation'   => false,
    'enableClientValidation' => false,
    'focus'                  => array($model, 'nickname'),
    'errorMessageCssClass'   => 'text-error',
    ]
);
?>
    <div class="modal hide"
         style="z-index: 10002;background:#f8f6ef;"
         id="signup" tabindex="-1"
         role="dialog"
         aria-labelledby="signupModalLabel"
         aria-hidden="true">
        <div class="modal-header" style="background:#fafaf5;">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>

            <h3 id="signupModalLabel" style="display:inline-block;font-family:verdana;font-size:18px;color:#464646;"><img
                    src="<?=Yii()->baseUrl?>/img/logo_icon.png"> Join us</h3>
        </div>
        <div class="modal-body" style="background:#efefef;">
            <div class="join_us">
                <?=$form->textField($model, 'email', array('placeholder' => $model->getAttributeLabel('email'), 'style' => 'width:96%;')); ?>
                <?=$form->error($model, 'email')?>
                <?=$form->passwordField($model, 'newPassword', array('placeholder' => $model->getAttributeLabel('newPassword'), 'class' => 'span5')); ?>
                <?=$form->error($model, 'newPassword')?>
                <?=$form->textField($model, 'nickname', array('placeholder' => $model->getAttributeLabel('nickname'), 'style' => 'width:96%;')); ?>
                <?=$form->error($model, 'nickname')?>
                <input type="submit" class="btn" style="text-decoration: underline;float:right;" value="Next"/>
            </div>
        </div>
    </div>
<?php
$this->endWidget();
if ($model->hasErrors()) {
    Yii()->clientScript->registerScript('signup', '$("#signup").modal("show")');
}
