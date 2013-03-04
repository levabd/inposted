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
         style="z-index: 10002;z-index: 10002;background:#efefef;"
         id="signup" tabindex="-1"
         role="dialog"
         aria-labelledby="signupModalLabel"
         aria-hidden="true">
        <div class="modal-header my_modal1">
            <button type="button" class="close my_modal2" data-dismiss="modal" aria-hidden="true">x</button>

            <h3 id="signupModalLabel" class="my_modal3"><img
                    src="<?=Yii()->baseUrl?>/img/logo_icon.png"> Join us</h3>
        </div>
        <div class="modal-body mini_post_ser">
            <div class="join_us">
                <?=$form->textField($model, 'email', array('placeholder' => $model->getAttributeLabel('email'))); ?>
                <?=$form->error($model, 'email')?>
                <?=$form->passwordField($model, 'newPassword', array('placeholder' => $model->getAttributeLabel('newPassword'))); ?>
                <?=$form->error($model, 'newPassword')?>
                <?=$form->textField($model, 'nickname', array('placeholder' => $model->getAttributeLabel('nickname'))); ?>
                <?=$form->error($model, 'nickname')?>
                <input type="submit" class="btn mypre" value="Next"/>
            </div>
        </div>
    </div>
<?php
$this->endWidget();
if ($model->hasErrors()) {
    Yii()->clientScript->registerScript('signup', '$("#signup").modal("show")');
}
